<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/bootstrap.php';
$router = require BASE_PATH . '/app/routes.php';

$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($requestUri, $scriptName)) {
    $uri = substr($requestUri, strlen($scriptName));
} else {
    $uri = $requestUri;
}

$uri = '/' . trim($uri, '/');
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

$router->dispatch($method, $uri);
