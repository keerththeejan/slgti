<?php
$title = $department['name'];
$lead = $department['summary'];
$departmentImage = $department['image'] ? media_url($department['image']) : asset('images/hero-1.jpg');
require APP_PATH . '/Views/partials/page-hero.php';
?>
<nav class="container breadcrumb-nav department-breadcrumb" aria-label="Breadcrumb">
    <a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span>
    <a href="<?= e(url('departments')) ?>">Departments</a><span aria-hidden="true">/</span>
    <span><?= e($department['name']) ?></span>
</nav>

<section class="department-hero section">
    <div class="container department-hero-grid">
        <div class="department-hero-media">
            <img src="<?= e($departmentImage) ?>" alt="<?= e($department['name']) ?> training facilities" width="1200" height="720">
            <span class="department-hero-icon" aria-hidden="true"><i class="bi <?= e($department['icon'] ?: 'bi-gear-wide-connected') ?>"></i></span>
        </div>
        <div class="department-hero-content">
            <p class="eyebrow">Academic department</p>
            <h2>Practical skills for a changing industry</h2>
            <p><?= e($department['summary']) ?></p>
            <div class="department-hero-actions">
                <a class="btn btn-red" href="#programmes">Explore programmes <i class="bi bi-arrow-down"></i></a>
                <a class="btn btn-line-dark" href="<?= e(url('apply')) ?>">Apply now <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <div class="department-facts" aria-label="Department overview">
                <div><strong><?= (int) $programmeCount ?></strong><span>Published programmes</span></div>
                <div><strong><?= (int) $staffCount ?></strong><span>Academic staff</span></div>
                <div><strong><i class="bi bi-geo-alt"></i></strong><span><?= e($department['location'] ?? 'SLGTI Kilinochchi') ?></span></div>
            </div>
        </div>
    </div>
</section>

<section class="section department-overview" id="overview">
    <div class="container department-content-grid">
        <article class="department-copy">
            <p class="eyebrow">About the department</p>
            <h2>Learning by doing</h2>
            <div class="prose"><?= $department['description'] ?></div>
            <?php if ($department['facilities']): ?>
                <div class="department-facilities">
                    <p class="eyebrow">Training environment</p>
                    <h2>Workshops and laboratories</h2>
                    <div class="prose"><?= $department['facilities'] ?></div>
                </div>
            <?php endif; ?>
        </article>
        <aside class="side-card department-office">
            <span class="section-icon"><i class="bi <?= e($department['icon'] ?: 'bi-building') ?>"></i></span>
            <p class="eyebrow">Connect with us</p>
            <h2>Department office</h2>
            <?php if ($department['head_name']): ?><p><strong><?= e($department['head_name']) ?></strong></p><?php endif; ?>
            <?php if ($department['email']): ?><p><a href="mailto:<?= e($department['email']) ?>"><i class="bi bi-envelope"></i> <?= e($department['email']) ?></a></p><?php endif; ?>
            <?php if ($department['phone']): ?><p><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string) $department['phone'])) ?>"><i class="bi bi-telephone"></i> <?= e($department['phone']) ?></a></p><?php endif; ?>
            <div class="department-office-links">
                <a href="<?= e(url('facilities')) ?>">Explore facilities <i class="bi bi-arrow-right"></i></a>
                <a href="<?= e(url('gallery')) ?>">View campus gallery <i class="bi bi-arrow-right"></i></a>
            </div>
        </aside>
    </div>
</section>

<section class="section section-soft department-programmes" id="programmes">
    <div class="container">
        <div class="section-head">
            <div><p class="eyebrow">Study pathways</p><h2>Programmes in this department</h2></div>
            <a class="text-link" href="<?= e(url('programmes?department=' . urlencode((string) $department['slug']))) ?>">View all programmes <i class="bi bi-arrow-right"></i></a>
        </div>
        <?php if ($programmes): ?>
            <div class="programme-grid department-programme-grid">
                <?php foreach ($programmes as $programme): ?>
                    <?php $programmeUrl = url('programmes/' . ($programme['seo_slug'] ?: $programme['slug'])); ?>
                    <article class="programme-card">
                        <?php if (!empty($programme['image'])): ?>
                            <img src="<?= e(media_url($programme['image'])) ?>" alt="<?= e($programme['title']) ?>" loading="lazy" width="640" height="400">
                        <?php else: ?>
                            <div class="programme-card-placeholder" aria-hidden="true"><i class="bi bi-tools"></i></div>
                        <?php endif; ?>
                        <div>
                            <div class="programme-card-meta"><span class="badge"><?= e($programme['level_code']) ?></span><?php if ($programme['duration']): ?><span><i class="bi bi-clock"></i> <?= e($programme['duration']) ?></span><?php endif; ?></div>
                            <h3><?= e($programme['title']) ?></h3>
                            <?php if ($programme['summary']): ?><p><?= e(excerpt($programme['summary'], 125)) ?></p><?php endif; ?>
                            <div class="programme-card-actions">
                                <a class="btn btn-line-dark" href="<?= e($programmeUrl) ?>">View programme</a>
                                <a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply now</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-mortarboard"></i><h3>Programmes will be published soon</h3><p>Check back for the latest study opportunities from this department.</p></div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($requirements)): ?>
<section class="section department-requirements" id="admissions">
    <div class="container narrow">
        <p class="eyebrow">Plan your application</p>
        <h2>Admissions requirements</h2>
        <ul class="link-list"><?php foreach ($requirements as $requirement): ?><li><a href="<?= e(url('requirements/' . $requirement['slug'])) ?>"><?= e($requirement['requirement']) ?><small><?= e($requirement['short_description']) ?></small></a></li><?php endforeach; ?></ul>
    </div>
</section>
<?php endif; ?>

<?php if ($staff): ?>
<section class="section section-soft department-staff" id="staff">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">Meet the team</p><h2>Academic staff</h2></div><a class="text-link" href="<?= e(url('staff')) ?>">Staff directory <i class="bi bi-arrow-right"></i></a></div>
        <div class="staff-grid">
            <?php foreach ($staff as $person): ?>
                <article class="staff-card department-staff-card">
                    <?php if (!empty($person['image'])): ?><img class="staff-photo" src="<?= e(media_url($person['image'])) ?>" alt="<?= e($person['image_alt'] ?: $person['name']) ?>" loading="lazy" width="96" height="96"><?php else: ?><span class="avatar" aria-hidden="true"><?= e(strtoupper(substr((string) $person['name'], 0, 1))) ?></span><?php endif; ?>
                    <h3><?= e($person['name']) ?></h3><p><?= e($person['position']) ?></p>
                    <?php if (!empty($person['section'])): ?><small><?= e($person['section']) ?></small><?php endif; ?>
                    <?php if (!empty($person['bio'])): ?><div class="staff-bio"><?= e(excerpt(strip_tags((string) $person['bio']), 110)) ?></div><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
