<section class="page-hero">
    <div class="container">
        <p class="eyebrow light">SLGTI information centre</p>
        <h1>Institutional Dashboard</h1>
        <p>Find programmes, important notices, upcoming events and links to student services.</p>
    </div>
</section>
<section class="section dashboard-page">
    <div class="container">
        <div class="dashboard-stats">
            <?php foreach ($stats as $stat): ?>
                <article class="dashboard-stat">
                    <i class="bi <?= e($stat['icon']) ?>" aria-hidden="true"></i>
                    <strong><?= e($stat['value']) ?></strong>
                    <span><?= e($stat['label']) ?></span>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="dashboard-grid">
            <section class="panel dashboard-panel">
                <div class="section-head">
                    <div><p class="eyebrow">Stay informed</p><h2>Latest notices</h2></div>
                    <a class="text-link" href="<?= e(url('news')) ?>">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <?php foreach ($notices as $notice): ?>
                    <article class="dashboard-list-item">
                        <div>
                            <span class="news-kicker"><?= e($notice['category_name'] ?: 'Announcement') ?></span>
                            <h3><a href="<?= e(url('news/' . $notice['slug'])) ?>"><?= e($notice['title']) ?></a></h3>
                        </div>
                        <time datetime="<?= e($notice['published_at']) ?>"><?= e(format_date($notice['published_at'])) ?></time>
                    </article>
                <?php endforeach; ?>
                <?php if ($notices === []): ?><p class="empty-state">No notices are currently published.</p><?php endif; ?>
            </section>
            <section class="panel dashboard-panel">
                <div class="section-head">
                    <div><p class="eyebrow">Plan ahead</p><h2>Upcoming events</h2></div>
                    <a class="text-link" href="<?= e(url('events')) ?>">View all <i class="bi bi-arrow-right"></i></a>
                </div>
                <?php foreach ($events as $event): ?>
                    <article class="dashboard-list-item">
                        <div>
                            <h3><a href="<?= e(url('events/' . $event['slug'])) ?>"><?= e($event['title']) ?></a></h3>
                            <span class="text-muted"><?= e($event['venue'] ?: 'SLGTI campus') ?></span>
                        </div>
                        <time datetime="<?= e($event['starts_at']) ?>"><?= e(format_date($event['starts_at'], 'd M')) ?></time>
                    </article>
                <?php endforeach; ?>
                <?php if ($events === []): ?><p class="empty-state">No upcoming events are currently published.</p><?php endif; ?>
            </section>
        </div>
        <section class="panel dashboard-panel">
            <div class="section-head">
                <div><p class="eyebrow">Study at SLGTI</p><h2>Featured programmes</h2></div>
                <a class="text-link" href="<?= e(url('programmes')) ?>">Explore all <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="dashboard-programmes">
                <?php foreach ($programmes as $programme): ?>
                    <a class="dashboard-programme" href="<?= e(url('programmes/' . $programme['slug'])) ?>">
                        <span><?= e($programme['level_code']) ?></span>
                        <strong><?= e($programme['title']) ?></strong>
                        <small><?= e($programme['department_name']) ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>
