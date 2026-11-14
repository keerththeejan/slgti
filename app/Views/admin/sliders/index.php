<div class="news-admin-header">
    <div><p class="eyebrow">Homepage presentation</p><h1>Homepage Slides</h1><p class="admin-lead">Manage responsive banner imagery, calls to action, scheduling and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/sliders/create')) ?>"><i class="bi bi-plus-lg"></i> Add slide</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Headline, subtitle or eyebrow"></label>
    <label>Location <select name="location"><option value="">All locations</option><option value="homepage" <?= $location === 'homepage' ? 'selected' : '' ?>>Homepage</option></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['draft', 'published', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/sliders')) ?>">Reset</a>
</form>
<section class="slider-admin-directory">
    <div class="panel-heading"><h2>Slide directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> slides · ordered numerically</span></div>
    <?php if ($rows): ?>
    <div class="slider-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="slider-admin-card">
                <a class="slider-admin-media" href="<?= e(url('/')) ?>" target="_blank" rel="noopener">
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= e(media_url($row['image'])) ?>" alt="<?= e($row['image_alt'] ?: $row['title']) ?>" loading="lazy" width="640" height="280">
                    <?php else: ?>
                        <span class="slider-admin-placeholder"><i class="bi bi-images" aria-hidden="true"></i><span>No image</span></span>
                    <?php endif; ?>
                    <span class="slider-admin-gradient" aria-hidden="true"></span>
                    <span class="slider-admin-copy"><strong><?= e($row['title']) ?></strong><small><?= e($row['subtitle']) ?></small></span>
                    <span class="slider-admin-order">Order <?= (int) $row['sort_order'] ?></span>
                </a>
                <div class="slider-admin-meta">
                    <span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span>
                    <span><?= e(ucwords(str_replace('_', ' ', $row['display_location']))) ?></span>
                </div>
                <div class="slider-admin-schedule"><i class="bi bi-clock" aria-hidden="true"></i> <?= $row['starts_at'] ? e($row['starts_at']) : 'Immediately' ?><?= $row['ends_at'] ? ' · until ' . e($row['ends_at']) : ' · No expiry' ?></div>
                <div class="slider-admin-actions">
                    <a class="slider-action-edit" href="<?= e(url('admin/sliders/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil" aria-hidden="true"></i> Edit</a>
                    <a class="slider-action-preview" href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><i class="bi bi-eye" aria-hidden="true"></i> Preview</a>
                    <form method="post" action="<?= e(url('admin/sliders/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this slide?"><?= csrf_field() ?><button class="slider-action-delete" type="submit"><i class="bi bi-trash" aria-hidden="true"></i> Delete</button></form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="slider-admin-empty"><i class="bi bi-images" aria-hidden="true"></i><h3>No slides found</h3><p>Try changing the filters or create a new slide.</p></div>
    <?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/sliders', array_filter(['q' => $q, 'location' => $location, 'status' => $status])) ?>
