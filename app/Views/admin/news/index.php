<div class="news-admin-header">
    <div><p class="eyebrow">Content publishing</p><h1>News Management</h1><p class="admin-lead">Create, schedule and publish institutional stories.</p></div>
    <?php if (can($config['permission'])): ?><a class="button" href="<?= e(url('admin/news/create')) ?>"><i class="bi bi-plus-lg"></i> Add news</a><?php endif; ?>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title, excerpt or content"></label>
    <label>Status
        <select name="status"><option value="">All statuses</option><?php foreach ($config['status_filter'] as $option): ?><option value="<?= e($option) ?>" <?= $status === $option ? 'selected' : '' ?>><?= e(ucfirst($option)) ?></option><?php endforeach; ?></select>
    </label>
    <label>Category
        <select name="category_id"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $category === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Author
        <select name="author_id"><option value="">All authors</option><?php foreach ($authors as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $author === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select>
    </label>
    <label>Published from <input type="date" name="from" value="<?= e($from) ?>"></label>
    <label>Published to <input type="date" name="to" value="<?= e($to) ?>"></label>
    <label class="news-checkbox"><input type="checkbox" name="featured" value="1" <?= $featured ? 'checked' : '' ?>> Featured only</label>
    <button class="button" type="submit"><i class="bi bi-funnel"></i> Filter</button>
    <a class="button button-secondary" href="<?= e(url('admin/news')) ?>">Reset</a>
</form>
<section class="panel">
    <div class="panel-heading"><h2>All news</h2><span class="panel-note"><?= (int) $pager['total'] ?> records</span></div>
    <div class="table-wrap">
        <table class="data-table news-admin-table">
            <thead><tr><th>Story</th><th>Category</th><th>Author</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="news-story-cell">
                        <img src="<?= e(media_url($row['image'])) ?>" alt="" width="72" height="48" loading="lazy">
                        <div><strong><?= e($row['title']) ?></strong><?php if ((int) $row['is_featured'] === 1): ?><span class="featured-badge"><i class="bi bi-star-fill"></i> Featured</span><?php endif; ?><small><?= e($row['excerpt']) ?></small></div>
                    </td>
                    <td><?= e($row['category_name'] ?: 'Uncategorised') ?></td>
                    <td><?= e($row['author_name'] ?: 'System') ?></td>
                    <td><?= e(format_date($row['published_at'], 'd M Y')) ?></td>
                    <td><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span></td>
                    <td class="actions">
                        <a href="<?= e(url('admin/news/' . $row['id'] . '/edit')) ?>">Edit</a>
                        <?php if ($row['status'] === 'published'): ?><a href="<?= e(url('news/' . $row['slug'])) ?>" target="_blank" rel="noopener">View</a><?php endif; ?>
                        <?php if (can($config['permission'])): ?><form method="post" action="<?= e(url('admin/news/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this news story?"><?= csrf_field() ?><button type="submit">Delete</button></form><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="6"><div class="empty-state">No news matches the selected filters.</div></td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?= render_pagination($pager, '/admin/news', array_filter(['q' => $q, 'status' => $status, 'category_id' => $category, 'author_id' => $author, 'from' => $from, 'to' => $to, 'featured' => $featured ? 1 : 0])) ?>
