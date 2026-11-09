<?php $title = $event['title']; $lead = format_date($event['starts_at'], 'd M Y, h:i A') . ' · ' . $event['venue']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose narrow">
        <?php if ($event['image']): ?><img class="cover" src="<?= e(media_url($event['image'])) ?>" alt=""><?php endif; ?>
        <?= $event['body'] ?>
        <?php if (!empty($event['registration_url'])): ?>
            <p><a class="btn btn-red" href="<?= e(nav_href($event['registration_url'])) ?>">Register</a></p>
        <?php endif; ?>
        <p><a class="text-link" href="<?= e(url('events')) ?>">All events</a></p>
    </div>
</section>
