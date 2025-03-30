<?php
namespace App\Models;

/**
 * Модель для работы с задачами.
 */
class Task
{
    const ITEMS_PER_PAGE = 5;
    const STORAGE_PATH = __DIR__ . '/../../data/tasks.json';

    /**
     * Возвращает все задачи.
     * @return array
     */
    public static function all(): array
    {
        self::ensureStorageExists();
        return json_decode(file_get_contents(self::STORAGE_PATH), true) ?: [];
    }


    /**
     * Находит задачу по ID.
     * @param string $id Идентификатор задачи
     * @return array|null Найденная задача или null, если не найдена
     */
    public static function find(string $id): ?array
    {
        $tasks = self::all();
        foreach ($tasks as $task) {
            if ($task['id'] === $id) {
                return $task;
            }
        }
        return null;
    }

    /**
     * Возвращает задачи с пагинацией.
     * @param int $page Номер страницы
     * @return array
     */
    public static function paginate(int $page): array
    {
        $tasks = self::all();
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        return array_slice($tasks, $offset, self::ITEMS_PER_PAGE);
    }

    /**
     * Ищет задачи по запросу.
     * @param string $query Поисковый запрос
     * @return array
     */
    public static function search(string $query): array
    {
        $tasks = self::all();
        if (empty($query))
            return $tasks;

        $query = strtolower($query);
        return array_filter($tasks, function ($task) use ($query) {
            $title = strtolower($task['title']);
            $description = strtolower($task['description'] ?? '');
            return str_contains($title, $query) || str_contains($description, $query);
        });
    }

    /**
     * Возвращает последние задачи.
     * @param int $count Количество задач
     * @return array
     */
    public static function latest(int $count): array
    {
        $tasks = self::all();
        return array_slice($tasks, -$count);
    }

    /**
     * Создает новую задачу.
     * @param array $data Данные задачи
     */
    public static function create(array $data): void
    {
        $tasks = self::all();
        $tasks[] = [
            'id' => uniqid(),
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'],
            'steps' => $data['steps'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        self::save($tasks);
    }

    /**
     * Удаляет задачу.
     * @param string $id Идентификатор задачи
     */
    public static function delete(string $id): void
    {
        $tasks = array_filter(self::all(), fn($task) => $task['id'] !== $id);
        self::save(array_values($tasks));
    }

    /**
     * Сохраняет задачи в файл.
     * @param array $tasks Массив задач
     */
    private static function save(array $tasks): void
    {
        file_put_contents(self::STORAGE_PATH, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Проверяет существование файла хранилища.
     */
    private static function ensureStorageExists(): void
    {
        $dir = dirname(self::STORAGE_PATH);

        // Создаем папку, если её нет
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // Создаем файл, если его нет
        if (!file_exists(self::STORAGE_PATH)) {
            file_put_contents(self::STORAGE_PATH, '[]');
        }
    }
}

