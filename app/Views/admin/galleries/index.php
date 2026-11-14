<div class="news-admin-header">
    <div><p class="eyebrow">Media library</p><h1>Gallery Albums</h1><p class="admin-lead">Organize published albums, cover images, accessibility text and display order.</p></div>
    <a class="button" href="<?= e(url('admin/galleries/create')) ?>"><i class="bi bi-plus-lg"></i> Add album</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Album title, slug or description"></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Event <select name="event_id"><option value="0">All events</option><?php foreach ($events as $event): ?><option value="<?= (int) $event['id'] ?>" <?= $eventId === (int) $event['id'] ? 'selected' : '' ?>><?= e($event['title']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/galleries')) ?>">Reset</a>
</form>
<section class="gallery-admin-directory">
    <div class="panel-heading"><h2>Album directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> albums · ordered numerically</span></div>
    <?php if ($rows): ?>
    <div class="gallery-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="gallery-admin-card">
                <a class="gallery-admin-card-media" href="<?= e(url('gallery/' . $row['slug'])) ?>" target="_blank" rel="noopener">
                    <?php if (!empty($row['display_cover'])): ?>
                        <img src="<?= e(media_url($row['display_cover'])) ?>" alt="<?= e($row['title']) ?>" loading="lazy" width="640" height="400">
                    <?php else: ?>
                        <span class="gallery-admin-placeholder"><i class="bi bi-images" aria-hidden="true"></i><span>No images</span></span>
                    <?php endif; ?>
                    <span class="gallery-admin-card-gradient" aria-hidden="true"></span>
                    <strong class="gallery-admin-card-title"><?= e($row['title']) ?></strong>
                    <span class="gallery-admin-card-count"><i class="bi bi-images" aria-hidden="true"></i> <?= (int) $row['image_count'] ?></span>
                </a>
                <div class="gallery-admin-card-meta">
                    <span class="gallery-admin-order">Order <?= (int) $row['sort_order'] ?></span>
                    <span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span>
                </div>
                <div class="gallery-admin-card-actions">
                    <a class="gallery-action-edit" href="<?= e(url('admin/galleries/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil" aria-hidden="true"></i> Edit</a>
                    <a class="gallery-action-add" href="<?= e(url('admin/gallery-images/create')) ?>"><i class="bi bi-cloud-arrow-up" aria-hidden="true"></i> Add images</a>
                    <form method="post" action="<?= e(url('admin/galleries/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this empty album? Albums containing images must be emptied first.">
                        <?= csrf_field() ?><button class="gallery-action-delete" type="submit"><i class="bi bi-trash" aria-hidden="true"></i> Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="gallery-admin-empty"><i class="bi bi-images" aria-hidden="true"></i><h3>No albums found</h3><p>Try changing the filters or create a new album.</p></div>
    <?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/galleries', array_filter(['q' => $q, 'category' => $category, 'event_id' => $eventId, 'status' => $status])) ?>
