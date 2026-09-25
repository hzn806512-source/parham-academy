<?php
declare(strict_types=1);

/** روتر ساده با پشتیبانی پارامتر پویا: /course/{slug} */
final class Router
{
    private array $routes = [];

    public function get(string $pattern, array $action): void
    {
        $this->routes[] = ['GET', $pattern, $action];
    }

    public function post(string $pattern, array $action): void
    {
        $this->routes[] = ['POST', $pattern, $action];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path       = '/' . trim($uri, '/');
        $pathExists = false;

        foreach ($this->routes as [$routeMethod, $pattern, $action]) {
            $regex = '#^' . preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#u';

            if (!preg_match($regex, $path, $matches)) {
                continue;
            }

            $pathExists = true;

            if ($routeMethod !== $method) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            [$controller, $function] = $action;

            if (!class_exists($controller) || !method_exists($controller, $function)) {
                abort(500, 'Controller not found: ' . $controller . '::' . $function);
            }

            call_user_func([$controller, $function], $params);
            return;
        }

        if ($pathExists) {
            abort(405, 'این درخواست مجاز نیست.');
        }

        abort(404, 'صفحه‌ای که به دنبال آن هستید پیدا نشد.');
    }
}