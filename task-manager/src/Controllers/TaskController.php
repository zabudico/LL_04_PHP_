<?php
namespace App\Controllers;

use App\Models\Task;
use App\Validation\TaskValidator;
use App\Core\SimpleRouter;

class TaskController
{
    public function index()
    {
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $tasks = Task::paginate($currentPage);
        $totalPages = Task::totalPages();
        $currentPage = min($currentPage, $totalPages);
        $latestTasks = Task::latest(2);

        $this->render('tasks/index', [
            'tasks' => $tasks,
            'latestTasks' => $latestTasks,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        $this->render('tasks/create', [
            'errors' => [],
            'old' => []
        ]);
    }

    public function store()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new \RuntimeException('Недействительный CSRF-токен');
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'category' => $_POST['category'] ?? '',
            'steps' => array_filter(array_map('trim', $_POST['steps'] ?? []))
        ];

        $validator = new TaskValidator();
        if ($validator->validate($data)) {
            Task::create($data);
            $_SESSION['success'] = 'Задача успешно создана!';
            SimpleRouter::redirect('/');
            exit;
        }

        $this->render('tasks/create', [
            'errors' => $validator->getErrors(),
            'old' => $data
        ]);
    }

    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            SimpleRouter::redirect('/');
        }
        $this->render('tasks/show', ['task' => $task]);
    }

    public function delete(string $id)
    {
        Task::delete($id);
        SimpleRouter::redirect('/');
    }

    private function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../templates/layouts/main.php";
    }
}