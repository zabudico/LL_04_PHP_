<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once __DIR__ . '/../src/Controllers/TaskController.php';
require_once __DIR__ . '/../src/Models/Task.php';
require_once __DIR__ . '/../src/Validation/TaskValidator.php';
require_once __DIR__ . '/../src/core/SimpleRouter.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET' && $uri === '/') {
        (new App\Controllers\TaskController())->index();
    } elseif ($method === 'GET' && $uri === '/tasks/create') {
        (new App\Controllers\TaskController())->create();
    } elseif ($method === 'POST' && $uri === '/tasks/store') {
        (new App\Controllers\TaskController())->store();
    } elseif ($method === 'GET' && preg_match('#^/tasks/(\w+)$#', $uri, $matches)) {
        (new App\Controllers\TaskController())->show($matches[1]);
    } elseif ($method === 'POST' && preg_match('#^/tasks/(\w+)/delete$#', $uri, $matches)) {
        (new App\Controllers\TaskController())->delete($matches[1]);
    } else {
        http_response_code(404);
        echo 'Страница не найдена';
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'Произошла внутренняя ошибка сервера: ' . $e->getMessage();
}