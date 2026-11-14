<div class="news-admin-header">
    <div><p class="eyebrow">People directory</p><h1>Staff Profiles</h1><p class="admin-lead">Manage staff profiles, department assignments, visibility and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/staff/create')) ?>"><i class="bi bi-plus-lg"></i> Add staff member</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Name, staff ID, role or email"></label>
    <label>Department <select name="department_id"><option value="0">All departments</option><?php foreach ($departments as $department): ?><option value="<?= (int) $department['id'] ?>" <?= $departmentId === (int) $department['id'] ? 'selected' : '' ?>><?= e($department['name']) ?></option><?php endforeach; ?></select></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/staff')) ?>">Reset</a>
</form>
<section class="staff-admin-directory">
    <div class="panel-heading"><h2>Staff directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> profiles · ordered numerically</span></div>
    <?php if ($rows): ?><div class="staff-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="staff-admin-card">
                <?php if ($row['image']): ?><img src="<?= e(media_url($row['image'])) ?>" alt="<?= e($row['image_alt'] ?: $row['name']) ?>" loading="lazy" width="300" height="340"><?php else: ?><span class="staff-admin-avatar" aria-hidden="true"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></span><?php endif; ?>
                <div class="staff-admin-body"><span class="staff-admin-order">Order <?= (int) $row['sort_order'] ?></span><h3><?= e($row['name']) ?></h3><p><?= e($row['position']) ?></p><small><?= e($row['department_name'] ?: 'Unassigned department') ?><?= $row['staff_id'] ? ' · ' . e($row['staff_id']) : '' ?></small><div class="staff-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><span><?= e(ucwords(str_replace('_', ' ', $row['employment_status']))) ?></span><?= $row['is_featured'] ? '<span class="staff-featured"><i class="bi bi-star-fill"></i> Featured</span>' : '' ?></div><div class="staff-admin-actions"><a href="<?= e(url('admin/staff/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a><a href="<?= e(url('staff/' . $row['slug'])) ?>" target="_blank" rel="noopener"><i class="bi bi-eye"></i> View</a><form method="post" action="<?= e(url('admin/staff/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this staff profile?"><?= csrf_field() ?><button type="submit"><i class="bi bi-trash"></i> Delete</button></form></div></div>
            </article>
        <?php endforeach; ?>
    </div><?php else: ?><div class="staff-admin-empty"><i class="bi bi-people"></i><h3>No staff profiles found</h3><p>Try changing the filters or add a new staff profile.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/staff', array_filter(['q' => $q, 'department_id' => $departmentId, 'category' => $category, 'status' => $status])) ?>
