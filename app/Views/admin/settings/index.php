<form method="post" action="<?= e(url('admin/settings')) ?>" class="panel form-grid">
    <?= csrf_field() ?>
    <?php foreach ($grouped as $group => $rows): ?>
        <h2><?= e(ucwords(str_replace('_', ' ', $group))) ?></h2>
        <?php foreach ($rows as $row): ?>
            <label><?= e($row['label']) ?>
                <?php if ($row['field_type'] === 'textarea'): ?>
                    <textarea name="settings[<?= e($row['setting_key']) ?>]" rows="3"><?= e($row['setting_value']) ?></textarea>
                <?php elseif ($row['field_type'] === 'select'): ?>
                    <select name="settings[<?= e($row['setting_key']) ?>]">
                        <option value="1" <?= $row['setting_value'] === '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= $row['setting_value'] === '0' ? 'selected' : '' ?>>No</option>
                    </select>
                <?php else: ?>
                    <input name="settings[<?= e($row['setting_key']) ?>]" value="<?= e($row['setting_value']) ?>">
                <?php endif; ?>
            </label>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <div class="form-actions"><button type="submit">Save settings</button></div>
</form>
