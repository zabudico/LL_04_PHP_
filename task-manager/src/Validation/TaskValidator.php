<?php
namespace App\Validation;

use App\Models\Task;

class TaskValidator
{
    private $errors = [];

    public function validate(array $data): bool
    {
        $this->validateTitle($data['title'] ?? '');
        $this->validateCategory($data['category'] ?? '');
        $this->validateSteps($data['steps'] ?? []);
        return empty($this->errors);
    }

    private function validateTitle(string $title): void
    {
        $title = trim($title);
        if (empty($title)) {
            $this->errors['title'] = 'Название обязательно';
            return;
        }

        // Замена mb_strlen() на strlen() (для работы без расширения mbstring)
        if (strlen($title) < 3) {
            $this->errors['title'] = 'Минимум 3 символа';
        }

        // Замена mb_strtolower() на strtolower()
        foreach (Task::all() as $task) {
            if (strtolower($task['title']) === strtolower($title)) {
                $this->errors['title'] = 'Задача с таким названием уже существует';
                break;
            }
        }
    }

    private function validateCategory(string $category): void
    {
        if (!in_array($category, ['work', 'personal'])) {
            $this->errors['category'] = 'Неверная категория';
        }
    }

    private function validateSteps(array $steps): void
    {
        $steps = array_filter($steps);
        if (empty($steps)) {
            $this->errors['steps'] = 'Добавьте хотя бы один шаг';
            return;
        }

        foreach ($steps as $step) {
            if (trim($step) === '') {
                $this->errors['steps'] = 'Шаги не могут быть пустыми';
                break;
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}