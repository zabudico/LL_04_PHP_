<?php
namespace App\Models;

class Task
{
    const ITEMS_PER_PAGE = 5;
    const STORAGE_PATH = __DIR__ . '/../../../data/tasks.json';

    public static function all(): array
    {
        self::ensureStorageExists();
        return json_decode(file_get_contents(self::STORAGE_PATH), true) ?: [];
    }

    public static function paginate(int $page): array
    {
        $tasks = self::all();
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;
        return array_slice($tasks, $offset, self::ITEMS_PER_PAGE);
    }

    public static function totalPages(): int
    {
        return ceil(count(self::all()) / self::ITEMS_PER_PAGE);
    }

    public static function latest(int $count): array
    {
        $tasks = self::all();
        return array_slice($tasks, -$count);
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $task) {
            if ($task['id'] === $id) {
                return $task;
            }
        }
        return null;
    }

    public static function create(array $data): void
    {
        $tasks = self::all();
        $tasks[] = [
            'id' => uniqid(),
            'title' => $data['title'],
            'category' => $data['category'],
            'steps' => $data['steps'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        self::save($tasks);
    }

    public static function delete(string $id): void
    {
        $tasks = array_filter(self::all(), fn($task) => $task['id'] !== $id);
        self::save(array_values($tasks));
    }

    private static function save(array $tasks): void
    {
        file_put_contents(self::STORAGE_PATH, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private static function ensureStorageExists(): void
    {
        $dir = dirname(self::STORAGE_PATH);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists(self::STORAGE_PATH)) {
            file_put_contents(self::STORAGE_PATH, '[]');
        }
    }
}