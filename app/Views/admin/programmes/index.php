<div class="news-admin-header">
    <div><p class="eyebrow">Academic catalogue</p><h1>Programmes Management</h1><p class="admin-lead">Manage published pathways, department assignments, NVQ levels and programme SEO.</p></div>
    <a class="button" href="<?= e(url('admin/programmes/create')) ?>"><i class="bi bi-plus-lg"></i> Add programme</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title, department, qualification"></label>
    <label>Department <select name="department_id"><option value="0">All departments</option><?php foreach ($departments as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $department === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select></label>
    <label>NVQ level <select name="level_id"><option value="0">All levels</option><?php foreach ($levels as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $level === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['code'] . ' · ' . $item['name']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit"><i class="bi bi-funnel"></i> Filter</button><a class="button button-secondary" href="<?= e(url('admin/programmes')) ?>">Reset</a>
</form>
<section class="panel">
    <div class="panel-heading"><h2>Programme catalogue</h2><span class="panel-note"><?= (int) $pager['total'] ?> programmes<?= $department && (int) $pager['pages'] === 1 ? ' · drag to reorder' : ' · filter one department on a single page to reorder' ?></span></div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Programme</th><th>Department</th><th>Level</th><th>Duration</th><th>Status</th><th></th></tr></thead>
            <tbody id="programmeList">
            <?php foreach ($rows as $row): ?>
                <tr data-id="<?= (int) $row['id'] ?>" data-department="<?= (int) $row['department_id'] ?>">
                    <td><span class="drag-handle" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></span><strong><?= e($row['title']) ?></strong><small><?= e($row['slug']) ?></small></td>
                    <td><?= e($row['department_name']) ?></td><td><?= e($row['level_code'] . ' · ' . $row['qualification']) ?></td><td><?= e($row['duration']) ?></td>
                    <td><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span></td>
                    <td class="actions"><a href="<?= e(url('admin/programmes/' . $row['id'] . '/edit')) ?>">Edit</a><a href="<?= e(url('programmes/' . ($row['seo_slug'] ?: $row['slug']))) ?>" target="_blank" rel="noopener">View</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="6">No programmes match the selected filters.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= render_pagination($pager, '/admin/programmes', array_filter(['q' => $q, 'department_id' => $department, 'level_id' => $level, 'status' => $status])) ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('programmeList');
    const department = <?= (int) $department ?>;
    if (!list || !department || <?= (int) $pager['pages'] !== 1 ? 'true' : 'false' ?> || list.querySelector('[data-department]') === null || [...list.children].some((row) => Number(row.dataset.department) !== department)) return;
    new Sortable(list, {animation: 150, handle: '.drag-handle', onEnd: async () => {
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const response = await fetch(<?= json_encode(url('admin/programmes/reorder')) ?>, {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-Token': token, 'Accept': 'application/json'}, body: JSON.stringify({department_id: department, programmes: [...list.children].map((row) => Number(row.dataset.id))})});
        const result = await response.json();
        if (!response.ok || !result.ok) window.alert(result.message || 'Could not save programme order.');
    }});
});
</script>
