<div class="news-admin-header">
    <div><p class="eyebrow">Content management</p><h1>Website Pages</h1><p class="admin-lead">Create and organize public pages, hierarchy, publication status and SEO metadata.</p></div>
    <a class="button" href="<?= e(url('admin/pages/create')) ?>"><i class="bi bi-plus-lg"></i> Add page</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title, slug or page content"></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/pages')) ?>">Reset</a>
</form>
<section class="page-admin-directory">
    <div class="panel-heading"><h2>Page directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> pages · ordered numerically</span></div>
    <?php if ($rows): ?><div class="page-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="page-admin-card"><?php if ($row['image']): ?><img src="<?= e(media_url($row['image'])) ?>" alt="<?= e($row['image_alt'] ?: $row['title']) ?>" loading="lazy" width="640" height="360"><?php else: ?><span class="page-admin-placeholder"><i class="bi bi-file-earmark-richtext"></i></span><?php endif; ?><div class="page-admin-body"><span class="page-admin-order">Order <?= (int) $row['sort_order'] ?></span><span class="page-admin-category"><?= e($row['category'] ?: 'General') ?></span><h3><?= e($row['title']) ?></h3><code>/page/<?= e($row['slug']) ?></code><p><?= e(excerpt($row['excerpt'] ?: strip_tags($row['body']), 100)) ?></p><div class="page-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><?php if ($row['parent_title']): ?><span>Child of <?= e($row['parent_title']) ?></span><?php endif; ?><?= $row['is_featured'] ? '<span class="page-featured"><i class="bi bi-star-fill"></i> Featured</span>' : '' ?></div><div class="page-admin-actions"><a href="<?= e(url('admin/pages/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a><a href="<?= e(url('page/' . $row['slug'])) ?>" target="_blank" rel="noopener"><i class="bi bi-eye"></i> View</a><form method="post" action="<?= e(url('admin/pages/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this page?"><?= csrf_field() ?><button type="submit"><i class="bi bi-trash"></i> Delete</button></form></div></div></article>
        <?php endforeach; ?>
    </div><?php else: ?><div class="page-admin-empty"><i class="bi bi-file-earmark-richtext"></i><h3>No pages found</h3><p>Try changing the filters or add a new page.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/pages', array_filter(['q' => $q, 'category' => $category, 'status' => $status])) ?>
