<?php $title = 'News & events'; $lead = 'Announcements, achievements and campus stories.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container filters">
        <a class="<?= $activeCategory === '' ? 'is-current' : '' ?>" href="<?= e(url('news')) ?>">All</a>
        <?php foreach ($categories as $category): ?>
            <a class="<?= $activeCategory === $category['slug'] ? 'is-current' : '' ?>" href="<?= e(url('news?category=' . $category['slug'])) ?>"><?= e($category['name']) ?></a>
        <?php endforeach; ?>
        <a href="<?= e(url('events')) ?>">Events calendar</a>
    </div>
    <div class="container news-grid">
        <?php foreach ($items as $item): ?>
            <article class="news-card">
                <a href="<?= e(url('news/' . $item['slug'])) ?>"><img src="<?= e(media_url($item['image'])) ?>" alt="" loading="lazy" width="800" height="520"></a>
                <div>
                    <p class="meta"><span><?= e($item['category_name'] ?: 'News') ?></span> <?= e(format_date($item['published_at'])) ?></p>
                    <h2><a href="<?= e(url('news/' . $item['slug'])) ?>"><?= e($item['title']) ?></a></h2>
                    <p><?= e($item['excerpt']) ?></p>
                    <a class="text-link" href="<?= e(url('news/' . $item['slug'])) ?>">Read more</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="container"><?= render_pagination($pager, '/news', array_filter(['category' => $activeCategory])) ?></div>
</section>
