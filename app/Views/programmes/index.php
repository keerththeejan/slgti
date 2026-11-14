<?php $title = 'NVQ programmes'; $lead = 'Certificate, diploma and higher national diploma pathways.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container filters" aria-label="Filter programmes">
        <a class="<?= $activeLevel === '' && $activeDepartment === '' ? 'is-current' : '' ?>" href="<?= e(url('programmes')) ?>">All</a>
        <?php foreach ($levels as $level): ?>
            <a class="<?= $activeLevel === $level['slug'] ? 'is-current' : '' ?>" href="<?= e(url('programmes?level=' . $level['slug'])) ?>"><?= e($level['code']) ?></a>
        <?php endforeach; ?>
        <?php foreach ($departments as $department): ?>
            <a class="<?= $activeDepartment === $department['slug'] ? 'is-current' : '' ?>" href="<?= e(url('programmes?department=' . $department['slug'])) ?>"><?= e($department['name']) ?></a>
        <?php endforeach; ?>
    </div>
    <div class="container programme-grid">
        <?php foreach ($programmes as $programme): ?>
            <article class="programme-card">
                <img src="<?= e(media_url($programme['image'])) ?>" alt="<?= e($programme['title']) ?>" loading="lazy" width="640" height="400">
                <div>
                    <p class="badge"><?= e($programme['level_code']) ?> · <?= e($programme['qualification']) ?></p>
                    <h2><?= e($programme['title']) ?></h2>
                    <p><?= e($programme['summary']) ?></p>
                    <ul><li><i class="bi bi-clock"></i> <?= e($programme['duration']) ?></li><li><i class="bi bi-building"></i> <?= e($programme['department_name']) ?></li></ul>
                    <a class="btn btn-navy" href="<?= e(url('programmes/' . ($programme['seo_slug'] ?: $programme['slug']))) ?>">View details</a>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (!$programmes): ?><p>No programmes match this filter.</p><?php endif; ?>
    </div>
</section>
