<?php $title = 'Events'; $lead = 'Open days, exhibitions and student activities.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container filters">
        <a href="<?= e(url('events/calendar')) ?>"><i class="bi bi-calendar3"></i> Calendar</a>
        <a class="<?= $activeState === '' && $activeCategory === '' ? 'is-current' : '' ?>" href="<?= e(url('events')) ?>">Upcoming</a>
        <a class="<?= $activeState === 'ongoing' ? 'is-current' : '' ?>" href="<?= e(url('events?state=ongoing')) ?>">Ongoing</a>
        <a class="<?= $activeState === 'completed' ? 'is-current' : '' ?>" href="<?= e(url('events?state=completed')) ?>">Completed</a>
        <?php foreach ($categories as $category): ?><a class="<?= $activeCategory === $category['slug'] ? 'is-current' : '' ?>" href="<?= e(url('events?category=' . $category['slug'])) ?>"><?= e($category['name']) ?></a><?php endforeach; ?>
    </div>
    <div class="container event-list">
        <?php foreach ($events as $event): ?>
            <article>
                <?php if ($event['image']): ?><img src="<?= e(media_url($event['image'])) ?>" alt="" width="180" height="110" loading="lazy"><?php endif; ?>
                <time datetime="<?= e($event['starts_at']) ?>"><strong><?= e(format_date($event['starts_at'], 'd')) ?></strong><?= e(format_date($event['starts_at'], 'M Y')) ?></time>
                <div>
                    <p class="badge"><?= e($event['category_name'] ?: 'Event') ?> · <?= e($event['venue']) ?></p>
                    <h2><a href="<?= e(url('events/' . $event['slug'])) ?>"><?= e($event['title']) ?></a></h2>
                    <p><?= e($event['excerpt']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
