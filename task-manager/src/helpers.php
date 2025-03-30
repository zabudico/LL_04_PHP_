<?php
/**
 * Экранирует HTML-сущности в массиве данных.
 * @param array $data Входные данные
 * @return array
 */
function escape(array $data): array
{
    return array_map(fn($value) => htmlspecialchars($value, ENT_QUOTES), $data);
}

/**
 * Возвращает CSRF-токен.
 * @return string
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}