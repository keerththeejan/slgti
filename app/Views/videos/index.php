<main class="page-shell">
    <header class="section-heading"><p class="eyebrow">Watch SLGTI</p><h1>Videos</h1><p>Explore institutional stories, training programmes and campus activities.</p></header>
    <form class="public-filter" method="get">
        <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search videos" aria-label="Search videos">
        <select name="category" aria-label="Filter by category"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= e($item['category']) ?>" <?= $category === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select>
        <button class="button" type="submit">Filter</button>
    </form>
    <div class="video-grid">
        <?php foreach ($videos as $video): ?>
            <?php $thumbnail = $video['thumbnail'] ? media_url($video['thumbnail']) : (($video['provider'] === 'youtube' && $video['video_id']) ? 'https://i.ytimg.com/vi/' . rawurlencode($video['video_id']) . '/hqdefault.jpg' : asset('images/placeholder.svg')); ?>
            <article class="video-card">
                <a class="video-card-media" href="<?= e(url('videos/' . $video['slug'])) ?>"><img src="<?= e($thumbnail) ?>" alt="<?= e($video['title']) ?>" loading="lazy" width="640" height="360"><span class="video-play"><i class="bi bi-play-fill"></i></span></a>
                <div class="video-card-body"><span class="video-provider"><?= e(ucfirst($video['provider'])) ?></span><h2><a href="<?= e(url('videos/' . $video['slug'])) ?>"><?= e($video['title']) ?></a></h2><p><?= e(excerpt($video['description'] ?? '', 130)) ?></p></div>
            </article>
        <?php endforeach; ?>
    </div>
</main>
