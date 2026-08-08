<?php
/**
 * PROJECT STRUCTURE SCANNER
 *
 * Put this file inside:
 *
 *   project/
 *       app/
 *       config/
 *       vendor/
 *       public/
 *           index.php  <-- put THIS file here
 *
 * The scanner assumes that the folder containing this file is "public"
 * and automatically scans the project root one level above it.
 *
 * IMPORTANT:
 * - This is for temporary deployment inspection.
 * - Delete this file immediately after use.
 * - It does NOT read file contents; it only lists structure, sizes,
 *   permissions, modification times, and symlink targets.
 */

declare(strict_types=1);

set_time_limit(0);

$publicDir = __DIR__;
$projectRoot = dirname($publicDir);

/*
 * Directories that are commonly huge or irrelevant to a deployment
 * structure report. Remove any entry if you specifically want it scanned.
 */
$excludedDirectories = [
    '.git',
    'node_modules',
    '.cache',
    'cache',
    'storage/logs',
];

/*
 * Sensitive files are listed by name but their contents are NEVER read.
 */
$sensitiveFileNames = [
    '.env',
    '.env.local',
    '.env.production',
    '.env.development',
];

function normalizePath(string $path): string
{
    $real = realpath($path);
    return $real !== false ? $real : $path;
}

function relativePath(string $path, string $root): string
{
    $path = normalizePath($path);
    $root = rtrim(normalizePath($root), DIRECTORY_SEPARATOR);

    if ($path === $root) {
        return '.';
    }

    if (str_starts_with($path, $root . DIRECTORY_SEPARATOR)) {
        return substr($path, strlen($root) + 1);
    }

    return $path;
}

function formatBytes(int|false $bytes): string
{
    if ($bytes === false) {
        return 'unknown';
    }

    if ($bytes < 1024) {
        return $bytes . ' B';
    }

    if ($bytes < 1024 * 1024) {
        return round($bytes / 1024, 2) . ' KB';
    }

    if ($bytes < 1024 * 1024 * 1024) {
        return round($bytes / (1024 * 1024), 2) . ' MB';
    }

    return round($bytes / (1024 * 1024 * 1024), 2) . ' GB';
}

function permissions(string $path): string
{
    $perms = @fileperms($path);

    if ($perms === false) {
        return 'unknown';
    }

    return substr(sprintf('%o', $perms), -4);
}

function modifiedTime(string $path): string
{
    $time = @filemtime($path);

    if ($time === false) {
        return 'unknown';
    }

    return date('Y-m-d H:i:s', $time);
}

function isExcludedDirectory(string $relative): bool
{
    global $excludedDirectories;

    $relative = str_replace('\\', '/', $relative);

    foreach ($excludedDirectories as $excluded) {
        $excluded = trim(str_replace('\\', '/', $excluded), '/');

        if (
            $relative === $excluded ||
            str_starts_with($relative, $excluded . '/')
        ) {
            return true;
        }
    }

    return false;
}

function isSensitiveFile(string $name): bool
{
    global $sensitiveFileNames;

    return in_array($name, $sensitiveFileNames, true);
}

