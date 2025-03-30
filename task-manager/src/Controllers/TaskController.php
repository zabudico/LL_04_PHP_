<?php
namespace App\Controllers;

use App\Models\Task;
use App\Validation\TaskValidator;
use App\Core\SimpleRouter;

/**
 * Контроллер для управления задачами.
 */
class TaskController
{
    /**
     * Отображает главную страницу с поиском и пагинацией.
     */
    public function index()
    {
        $searchQuery = trim($_GET['search'] ?? '');
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));

        $tasks = Task::search($searchQuery);
        $totalItems = count($tasks);
        $totalPages = ceil($totalItems / Task::ITEMS_PER_PAGE);
        $currentPage = min($currentPage, $totalPages);
        $offset = ($currentPage - 1) * Task::ITEMS_PER_PAGE;
        $paginatedTasks = array_slice($tasks, $offset, Task::ITEMS_PER_PAGE);

        $this->render('tasks/index', [
            'tasks' => $paginatedTasks,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'searchQuery' => $searchQuery
        ]);
    }

    /**
     * Отображает последние задачи.
     */
    public function latest()
    {
        $tasks = Task::latest(10);
        $this->render('tasks/latest', ['tasks' => $tasks]);
    }

    /**
     * Отображает форму создания задачи.
     */
    public function create()
    {
        $this->render('tasks/create', [
            'errors' => [],
            'old' => []
        ]);
    }

    /**
     * Обрабатывает отправку формы.
     */
    public function store()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new \RuntimeException('Недействительный CSRF-токен');
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'category' => $_POST['category'] ?? '',
            'description' => trim($_POST['description'] ?? ''),
            'steps' => array_filter(array_map('trim', $_POST['steps'] ?? []))
        ];

        $validator = new TaskValidator();
        if ($validator->validate($data)) {
            Task::create($data);
            $_SESSION['success'] = 'Задача успешно создана!';
            SimpleRouter::redirect('/');
        }

        $this->render('tasks/create', [
            'errors' => $validator->getErrors(),
            'old' => $data
        ]);
    }

    /**
     * Отображает детали задачи.
     * @param string $id Идентификатор задачи
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (!$task)
            SimpleRouter::redirect('/');
        $this->render('tasks/show', ['task' => $task]);
    }

    /**
     * Удаляет задачу.
     * @param string $id Идентификатор задачи
     */
    public function delete(string $id)
    {
        Task::delete($id);
        $_SESSION['success'] = 'Задача удалена!';
        SimpleRouter::redirect('/');
    }

    /**
     * Рендерит шаблон.
     * @param string $view Название шаблона
     * @param array $data Данные для передачи в шаблон
     */
    private function render(string $view, array $data = [])
    {
        extract($data);
        include __DIR__ . "/../../templates/layouts/main.php";
    }
}
