<?php

/**
 * Website Structure Viewer
 *
 * Upload this file to the directory you want to inspect.
 * It recursively displays folders and files.
 *
 * IMPORTANT:
 * Delete this file after you are finished using it.
 */

$root = __DIR__;

/**
 * Directories/files we don't want to expose.
 */
$excluded = [
    '.git',
    '.gitignore',
    '.DS_Store',
    'node_modules',
];

/**
 * Check whether a path should be excluded.
 */
function isExcluded($name)
{
    global $excluded;
    return in_array($name, $excluded, true);
}

/**
 * Recursively build the directory tree.
 */
function buildTree($directory, $prefix = '')
{
    $items = @scandir($directory);

    if ($items === false) {
        return '';
    }

    // Remove . and ..
    $items = array_diff($items, ['.', '..']);

    // Sort folders/files alphabetically
    sort($items, SORT_NATURAL | SORT_FLAG_CASE);

    $output = '';

    foreach ($items as $item) {

        if (isExcluded($item)) {
            continue;
        }

        $fullPath = $directory . DIRECTORY_SEPARATOR . $item;

        if (is_dir($fullPath)) {

            $output .= $prefix . "├── 📁 " . $item . "/\n";

            $output .= buildTree(
                $fullPath,
                $prefix . "│   "
            );

        } else {

            $size = @filesize($fullPath);

            if ($size !== false) {
                $output .= $prefix . "├── 📄 " . $item .
                    " (" . formatBytes($size) . ")\n";
            } else {
                $output .= $prefix . "├── 📄 " . $item . "\n";
            }
        }
    }

    return $output;
}

/**
 * Convert bytes to readable size.
 */
function formatBytes($bytes)
{
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

$structure = "WEBSITE DIRECTORY STRUCTURE\n";
$structure .= "===========================\n\n";
$structure .= "Root: " . $root . "\n\n";

$structure .= buildTree($root);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Website Structure</title>

    <style>
        body {
            font-family: monospace;
            background: #111;
            color: #eee;
            padding: 30px;
        }

        h1 {
            font-family: Arial, sans-serif;
        }

        textarea {
            width: 100%;
            height: 80vh;
            box-sizing: border-box;
            background: #000;
            color: #00ff88;
            border: 1px solid #444;
            padding: 20px;
            font-family: monospace;
            font-size: 14px;
            line-height: 1.5;
            resize: vertical;
        }

        button {
            padding: 12px 20px;
            margin-bottom: 15px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>Website Directory Structure</h1>

<button onclick="copyStructure()">
    Copy Structure
</button>

<textarea id="structure" readonly><?php
echo htmlspecialchars($structure);
?></textarea>

<script>
function copyStructure() {
    const textarea = document.getElementById('structure');

    textarea.select();
    textarea.setSelectionRange(0, 999999);

    navigator.clipboard.writeText(textarea.value)
        .then(() => {
            alert('Directory structure copied!');
        })
        .catch(() => {
            document.execCommand('copy');
            alert('Directory structure copied!');
        });
}
</script>

</body>
</html>