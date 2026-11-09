<form method="post" action="<?= e(url('admin/backups')) ?>" class="toolbar">
    <?= csrf_field() ?>
    <p>Create a MySQL dump of the webslgti database. Download files stay on this server until you delete them.</p>
    <button type="submit">Create backup</button>
</form>
<div class="panel">
    <div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>File</th><th>Size</th><th>Created</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?= e($file['name']) ?></td>
                <td><?= number_format($file['size'] / 1024, 1) ?> KB</td>
                <td><?= e(date('d M Y H:i', $file['time'])) ?></td>
                <td class="actions">
                    <a href="<?= e(url('admin/backups/' . $file['name'])) ?>">Download</a>
                    <form method="post" action="<?= e(url('admin/backups/' . $file['name'] . '/delete')) ?>" data-confirm="Delete this backup?">
                        <?= csrf_field() ?><button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$files): ?><tr><td colspan="4">No backups yet.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
