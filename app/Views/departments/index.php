<?php $title = 'Departments'; $lead = 'Six technology departments. One campus for practical training.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container dept-grid">
        <?php foreach ($departments as $department): ?>
            <a class="dept-card" href="<?= e(url('departments/' . $department['slug'])) ?>">
                <img src="<?= e(media_url($department['image'])) ?>" alt="" loading="lazy" width="640" height="420">
                <span class="dept-icon"><i class="bi <?= e($department['icon'] ?: 'bi-gear') ?>"></i></span>
                <h2><?= e($department['name']) ?></h2>
                <p><?= e($department['summary']) ?></p>
                <span class="text-link"><?= (int) $department['programme_count'] ?> programmes <i class="bi bi-arrow-right"></i></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>