function scanDirectory(
    string $directory,
    string $root,
    string $prefix = '',
    int &$directories = 0,
    int &$files = 0,
    int &$totalBytes = 0
): string {
    $output = '';

    $items = @scandir($directory);

    if ($items === false) {
        return $prefix . "└── [ACCESS DENIED] " . basename($directory) . "\n";
    }

    $items = array_values(array_diff($items, ['.', '..']));

    usort($items, function ($a, $b) use ($directory) {
        $aPath = $directory . DIRECTORY_SEPARATOR . $a;
        $bPath = $directory . DIRECTORY_SEPARATOR . $b;

        $aDir = @is_dir($aPath);
        $bDir = @is_dir($bPath);

        if ($aDir !== $bDir) {
            return $aDir ? -1 : 1;
        }

        return strnatcasecmp($a, $b);
    });

    $count = count($items);

    foreach ($items as $index => $item) {
        $path = $directory . DIRECTORY_SEPARATOR . $item;
        $last = ($index === $count - 1);

        $branch = $last ? '└── ' : '├── ';
        $childPrefix = $prefix . ($last ? '    ' : '│   ');

        $relative = relativePath($path, $root);

        if (@is_link($path)) {
            $target = @readlink($path);
            $output .= $prefix . $branch . '🔗 ' . $item .
                ' -> ' . ($target !== false ? $target : 'unknown') . "\n";
            continue;
        }

        if (@is_dir($path)) {
            if (isExcludedDirectory($relative)) {
                $output .= $prefix . $branch . '📁 ' . $item .
                    '/ [EXCLUDED FROM SCAN]' . "\n";
                continue;
            }

            $directories++;

            $output .= $prefix . $branch . '📁 ' . $item . '/' .
                ' [perm ' . permissions($path) .
                ', modified ' . modifiedTime($path) . "]\n";

            $output .= scanDirectory(
                $path,
                $root,
                $childPrefix,
                $directories,
                $files,
                $totalBytes
            );

            continue;
        }

        if (@is_file($path)) {
            $files++;

            $size = @filesize($path);

            if ($size !== false) {
                $totalBytes += $size;
            }

            $extra = '';

            if (isSensitiveFile($item)) {
                $extra = ' [SENSITIVE FILE - CONTENT NOT READ]';
            }

            $output .= $prefix . $branch . '📄 ' . $item .
                ' [' . formatBytes($size) .
                ', perm ' . permissions($path) .
                ', modified ' . modifiedTime($path) .
                ']' . $extra . "\n";
        }
    }

    return $output;
}

/*
 * Safety check: this script is intended to be inside /public.
 * If the project root cannot be resolved, stop.
 */
$projectRoot = normalizePath($projectRoot);
$publicDir = normalizePath($publicDir);

if (!is_dir($projectRoot)) {
    http_response_code(500);
    exit('Could not determine the project root.');
}

$directories = 0;
$files = 0;
$totalBytes = 0;

$structure = scanDirectory(
    $projectRoot,
    $projectRoot,
    '',
    $directories,
    $files,
    $totalBytes
);

$report =
    "PROJECT STRUCTURE REPORT\n" .
    "========================\n\n" .
    "Project root:\n" .
    $projectRoot . "\n\n" .
    "Public directory containing this scanner:\n" .
    $publicDir . "\n\n" .
    "Scanner file:\n" .
    __FILE__ . "\n\n" .
    "Summary:\n" .
    "--------\n" .
    "Directories: " . $directories . "\n" .
    "Files:       " . $files . "\n" .
    "File size:   " . formatBytes($totalBytes) . "\n\n" .
    "Directory tree:\n" .
    "---------------\n" .
    basename($projectRoot) . "/\n" .
    $structure;

/*
 * Optional plain-text download.
 */
if (isset($_GET['download']) && $_GET['download'] === '1') {
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="project-structure.txt"');
    echo $report;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Structure Scanner</title>

    <style>
        body {
            margin: 0;
            padding: 25px;
            background: #111827;
            color: #e5e7eb;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .warning {
            background: #7f1d1d;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        button,
        a {
            display: inline-block;
            border: 0;
            border-radius: 6px;
            padding: 11px 16px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover,
        a:hover {
            opacity: .85;
        }

        textarea {
            width: 100%;
            height: 75vh;
            box-sizing: border-box;
            resize: vertical;
            padding: 20px;
            border: 1px solid #374151;
            border-radius: 8px;
            background: #030712;
            color: #86efac;
            font-family: "SFMono-Regular", Consolas, monospace;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Project Structure Scanner</h1>

    <p>
        Scanned from the project root automatically.
        File contents were not read.
    </p>

    <div class="warning">
        <strong>SECURITY:</strong>
        Delete this scanner immediately after you finish inspecting the project.
        It exposes your server's directory structure.
    </div>

    <div class="buttons">
        <button onclick="copyReport()">Copy Structure</button>

        <a href="?download=1">
            Download TXT
        </a>
    </div>

    <textarea id="report" readonly><?=
        htmlspecialchars($report, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
    ?></textarea>

</div>

<script>
function copyReport() {
    const report = document.getElementById('report');

    navigator.clipboard.writeText(report.value)
        .then(() => {
            alert('Project structure copied to clipboard.');
        })
        .catch(() => {
            report.focus();
            report.select();
            document.execCommand('copy');
            alert('Project structure copied to clipboard.');
        });
}
</script>

</body>
</html>
