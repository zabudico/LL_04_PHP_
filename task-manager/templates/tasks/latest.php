<?php
// Получаем лимит из GET-параметра (по умолчанию 2)
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 2;
// Ограничиваем лимит разумными значениями
$limit = max(1, min($limit, 50)); // Не меньше 1 и не больше 50
?>

<div class="latest-tasks">
    <h1>Последние задачи</h1>

    <!-- Форма для выбора количества задач -->
    <form method="GET" class="limit-selector">
        <label>
            Показать:
            <select name="limit" onchange="this.form.submit()">
                <option value="2" <?= $limit == 2 ? 'selected' : '' ?>>2</option>
                <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
            </select>
            последних задач
        </label>
    </form>

    <?php if (empty($tasks)): ?>
        <p>Нет задач для отображения</p>
    <?php else: ?>
        <?php
        // Получаем N последних задач
        $latestTasks = array_slice($tasks, -$limit);
        ?>

        <div class="task-list">
            <?php foreach ($latestTasks as $task): ?>
                <div class="task-card">
                    <h3><?= htmlspecialchars($task['title']) ?></h3>
                    <div class="meta">
                        <span class="category-badge <?= htmlspecialchars($task['category']) ?>">
                            <?= $task['category'] === 'work' ? 'Работа' : 'Личное' ?>
                        </span>
                        <span class="date">
                            <?= date('d.m.Y H:i', strtotime($task['created_at'])) ?>
                        </span>
                    </div>
                    <p><?= htmlspecialchars($task['description']) ?></p>
                    <a href="/tasks/<?= $task['id'] ?>" class="btn btn-primary">
                        Подробнее
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>