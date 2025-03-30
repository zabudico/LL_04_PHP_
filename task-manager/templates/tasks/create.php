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
        <select name="category" class="<?= isset($errors['category']) ? 'error' : '' ?>">
            <option value="work" <?= ($old['category'] ?? '') === 'work' ? 'selected' : '' ?>>Работа</option>
            <option value="personal" <?= ($old['category'] ?? '') === 'personal' ? 'selected' : '' ?>>Личное</option>
        </select>
        <?php if (isset($errors['category'])): ?>
            <span class="error-message"><?= $errors['category'] ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Описание:</label>
        <textarea name="description" class="<?= isset($errors['description']) ? 'error' : '' ?>"
            rows="4"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        <?php if (isset($errors['description'])): ?>
            <span class="error-message"><?= $errors['description'] ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>Шаги выполнения:</label>
        <div id="steps-container">
            <?php foreach ($old['steps'] ?? [''] as $step): ?>
                <input type="text" name="steps[]" value="<?= htmlspecialchars($step) ?>"
                    class="<?= isset($errors['steps']) ? 'error' : '' ?>">
            <?php endforeach; ?>
        </div>
        <button type="button" data-add-step class="btn btn-primary">Добавить шаг</button>
        <?php if (isset($errors['steps'])): ?>
            <span class="error-message"><?= $errors['steps'] ?></span>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Создать задачу</button>
</form>