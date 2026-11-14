<?php $title = $event['title']; $lead = format_date($event['starts_at'], 'd M Y, h:i A') . ' · ' . $event['venue']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose narrow">
        <?php if ($event['image']): ?><img class="cover" src="<?= e(media_url($event['image'])) ?>" alt=""><?php endif; ?>
        <?= $event['body'] ?>
        <?php if (!empty($event['registration_url'])): ?>
            <p><a class="btn btn-red" href="<?= e(nav_href($event['registration_url'])) ?>">Register</a></p>
        <?php endif; ?>
        <?php if (!empty($galleries)): ?><h2>Event gallery</h2><div class="video-grid"><?php foreach ($galleries as $gallery): ?><article><h3><a href="<?= e(url('gallery/' . $gallery['slug'])) ?>"><?= e($gallery['title']) ?></a></h3><p><?= (int) $gallery['image_count'] ?> photographs</p></article><?php endforeach; ?></div><?php endif; ?>
        <p><a class="text-link" href="<?= e(url('events')) ?>">All events</a> · <a class="text-link" href="<?= e(url('events/calendar')) ?>">Calendar</a></p>
    </div>
</section>
