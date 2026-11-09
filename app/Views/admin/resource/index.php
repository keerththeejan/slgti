<div class="toolbar">
    <form method="get">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search <?= e(strtolower($config['title'])) ?>">
        <?php if (!empty($config['status_filter'])): ?>
            <select name="status">
                <option value="">All statuses</option>
                <?php foreach ($config['status_filter'] as $option): ?>
                    <option value="<?= e($option) ?>" <?= ($status ?? '') === $option ? 'selected' : '' ?>><?= e(ucfirst($option)) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button type="submit">Search</button>
    </form>
    <?php if ($resource === 'applications' && can('applications.manage')): ?>
        <a class="button" href="<?= e(url('admin/applications/export' . (($status ?? '') !== '' ? '?status=' . urlencode($status) : ''))) ?>">Export CSV</a>
    <?php endif; ?>
    <?php if (($config['creatable'] ?? true) && can($config['permission'])): ?>
        <a class="button" href="<?= e(url('admin/' . $resource . '/create')) ?>">Add new</a>
    <?php endif; ?>
</div>
<div class="panel">
    <div class="table-wrap">
    <table class="data-table">
        <thead>
        <tr>
            <?php foreach ($config['list'] as $label): ?><th><?= e($label) ?></th><?php endforeach; ?>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($config['list'] as $column => $label): ?>
                    <td><?= e((string) ($row[$column] ?? '')) ?></td>
                <?php endforeach; ?>
                <td class="actions">
                    <a href="<?= e(url('admin/' . $resource . '/' . $row['id'] . '/edit')) ?>">Open</a>
                    <?php if (can($config['permission']) && ($config['creatable'] ?? true)): ?>
                        <form method="post" action="<?= e(url('admin/' . $resource . '/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this record?">
                            <?= csrf_field() ?><button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="<?= count($config['list']) + 1 ?>">No records yet.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<?= render_pagination($pager, '/admin/' . $resource, array_filter(['q' => $q])) ?>
