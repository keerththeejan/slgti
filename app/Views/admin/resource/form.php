<?php
$locked = !can($config['permission']);
$action = $record
    ? url('admin/' . $resource . '/' . $record['id'])
    : url('admin/' . $resource);
?>
<form class="panel form-grid" method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if ($record && $resource === 'applications'):
        $documents = \App\Core\Model::fetchAll('SELECT * FROM application_documents WHERE application_id = ?', [(int) $record['id']]);
        if ($documents): ?>
            <div class="record-details">
                <?php foreach ($documents as $document): ?>
                    <div><dt>Document</dt><dd><a href="<?= e(url('admin/applications/documents/' . $document['id'])) ?>"><?= e($document['original_name']) ?></a></dd></div>
                <?php endforeach; ?>
            </div>
        <?php endif; endif; ?>
    <?php if ($record && in_array($resource, ['applications', 'messages'], true)): ?>
        <dl class="record-details">
            <?php foreach ($record as $key => $value): if (in_array($key, ['admin_notes', 'status', 'password'], true)) continue; ?>
                <div><dt><?= e(ucwords(str_replace('_', ' ', $key))) ?></dt><dd><?= e((string) $value) ?></dd></div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php foreach ($config['fields'] as $name => $field):
        $type = $field['type'];
        $value = old($name, $record[$name] ?? '');
        if ($type === 'datetime' && $value) {
            $value = date('Y-m-d\TH:i', strtotime((string) $value));
        }
        ?>
        <label>
            <?= e($field['label']) ?>
            <?php if ($type === 'textarea' || $type === 'editor'): ?>
                <textarea name="<?= e($name) ?>" rows="<?= $type === 'editor' ? 12 : 4 ?>" class="<?= $type === 'editor' ? 'richtext' : '' ?>" <?= $locked ? 'readonly' : '' ?>><?= e((string) $value) ?></textarea>
            <?php elseif ($type === 'select'): ?>
                <select name="<?= e($name) ?>" <?= $locked ? 'disabled' : '' ?>>
                    <option value="">Select</option>
                    <?php if (!empty($field['options'])): ?>
                        <?php foreach ($field['options'] as $optionValue => $optionLabel): ?>
                            <option value="<?= e((string) $optionValue) ?>" <?= (string) $value === (string) $optionValue ? 'selected' : '' ?>><?= e($optionLabel) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($options[$name] ?? [] as $option): ?>
                            <option value="<?= (int) $option['id'] ?>" <?= (string) $value === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            <?php elseif ($type === 'checkbox'): ?>
                <input type="checkbox" name="<?= e($name) ?>" value="1" <?= (string) old($name, $record[$name] ?? 0) === '1' || old($name, $record[$name] ?? 0) === 1 ? 'checked' : '' ?>>
            <?php elseif ($type === 'file'): ?>
                <?php if (!empty($record[$name])): ?><img class="thumb" src="<?= e(media_url($record[$name])) ?>" alt=""><?php endif; ?>
                <?php $multiple = $resource === 'gallery-images' && !$record; ?>
                <input type="file" name="<?= e($multiple ? $name . '[]' : $name) ?>" accept="image/jpeg,image/png,image/webp,image/gif" <?= $multiple ? 'multiple' : '' ?>>
            <?php elseif ($type === 'password'): ?>
                <input type="password" name="<?= e($name) ?>" autocomplete="new-password" placeholder="<?= $record ? 'Leave blank to keep the current password' : '' ?>">
            <?php else: ?>
                <input type="<?= e($type === 'slug' || $type === 'number' ? ($type === 'number' ? 'number' : 'text') : ($type === 'email' ? 'email' : ($type === 'datetime' ? 'datetime-local' : 'text'))) ?>" name="<?= e($name) ?>" value="<?= e((string) $value) ?>" <?= $locked ? 'readonly' : '' ?>>
            <?php endif; ?>
            <?php if ($err = field_error($name)): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
        </label>
    <?php endforeach; ?>
    <div class="form-actions">
        <?php if (!$locked): ?><button type="submit"><?= $record ? 'Save changes' : 'Create' ?></button><?php endif; ?>
        <a href="<?= e(url('admin/' . $resource)) ?>">Back</a>
    </div>
</form>
