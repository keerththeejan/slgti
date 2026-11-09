<?php $title = 'Events'; $lead = 'Open days, exhibitions and student activities.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container event-list">
        <?php foreach ($events as $event): ?>
            <article>
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
