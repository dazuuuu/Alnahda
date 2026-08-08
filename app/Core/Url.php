<?php

namespace App\Core;

/**
 * Computes the app's base path once per request (e.g. "/Alnahda/public"
 * when hosted in a subfolder, or "" at domain root) so every generated link/asset
 * URL works regardless of where the vhost points.
 *
 * Asset and route URLs always follow the live request (SCRIPT_NAME), not APP_URL,
 * so CSS/JS keep working locally even if .env still has a production domain.
 */
class Url
{
    private static ?string $basePath = null;

    public static function init(): void
    {
        if (self::$basePath !== null) {
            return;
        }

        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $scriptDir = dirname($scriptName);
        $scriptDir = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');

        // Project-root .htaccess rewrite makes SCRIPT_NAME "/public/index.php".
        // That "/public" must not appear in browser URLs (assets or routes).
        // Do NOT strip when the app is really installed under ".../Alnahda/public".
        if ($scriptDir === '/public') {
            $scriptDir = '';
        }

        self::$basePath = $scriptDir;
    }

    public static function basePath(): string
    {
        self::init();
        return self::$basePath;
    }

    /** Absolute app URL for a route, e.g. Url::to('/admin/products') */
    public static function to(string $path = '/'): string
    {
        self::init();
        return self::$basePath . '/' . ltrim($path, '/');
    }

    /**
     * Fully-qualified URL for emails / external links.
     * Uses APP_URL when set; otherwise the current request host + base path.
     */
    public static function absolute(string $path = '/'): string
    {
        $configured = rtrim((string) Env::get('APP_URL', ''), '/');
        if ($configured !== '') {
            return $configured . '/' . ltrim($path, '/');
        }

        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . self::to($path);
    }

    /** Absolute app URL for a static file under public/, e.g. Url::asset('assets/css/app.css') */
    public static function asset(string $path): string
    {
        return self::to($path);
    }

    /**
     * The current request path relative to the app base (e.g. "/admin/products"),
     * with the query string stripped and a leading slash guaranteed.
     */
    public static function currentPath(): string
    {
        self::init();
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        // ErrorDocument 404 /index.php — prefer the original requested path.
        if ($uri === '/index.php' || $uri === self::$basePath . '/index.php') {
            foreach (['REDIRECT_URL', 'REDIRECT_URI', 'HTTP_X_ORIGINAL_URL'] as $key) {
                $redirect = $_SERVER[$key] ?? null;
                if (is_string($redirect) && $redirect !== '' && $redirect !== '/index.php') {
                    $uri = parse_url($redirect, PHP_URL_PATH) ?: $redirect;
                    break;
                }
            }
        }

        if (self::$basePath !== '' && strpos($uri, self::$basePath) === 0) {
            $uri = substr($uri, strlen(self::$basePath));
        }
        // Stale bookmarks/links that still include a leading /public/ (rewrite artifact).
        if (self::$basePath === '') {
            if (strpos($uri, '/public/') === 0) {
                $uri = substr($uri, strlen('/public'));
            } elseif ($uri === '/public') {
                $uri = '/';
            }
        }
        $uri = '/' . ltrim((string) $uri, '/');
        return rtrim($uri, '/') === '' ? '/' : rtrim($uri, '/');
    }
}
