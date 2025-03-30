<form method="GET" action="/" class="search-form">
    <div class="form-group">
        <input type="text" name="search" placeholder="Поиск по названию или описанию"
            value="<?= htmlspecialchars($searchQuery ?? '') ?>">
        <button type="submit" class="btn btn-primary">Найти</button>
    </div>
</form>

<?php if (empty($tasks) && !empty($searchQuery)): ?>
    <div class="no-results">
        По запросу "<?= htmlspecialchars($searchQuery) ?>" ничего не найдено.
    </div>
<?php endif; ?>

<div class="task-list">
    <?php foreach ($tasks as $task): ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($task['title']) ?></h3>
            <div class="meta">
                <span class="category-badge <?= $task['category'] ?>">
                    <?= $task['category'] === 'work' ? 'Работа' : 'Личное' ?>
                </span>
                <span class="date"><?= date('d.m.Y H:i', strtotime($task['created_at'])) ?></span>
            </div>
            <div class="description">
                <?= htmlspecialchars(substr($task['description'], 0, 100)) . '...' ?>
            </div>
            <a href="/tasks/<?= $task['id'] ?>" class="btn btn-primary">Подробнее</a>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/?page=<?= $i ?>&search=<?= urlencode($searchQuery) ?>" class="<?= $i == $currentPage ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>