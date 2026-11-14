<div class="news-admin-header">
    <div><p class="eyebrow">Content management</p><h1>Frequently Asked Questions</h1><p class="admin-lead">Organize published answers with accessible categories and numeric ordering.</p></div>
    <a class="button" href="<?= e(url('admin/faqs/create')) ?>"><i class="bi bi-plus-lg"></i> Add FAQ</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Question, answer or category"></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/faqs')) ?>">Reset</a>
</form>
<section class="faq-admin-directory">
    <div class="panel-heading"><h2>FAQ directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> FAQs · ordered numerically</span></div>
    <?php if ($rows): ?><div class="faq-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="faq-admin-card"><div class="faq-admin-top"><span class="faq-admin-order">Order <?= (int) $row['sort_order'] ?></span><span class="faq-admin-category"><?= e($row['category'] ?: 'General') ?></span></div><h3><?= e($row['question']) ?></h3><div class="faq-admin-answer"><?= excerpt($row['answer'], 150) ?></div><div class="faq-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><?php if ($row['is_featured']): ?><span class="faq-featured"><i class="bi bi-star-fill"></i> Featured</span><?php endif; ?><time datetime="<?= e($row['updated_at'] ?? '') ?>">Updated <?= e(date('M j, Y', strtotime($row['updated_at'] ?? 'now'))) ?></time></div><div class="faq-admin-actions"><a href="<?= e(url('admin/faqs/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a><a href="<?= e(url('faq#faq-' . $row['id'])) ?>" target="_blank" rel="noopener"><i class="bi bi-eye"></i> Preview</a><form method="post" action="<?= e(url('admin/faqs/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this FAQ?"><?= csrf_field() ?><button type="submit"><i class="bi bi-trash"></i> Delete</button></form></div></article>
        <?php endforeach; ?>
    </div><?php else: ?><div class="faq-admin-empty"><i class="bi bi-question-circle"></i><h3>No FAQs found</h3><p>Try changing the filters or add a new FAQ.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/faqs', array_filter(['q' => $q, 'category' => $category, 'status' => $status])) ?>
