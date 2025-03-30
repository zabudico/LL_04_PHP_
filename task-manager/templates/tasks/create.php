<form method="POST" action="/tasks/store">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="form-group">
        <label>Название:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>"
            class="<?= isset($errors['title']) ? 'error' : '' ?>">
        <?php if (isset($errors['title'])): ?>
            <span class="error-message"><?= $errors['title'] ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Категория:</label>
        <select name="category">
            <option value="work" <?= ($old['category'] ?? '') === 'work' ? 'selected' : '' ?>>Работа</option>
            <option value="personal" <?= ($old['category'] ?? '') === 'personal' ? 'selected' : '' ?>>Личное</option>
        </select>
    </div>

    <div class="form-group">
        <label>Шаги выполнения:</label>
        <div id="steps-container">
            <?php foreach ($old['steps'] ?? [''] as $step): ?>
                <input type="text" name="steps[]" value="<?= htmlspecialchars($step) ?>"
                    class="<?= isset($errors['steps']) ? 'error' : '' ?>">
            <?php endforeach; ?>
        </div>
        <button type="button" data-add-step class="btn">Добавить шаг</button>
        <?php if (isset($errors['steps'])): ?>
            <span class="error-message"><?= $errors['steps'] ?></span>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn">Создать задачу</button>
</form>

<?php if (isset($errors['title'])): ?>
    <div class="error-message"><?= $errors['title'] ?></div>
<?php endif; ?>