<div class="news-admin-header">
    <div><p class="eyebrow">Media library</p><h1>Gallery Images</h1><p class="admin-lead">Organize album photography, accessibility text, credits and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/gallery-images/create')) ?>"><i class="bi bi-cloud-arrow-up"></i> Upload images</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Caption, album, alt text or credits"></label>
    <label>Album <select name="gallery_id"><option value="0">All albums</option><?php foreach ($galleries as $gallery): ?><option value="<?= (int) $gallery['id'] ?>" <?= $galleryId === (int) $gallery['id'] ? 'selected' : '' ?>><?= e($gallery['title']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/gallery-images')) ?>">Reset</a>
</form>
<section class="gallery-images-admin-directory">
    <div class="panel-heading"><h2>Image directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> images · ordered numerically</span></div>
    <?php if ($rows): ?>
    <div class="gallery-images-admin-grid">
        <?php foreach ($rows as $row): ?>
            <?php $size = (int) $row['file_size']; $sizeLabel = $size >= 1048576 ? number_format($size / 1048576, 1) . ' MB' : number_format($size / 1024, 0) . ' KB'; ?>
            <article class="gallery-image-admin-card">
                <button class="gallery-image-admin-media" type="button" data-gallery-preview data-image="<?= e(media_url($row['image'])) ?>" data-alt="<?= e($row['alt_text'] ?: $row['title']) ?>" data-caption="<?= e($row['title']) ?>">
                    <img src="<?= e(media_url($row['image'])) ?>" alt="<?= e($row['alt_text'] ?: $row['title']) ?>" loading="lazy" width="360" height="240">
                    <span class="gallery-image-admin-overlay"><i class="bi bi-arrows-fullscreen" aria-hidden="true"></i> Preview</span>
                    <span class="gallery-image-admin-order">Order <?= (int) $row['sort_order'] ?></span>
                </button>
                <div class="gallery-image-admin-body">
                    <h3 title="<?= e($row['title']) ?>"><?= e($row['title']) ?></h3>
                    <a class="gallery-image-admin-album" href="<?= e(url('admin/galleries')) ?>"><?= e($row['album_title']) ?></a>
                    <div class="gallery-image-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><span><?= $row['width'] && $row['height'] ? (int) $row['width'] . '×' . (int) $row['height'] : 'Unknown size' ?></span></div>
                    <div class="gallery-image-admin-meta"><span><?= e($sizeLabel) ?></span><span><?= e(format_date($row['created_at'], 'd M Y')) ?></span></div>
                    <div class="gallery-image-admin-actions">
                        <a href="<?= e(url('admin/gallery-images/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil" aria-hidden="true"></i> Edit</a>
                        <button type="button" data-gallery-preview data-image="<?= e(media_url($row['image'])) ?>" data-alt="<?= e($row['alt_text'] ?: $row['title']) ?>" data-caption="<?= e($row['title']) ?>"><i class="bi bi-eye" aria-hidden="true"></i> View</button>
                        <form method="post" action="<?= e(url('admin/gallery-images/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this gallery image?"><button type="submit"><i class="bi bi-trash" aria-hidden="true"></i> Delete</button><?= csrf_field() ?></form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="gallery-image-admin-empty"><i class="bi bi-images" aria-hidden="true"></i><h3>No gallery images found</h3><p>Try changing the filters or upload images to an album.</p></div>
    <?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/gallery-images', array_filter(['q' => $q, 'gallery_id' => $galleryId, 'status' => $status])) ?>
<div class="gallery-image-preview-modal" data-gallery-modal hidden>
    <div class="gallery-image-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="gallery-preview-caption">
        <button type="button" class="gallery-image-preview-close" data-gallery-close aria-label="Close preview"><i class="bi bi-x-lg"></i></button>
        <img data-gallery-modal-image src="" alt="">
        <p id="gallery-preview-caption" data-gallery-modal-caption></p>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-gallery-modal]');
    const image = modal?.querySelector('[data-gallery-modal-image]');
    const caption = modal?.querySelector('[data-gallery-modal-caption]');
    const close = () => { if (modal) modal.hidden = true; };
    document.querySelectorAll('[data-gallery-preview]').forEach((trigger) => trigger.addEventListener('click', () => {
        image.src = trigger.dataset.image || '';
        image.alt = trigger.dataset.alt || '';
        caption.textContent = trigger.dataset.caption || '';
        modal.hidden = false;
    }));
    modal?.querySelector('[data-gallery-close]')?.addEventListener('click', close);
    modal?.addEventListener('click', (event) => { if (event.target === modal) close(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });
});
</script>
