<div class="task-card">
    <h3><?= htmlspecialchars($task['title']) ?></h3>
    <div class="meta">
        <span class="category-badge <?= $task['category'] ?>">
            <?= $task['category'] === 'work' ? 'Работа' : 'Личное' ?>
        </span>
        <span class="date"><?= date('d.m.Y H:i', strtotime($task['created_at'])) ?></span>
    </div>

    <div class="description">
        <?= nl2br(htmlspecialchars($task['description'])) ?>
    </div>

    <div class="steps">
        <?php foreach ($task['steps'] as $index => $step): ?>
            <div class="step"><?= ($index + 1) ?>. <?= htmlspecialchars($step) ?></div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="/tasks/<?= $task['id'] ?>/delete">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger" data-delete-task>Удалить задачу</button>
    </form>
</div>