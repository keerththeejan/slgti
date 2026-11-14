<?php require APP_PATH . '/Views/partials/hero-slider.php'; ?>
<?php require APP_PATH . '/Views/partials/quick-links.php'; ?>
<section class="home-stats" aria-label="SLGTI at a glance">
    <div class="container home-stats-grid">
        <?php foreach ($stats as $stat): ?>
            <article class="home-stat">
                <i class="bi bi-<?= e($stat['label'] === 'Students trained' ? 'people' : ($stat['label'] === 'NVQ programmes' ? 'mortarboard' : ($stat['label'] === 'Departments' ? 'building' : 'diagram-3'))) ?>" aria-hidden="true"></i>
                <strong data-count="<?= e(preg_replace('/[^0-9]/', '', (string) $stat['value'])) ?>"><?= e($stat['value']) ?></strong>
                <span><?= e($stat['label']) ?></span>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require APP_PATH . '/Views/partials/about-programmes.php'; ?>
<?php require APP_PATH . '/Views/partials/departments-news.php'; ?>
<?php require APP_PATH . '/Views/partials/testimonials.php'; ?>
