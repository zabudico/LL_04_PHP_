<?php
namespace App\Core;

class SimpleRouter
{
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}