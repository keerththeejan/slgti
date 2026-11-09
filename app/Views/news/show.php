<?php $title = $item['title']; $lead = format_date($item['published_at']) . ' · ' . ($item['category_name'] ?: 'News'); require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose narrow">
        <img class="cover" src="<?= e(media_url($item['image'])) ?>" alt="">
        <?php if (!empty($item['tags'])): ?><p><?= e($item['tags']) ?></p><?php endif; ?>
        <?= $item['body'] ?>
        <p><a class="text-link" href="<?= e(url('news')) ?>">Back to news</a></p>
        <?php if ($related): ?>
            <h2>More stories</h2>
            <ul class="link-list">
                <?php foreach ($related as $story): ?>
                    <li><a href="<?= e(url('news/' . $story['slug'])) ?>"><?= e($story['title']) ?><small><?= e(format_date($story['published_at'])) ?></small></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
