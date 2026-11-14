<div class="news-admin-header">
    <div><p class="eyebrow">Community voices</p><h1>Testimonials</h1><p class="admin-lead">Review student and alumni stories, approval status, profiles and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/testimonials/create')) ?>"><i class="bi bi-plus-lg"></i> Add testimonial</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Name, role or testimonial"></label>
    <label>Approval <select name="approval"><option value="">All approval states</option><?php foreach (['pending', 'approved', 'rejected'] as $item): ?><option value="<?= $item ?>" <?= $approval === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <label>Publication <select name="status"><option value="">All publication states</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/testimonials')) ?>">Reset</a>
</form>
<section class="testimonial-admin-directory">
    <div class="panel-heading"><h2>Testimonial directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> testimonials · ordered numerically</span></div>
    <?php if ($rows): ?>
    <div class="testimonial-admin-grid">
        <?php foreach ($rows as $row): ?>
            <article class="testimonial-admin-card">
                <div class="testimonial-admin-head">
                    <?php if ($row['profile_image']): ?><img src="<?= e(media_url($row['profile_image'])) ?>" alt="<?= e($row['image_alt'] ?: $row['name']) ?>" loading="lazy" width="72" height="72"><?php else: ?><span class="testimonial-admin-avatar" aria-hidden="true"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></span><?php endif; ?>
                    <div><h3><?= e($row['name']) ?></h3><p><?= e($row['designation'] ?: $row['role']) ?></p><?php if ($row['department_name'] || $row['programme_name']): ?><small><?= e($row['department_name'] ?: $row['programme_name']) ?></small><?php endif; ?></div>
                    <span class="testimonial-admin-order">#<?= (int) $row['sort_order'] ?></span>
                </div>
                <blockquote><?= e(excerpt($row['quote'], 190)) ?></blockquote>
                <div class="testimonial-admin-meta"><span class="testimonial-approval approval-<?= e($row['approval_status']) ?>"><?= e(ucfirst($row['approval_status'])) ?></span><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><?= $row['is_featured'] ? '<span class="testimonial-featured"><i class="bi bi-star-fill"></i> Featured</span>' : '' ?></div>
                <div class="testimonial-admin-actions"><a href="<?= e(url('admin/testimonials/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a>
                    <?php if ($row['approval_status'] !== 'approved'): ?><form method="post" action="<?= e(url('admin/testimonials/' . $row['id'] . '/approval')) ?>"><?= csrf_field() ?><input type="hidden" name="approval_status" value="approved"><button type="submit"><i class="bi bi-check-lg"></i> Approve</button></form><?php endif; ?>
                    <?php if ($row['approval_status'] !== 'rejected'): ?><form method="post" action="<?= e(url('admin/testimonials/' . $row['id'] . '/approval')) ?>"><?= csrf_field() ?><input type="hidden" name="approval_status" value="rejected"><button class="is-danger" type="submit" data-confirm="Reject this testimonial?"><i class="bi bi-x-lg"></i> Reject</button></form><?php endif; ?>
                    <form method="post" action="<?= e(url('admin/testimonials/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this testimonial?"><?= csrf_field() ?><button class="is-danger" type="submit"><i class="bi bi-trash"></i> Delete</button></form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?><div class="testimonial-admin-empty"><i class="bi bi-chat-quote"></i><h3>No testimonials found</h3><p>Try changing the filters or add a new testimonial.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/testimonials', array_filter(['q' => $q, 'approval' => $approval, 'status' => $status])) ?>
