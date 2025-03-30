<?php
namespace App\Validation;

use App\Models\Task;

/**
 * Валидатор для задач.
 */
class TaskValidator
{
    private array $errors = [];

    /**
     * Проверяет данные задачи.
     * @param array $data Данные формы
     * @return bool
     */
    public function validate(array $data): bool
    {
        $this->validateTitle($data['title'] ?? '');
        $this->validateCategory($data['category'] ?? '');
        $this->validateDescription($data['description'] ?? '');
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

        if (strlen($title) < 3) {
            $this->errors['title'] = 'Минимум 3 символа';
        }

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

    private function validateDescription(string $description): void
    {
        $description = trim($description);
        if (empty($description)) {
            $this->errors['description'] = 'Описание обязательно';
        } elseif (strlen($description) < 10) {
            $this->errors['description'] = 'Минимум 10 символов';
        }
    }

    private function validateSteps(array $steps): void
    {
        $steps = array_filter($steps);
        if (empty($steps)) {
            $this->errors['steps'] = 'Добавьте хотя бы один шаг';
        }
    }

    /**
     * Возвращает ошибки валидации.
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
