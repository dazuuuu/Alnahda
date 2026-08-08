<?php

namespace App\Core;

/**
 * Computes the app's base path once per request so every generated link/asset
 * URL works regardless of where the vhost points.
 *
 * Prefer APP_URL from .env (path portion only) so a host that rewrites into
 * public/ does not leak "/public" into links. Fall back to SCRIPT_NAME.
 *
 * Local example:  APP_URL=http://localhost/Alnahda/public  → base "/Alnahda/public"
 * Production:     APP_URL=https://alnahdaagency.com        → base "" (domain root)
 */
class Url
{
    private static ?string $basePath = null;

    public static function init(): void
    {
        if (self::$basePath !== null) {
            return;
        }

        $fromEnv = self::basePathFromAppUrl();
        if ($fromEnv !== null) {
            self::$basePath = $fromEnv;
            return;
        }

        // dirname(SCRIPT_NAME) for public/index.php is the folder the browser sees it in.
        // When the project-root .htaccess rewrites into public/, SCRIPT_NAME often
        // becomes "/public/index.php" — strip that misleading "/public" segment.
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $scriptDir = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');
        if ($scriptDir === '/public' || str_ends_with($scriptDir, '/public')) {
            $scriptDir = substr($scriptDir, 0, -strlen('/public'));
        }
        self::$basePath = $scriptDir === '/' ? '' : $scriptDir;
    }

    /**
     * Path prefix from APP_URL, or null if APP_URL is unset/invalid.
     * https://alnahdaagency.com      → ""
     * https://alnahdaagency.com/app  → "/app"
     * http://localhost/Alnahda/public → "/Alnahda/public"
     */
    private static function basePathFromAppUrl(): ?string
    {
        $appUrl = trim((string) Env::get('APP_URL', ''));
        if ($appUrl === '') {
            return null;
        }
        $parts = parse_url($appUrl);
        if ($parts === false) {
            return null;
        }
        $path = $parts['path'] ?? '';
        $path = '/' . trim($path, '/');
        return $path === '/' ? '' : rtrim($path, '/');
    }

    public static function basePath(): string
    {
        self::init();
        return self::$basePath;
    }

    /** App-relative URL for a route, e.g. Url::to('/admin') → "/admin" or "/Alnahda/public/admin" */
    public static function to(string $path = '/'): string
    {
        self::init();
        return self::$basePath . '/' . ltrim($path, '/');
    }

    /**
     * Fully-qualified URL for emails / external links.
     * Prefers APP_URL from .env, then falls back to the current request host.
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
        if (self::$basePath !== '' && strpos($uri, self::$basePath) === 0) {
            $uri = substr($uri, strlen(self::$basePath));
        }
        // Also strip a bare "/public" prefix if a rewritten SCRIPT_NAME leaked into bookmarks/links.
        if (strpos($uri, '/public/') === 0) {
            $uri = substr($uri, strlen('/public'));
        } elseif ($uri === '/public') {
            $uri = '/';
        }
        $uri = '/' . ltrim($uri, '/');
        return rtrim($uri, '/') === '' ? '/' : rtrim($uri, '/');
    }
}
