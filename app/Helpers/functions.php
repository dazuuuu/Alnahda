<?php
/**
 * Global helper functions available to every controller and view.
 */

use App\Core\Url;

function url(string $path = '/'): string
{
    return Url::to($path);
}

function asset(string $path): string
{
    return Url::asset($path);
}

/**
 * Same as asset(), but appends the file's last-modified time as a ?v=
 * cache-buster — use this for CSS/JS so browsers never serve a stale copy
 * after an edit. Falls back to asset() unversioned if the file can't be found.
 */
function versionedAsset(string $path): string
{
    $full = dirname(__DIR__, 2) . '/public/' . ltrim($path, '/');
    $version = is_file($full) ? filemtime($full) : null;
    return asset($path) . ($version ? '?v=' . $version : '');
}

function e($str): string
{
    return htmlspecialchars((string) ($str ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Uploaded images are stored relative to public/ (assets/uploads/...); this
 * also passes through absolute URLs unchanged in case a seed ever uses one.
 */
function imageUrl(?string $path): string
{
    if (!$path) {
        return '';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return asset($path);
}

/** Renders a yes/no/null application field as a readable label. */
function yesNo($value): string
{
    if ($value === 'yes') {
        return 'Yes';
    }
    if ($value === 'no') {
        return 'No';
    }
    return '—';
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function csrfVerify(?string $token): bool
{
    return !empty($_SESSION['csrf_token']) && $token !== null && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flashSuccess(string $message): void
{
    $_SESSION['flash_success'] = $message;
}

function flashError(string $message): void
{
    $_SESSION['flash_error'] = $message;
}
