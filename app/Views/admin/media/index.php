<div class="toolbar">
    <form method="get"><input type="search" name="q" value="<?= e($q) ?>" placeholder="Search files"><button type="submit">Search</button></form>
    <form method="post" action="<?= e(url('admin/media')) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" required>
        <button type="submit">Upload</button>
    </form>
</div>
<div class="media-grid">
    <?php foreach ($items as $item): ?>
        <article>
            <img src="<?= e(media_url($item['path'])) ?>" alt="">
            <p><?= e($item['original_name'] ?: $item['filename']) ?></p>
            <code><?= e($item['path']) ?></code>
            <form method="post" action="<?= e(url('admin/media/' . $item['id'] . '/delete')) ?>" data-confirm="Delete this file?">
                <?= csrf_field() ?><button type="submit">Delete</button>
            </form>
        </article>
    <?php endforeach; ?>
</div>
