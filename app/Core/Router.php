<?php

namespace App\Core;

/**
 * Single-file path handler: public/index.php is the only script Apache ever
 * executes (see public/.htaccess) — every route below maps a clean URL to a
 * Controller@method.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, array $handler): void
    {
        $paramNames = [];
        $pattern = preg_replace_callback('#\{([a-zA-Z_]+)\}#', function ($m) use (&$paramNames) {
            $paramNames[] = $m[1];
            return '([^/]+)';
        }, rtrim($path, '/') ?: '/');

        $this->routes[] = [
            'method' => $method,
            'regex' => '#^' . $pattern . '$#',
            'params' => $paramNames,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = Request::method();
        $path = Url::currentPath();

        // When Apache uses ErrorDocument 404 /index.php, some hosts leave a
        // trailing script name in the path — normalize so routes still match.
        if ($path === '/index.php' || str_ends_with($path, '/index.php')) {
            $redirect = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REDIRECT_URI'] ?? '';
            if (is_string($redirect) && $redirect !== '') {
                $path = '/' . ltrim(parse_url($redirect, PHP_URL_PATH) ?: $redirect, '/');
                $path = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['regex'], $path, $matches)) {
                array_shift($matches);
                $args = array_combine($route['params'], array_map('urldecode', $matches));
                [$class, $action] = $route['handler'];
                $controller = new $class();
                call_user_func_array([$controller, $action], $args);
                return;
            }
        }

        http_response_code(404);
        View::render('site.404');
    }
}
