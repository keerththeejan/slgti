<div class="news-admin-header">
    <div><p class="eyebrow">Media library</p><h1>Videos</h1><p class="admin-lead">Manage provider-safe embeds, thumbnails, publication and numeric display order.</p></div>
    <a class="button" href="<?= e(url('admin/videos/create')) ?>"><i class="bi bi-plus-lg"></i> Add video</a>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title, description or URL"></label>
    <label>Category <select name="category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach (['published', 'draft', 'archived'] as $item): ?><option value="<?= $item ?>" <?= $status === $item ? 'selected' : '' ?>><?= ucfirst($item) ?></option><?php endforeach; ?></select></label>
    <button class="button" type="submit">Filter</button><a class="button button-secondary" href="<?= e(url('admin/videos')) ?>">Reset</a>
</form>
<section class="video-admin-directory">
    <div class="panel-heading"><h2>Video directory</h2><span class="panel-note"><?= (int) $pager['total'] ?> videos · ordered numerically</span></div>
    <?php if ($rows): ?>
    <div class="video-admin-grid">
        <?php foreach ($rows as $row): ?>
            <?php $thumbnail = $row['thumbnail'] ? media_url($row['thumbnail']) : (($row['provider'] === 'youtube' && $row['video_id']) ? 'https://i.ytimg.com/vi/' . rawurlencode($row['video_id']) . '/hqdefault.jpg' : asset('images/placeholder.svg')); ?>
            <article class="video-admin-card">
                <button class="video-admin-media" type="button" data-video-preview data-embed="<?= e($row['embed_url'] ?? '') ?>" data-url="<?= e($row['video_url']) ?>" data-provider="<?= e($row['provider']) ?>" data-title="<?= e($row['title']) ?>">
                    <img src="<?= e($thumbnail) ?>" alt="<?= e($row['title']) ?>" loading="lazy" width="640" height="360">
                    <span class="video-admin-play"><i class="bi bi-play-fill"></i></span><span class="video-admin-order">Order <?= (int) $row['sort_order'] ?></span>
                </button>
                <div class="video-admin-body"><div class="video-admin-meta"><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span><span><?= e(ucfirst($row['provider'])) ?></span></div><h3 title="<?= e($row['title']) ?>"><?= e($row['title']) ?></h3><p><?= e(excerpt($row['description'] ?? '', 90)) ?></p><small><?= e($row['category'] ?: 'Uncategorized') ?><?= $row['duration'] ? ' · ' . e($row['duration']) : '' ?></small>
                    <div class="video-admin-actions"><a href="<?= e(url('admin/videos/' . $row['id'] . '/edit')) ?>"><i class="bi bi-pencil"></i> Edit</a><button type="button" data-video-preview data-embed="<?= e($row['embed_url'] ?? '') ?>" data-url="<?= e($row['video_url']) ?>" data-provider="<?= e($row['provider']) ?>" data-title="<?= e($row['title']) ?>"><i class="bi bi-eye"></i> Preview</button><form method="post" action="<?= e(url('admin/videos/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this video?"><button type="submit"><i class="bi bi-trash"></i> Delete</button><?= csrf_field() ?></form></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php else: ?><div class="video-admin-empty"><i class="bi bi-camera-video"></i><h3>No videos found</h3><p>Try changing the filters or add a new video.</p></div><?php endif; ?>
</section>
<?= render_pagination($pager, '/admin/videos', array_filter(['q' => $q, 'category' => $category, 'status' => $status])) ?>
<div class="video-preview-modal" data-video-modal hidden><div class="video-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="video-preview-title"><button type="button" data-video-close aria-label="Close preview"><i class="bi bi-x-lg"></i></button><h2 id="video-preview-title"></h2><div data-video-frame></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-video-modal]'), frame = modal?.querySelector('[data-video-frame]'), title = modal?.querySelector('h2');
    const close = () => { if (modal) { modal.hidden = true; frame.replaceChildren(); } };
    document.querySelectorAll('[data-video-preview]').forEach((trigger) => trigger.addEventListener('click', () => {
        title.textContent = trigger.dataset.title || 'Video preview';
        frame.replaceChildren();
        if (trigger.dataset.embed) {
            const iframe = document.createElement('iframe'); iframe.src = trigger.dataset.embed; iframe.title = title.textContent; iframe.allow = 'autoplay; fullscreen; picture-in-picture'; iframe.allowFullscreen = true; frame.append(iframe);
        } else {
            const link = document.createElement('a'); link.href = trigger.dataset.url; link.target = '_blank'; link.rel = 'noopener'; link.textContent = 'Open video'; frame.append(link);
        }
        modal.hidden = false;
    }));
    modal?.querySelector('[data-video-close]')?.addEventListener('click', close);
    modal?.addEventListener('click', (event) => { if (event.target === modal) close(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') close(); });
});
</script>
