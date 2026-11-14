<main class="page-shell">
    <article class="video-detail">
        <p class="eyebrow"><?= e($video['category'] ?: 'Video') ?></p><h1><?= e($video['title']) ?></h1>
        <div class="video-detail-player">
            <?php if (!empty($video['embed_url'])): ?><iframe src="<?= e($video['embed_url']) ?>" title="<?= e($video['title']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php elseif ($video['provider'] === 'self_hosted'): ?><video controls preload="metadata" poster="<?= e($video['thumbnail'] ? media_url($video['thumbnail']) : '') ?>"><source src="<?= e($video['video_url']) ?>"></video>
            <?php else: ?><a href="<?= e($video['video_url']) ?>" target="_blank" rel="noopener">Watch video</a><?php endif; ?>
        </div>
        <p><?= e($video['description'] ?? '') ?></p>
        <?php if ($video['credits'] || $video['video_date'] || $video['location']): ?><dl class="video-detail-meta"><?php if ($video['video_date']): ?><div><dt>Date</dt><dd><?= e(format_date($video['video_date'])) ?></dd></div><?php endif; ?><?php if ($video['location']): ?><div><dt>Location</dt><dd><?= e($video['location']) ?></dd></div><?php endif; ?><?php if ($video['credits']): ?><div><dt>Credits</dt><dd><?= e($video['credits']) ?></dd></div><?php endif; ?></dl><?php endif; ?>
    </article>
    <?php if ($related): ?><section><div class="section-heading"><h2>Related videos</h2></div><div class="video-grid"><?php foreach ($related as $item): ?><article class="video-card"><h3><a href="<?= e(url('videos/' . $item['slug'])) ?>"><?= e($item['title']) ?></a></h3></article><?php endforeach; ?></div></section><?php endif; ?>
</main>
