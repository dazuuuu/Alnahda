<?php

namespace App\Core;

/**
 * Minimal PHP-include view renderer. Controllers call View::render() once per
 * "chunk" (layout-header, page body, layout-footer).
 *
 * Templates live in the web root's views/ folder:
 *   local:     public/views/
 *   hosting:   public_html/views/  (same files you deploy from public/)
 *
 * Path comes from PUBLIC_PATH (set in public/index.php), so it works whether
 * the folder is named public or public_html.
 */
class View
{
    private static string $basePath;

    public static function render(string $view, array $data = []): void
    {
        if (!isset(self::$basePath)) {
            self::$basePath = self::viewsDirectory();
        }
        $file = self::$basePath . str_replace('.', '/', $view) . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View not found: {$view} ({$file})");
        }
        extract($data, EXTR_SKIP);
        require $file;
    }

    public static function capture(string $view, array $data = []): string
    {
        ob_start();
        self::render($view, $data);
        return ob_get_clean();
    }

    private static function viewsDirectory(): string
    {
        if (defined('PUBLIC_PATH')) {
            return rtrim(PUBLIC_PATH, '/\\') . '/views/';
        }
        // CLI / fallback: project-root/public/views
        return dirname(__DIR__, 2) . '/public/views/';
    }
}
