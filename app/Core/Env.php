<?php

namespace App\Core;

use Dotenv\Exception\InvalidFileException;

/**
 * Loads .env (via vlucas/phpdotenv) once per request from the project root
 * (one level above public/), and exposes a global env() helper.
 */
class Env
{
    private static bool $loaded = false;

    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }
        $root = dirname(__DIR__, 2);
        if (file_exists($root . '/.env')) {
            try {
                $dotenv = \Dotenv\Dotenv::createImmutable($root);
                $dotenv->safeLoad();
            } catch (InvalidFileException $e) {
                throw new InvalidFileException(
                    $e->getMessage()
                    . ' Tip: quote any .env value that contains spaces'
                    . ' (e.g. MAIL_PASSWORD="xxxx xxxx xxxx xxxx" for a Gmail App Password).'
                    . ' See .env.example.',
                    (int) $e->getCode(),
                    $e
                );
            }
        }
        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($value === false || $value === null || $value === '') {
            return $default;
        }
        return $value;
    }
}
