<!-- Последние 2 задачи -->
<?php if (!empty($latestTasks)): ?>
    <h2>Последние задачи</h2>
    <div class="latest-tasks">
        <?php foreach ($latestTasks as $task): ?>
            <div class="task-card">
                <h3><?= htmlspecialchars($task['title']) ?></h3>
                <div class="meta">
                    <span class="category"><?= $task['category'] ?></span>
                    <span class="date"><?= date('d.m.Y H:i', strtotime($task['created_at'])) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
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
            <a href="/tasks/<?= $task['id'] ?>" class="btn">Подробнее</a>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/?page=<?= $i ?>" class="<?= $i == $currentPage ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>