<section class="home-lower">
    <div class="container home-lower-grid">
        <div>
            <p class="eyebrow">Our Departments</p>
            <h2>Explore Our Departments</h2>
            <div class="home-depts">
                <?php foreach ($departments as $department): ?>
                    <a class="home-dept" href="<?= e(url('departments/' . $department['slug'])) ?>">
                        <img src="<?= e(media_url($department['image'])) ?>" alt="" width="320" height="180" loading="lazy">
                        <span class="home-dept-icon"><i class="bi <?= e($department['icon'] ?: 'bi-gear') ?>"></i></span>
                        <strong><?= e($department['name']) ?></strong>
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <div class="home-news-head">
                <div>
                    <p class="eyebrow">Latest News &amp; Events</p>
                </div>
                <a class="text-link" href="<?= e(url('news')) ?>">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="home-news">
                <?php foreach ($news as $item): ?>
                    <article>
                        <a href="<?= e(url('news/' . $item['slug'])) ?>">
                            <img src="<?= e(media_url($item['image'])) ?>" alt="" width="480" height="280" loading="lazy">
                            <span class="news-kicker"><?= e($item['category_name'] ?: 'News') ?></span>
                            <time datetime="<?= e($item['published_at']) ?>"><?= e(format_date($item['published_at'])) ?></time>
                            <h3><?= e($item['title']) ?></h3>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
