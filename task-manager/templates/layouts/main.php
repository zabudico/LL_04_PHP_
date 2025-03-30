<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <header class="header">
            <h1>Task Manager</h1>
            <nav>
                <a href="/" class="btn">Главная</a>
                <a href="/latest" class="btn">Последние задачи</a>
                <a href="/tasks/create" class="btn">Создать задачу</a>
            </nav>
        </header>

        <main>
            <?php include __DIR__ . "/../{$view}.php"; ?>
        </main>
    </div>
    <script src="/js/script.js"></script>




</body>

</html>

<?php
// Генерация CSRF-токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>