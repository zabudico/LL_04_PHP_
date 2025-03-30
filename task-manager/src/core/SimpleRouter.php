<?php
namespace App\Core;

/**
 * Простой роутер.
 */
class SimpleRouter
{
    /**
     * Выполняет редирект.
     * @param string $url Целевой URL
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}