<div class="news-admin-header">
    <div><p class="eyebrow">Campus resources</p><h1>Facilities</h1><p class="admin-lead">Manage workshops, laboratories, equipment, availability and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/facilities/create')) ?>"><i class="bi bi-plus-lg"></i> Add facility</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Name, category, location or equipment"></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Availability <select name="availability"><option value="">All availability</option><?php foreach (['available', 'limited', 'unavailable'] as $item): ?><option value="<?= $item ?>" <?= $availability === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/facilities')) ?>">Reset</a>
</form>
<section class="facility-admin-directory">
    <div class="panel-heading"><h2>Facility directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> facilities · ordered numerically</span></div>
    <?php if ($rows): ?><div class="facility-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="facility-admin-card"><?php if ($row['image']): ?><img src="<?= e(media_url($row['image'])) ?>" alt="<?= e($row['image_alt'] ?: $row['title']) ?>" loading="lazy" width="640" height="420"><?php else: ?><span class="facility-admin-placeholder"><i class="bi bi-building"></i></span><?php endif; ?><div class="facility-admin-body"><span class="facility-admin-order">Order <?= (int) $row['sort_order'] ?></span><span class="facility-admin-category"><?= e($row['category'] ?: 'Campus facility') ?></span><h3><?= e($row['title']) ?></h3><p><?= e(excerpt($row['summary'], 105)) ?></p><small><?= e($row['location'] ?: 'Location not set') ?><?= $row['capacity'] ? ' · ' . e($row['capacity']) : '' ?></small><div class="facility-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><span class="availability-<?= e($row['availability_status']) ?>"><?= e(ucwords(str_replace('_', ' ', $row['availability_status']))) ?></span><?= $row['is_featured'] ? '<span class="facility-featured"><i class="bi bi-star-fill"></i> Featured</span>' : '' ?></div><div class="facility-admin-actions"><a href="<?= e(url('admin/facilities/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a><a href="<?= e(url('facilities/' . $row['slug'])) ?>" target="_blank" rel="noopener"><i class="bi bi-eye"></i> View</a><form method="post" action="<?= e(url('admin/facilities/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this facility?"><?= csrf_field() ?><button type="submit"><i class="bi bi-trash"></i> Delete</button></form></div></div></article>
        <?php endforeach; ?>
    </div><?php else: ?><div class="facility-admin-empty"><i class="bi bi-building"></i><h3>No facilities found</h3><p>Try changing the filters or add a new facility.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/facilities', array_filter(['q' => $q, 'category' => $category, 'availability' => $availability, 'status' => $status])) ?>
