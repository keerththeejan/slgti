<div class="news-admin-header">
    <div><p class="eyebrow">Academic structure</p><h1>Departments Management</h1><p class="admin-lead">Maintain departments, programme relationships and public ordering.</p></div>
    <a class="button" href="<?= e(url('admin/departments/create')) ?>"><i class="bi bi-plus-lg"></i> Add department</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Name, slug, summary or head"></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $option): ?><option value="<?= $option ?>" <?= $status === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit"><i class="bi bi-funnel"></i> Filter</button><a class="button button-secondary" href="<?= e(url('admin/departments')) ?>">Reset</a>
</form>
<section class="panel">
    <div class="panel-heading"><h2>Department directory</h2><span class="panel-note"><?= count($departments) ?> departments<?= ($q !== '' || $status !== '') ? ' · clear filters to reorder' : ' · drag to reorder' ?></span></div>
    <div id="departmentFeedback" class="menu-feedback" role="status" hidden></div>
    <div id="departmentList" class="department-admin-grid">
        <?php foreach ($departments as $department): ?>
            <article class="department-admin-card" data-id="<?= (int) $department['id'] ?>">
                <div class="department-admin-image"><img src="<?= e(media_url($department['image'])) ?>" alt="" width="360" height="180" loading="lazy"><button class="drag-handle" type="button" aria-label="Drag to reorder"><i class="bi bi-grip-vertical"></i></button></div>
                <div class="department-admin-body"><div class="department-admin-title"><div><h3><?= e($department['name']) ?></h3><small><?= e($department['slug']) ?></small></div><span class="news-status status-<?= e($department['status']) ?>"><?= e(ucfirst($department['status'])) ?></span></div>
                    <p><?= e($department['summary']) ?></p><div class="department-admin-meta"><span><i class="bi bi-mortarboard"></i> <?= (int) $department['programme_count'] ?> programmes</span><span><i class="bi bi-person"></i> <?= e($department['head_name'] ?: 'Head not listed') ?></span></div>
                    <div class="actions"><a href="<?= e(url('admin/departments/' . $department['id'] . '/edit')) ?>">Edit</a><a href="<?= e(url('departments/' . ($department['seo_slug'] ?: $department['slug']))) ?>" target="_blank" rel="noopener">View</a><form method="post" action="<?= e(url('admin/departments/' . $department['id'] . '/delete')) ?>" data-confirm="<?= (int) $department['programme_count'] > 0 ? 'This department has programmes and cannot be deleted safely. Archive it instead.' : 'Delete this department?' ?>"><?= csrf_field() ?><button type="submit">Delete</button></form></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!$departments): ?><div class="empty-state">No departments match the selected filters.</div><?php endif; ?>
</section>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('departmentList');
    const feedback = document.getElementById('departmentFeedback');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    if (!list) return;
    if (<?= json_encode($q === '' && $status === '') ?>) new Sortable(list, {animation: 150, handle: '.drag-handle', onEnd: async () => {
        feedback.hidden = false; feedback.className = 'menu-feedback is-loading'; feedback.textContent = 'Saving department order...';
        try {
            const response = await fetch(<?= json_encode(url('admin/departments/reorder')) ?>, {method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-Token': token, 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify({departments: [...list.children].map((card) => Number(card.dataset.id))})});
            const result = await response.json(); if (!response.ok || !result.ok) throw new Error(result.message || 'Could not save order.');
            feedback.className = 'menu-feedback is-success'; feedback.textContent = result.message;
        } catch (error) { feedback.className = 'menu-feedback is-error'; feedback.textContent = error.message; }
    }});
});
</script>
