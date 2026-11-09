<div class="panel">
    <div class="table-wrap">
    <table class="data-table">
        <thead><tr><th>When</th><th>User</th><th>Action</th><th>Detail</th><th>IP</th></tr></thead>
        <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= e(format_date($log['created_at'], 'd M Y H:i')) ?></td>
                <td><?= e($log['user_name'] ?: 'System') ?></td>
                <td><?= e($log['action']) ?></td>
                <td><?= e($log['description']) ?></td>
                <td><?= e($log['ip_address']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?= render_pagination($pager, '/admin/logs') ?>
