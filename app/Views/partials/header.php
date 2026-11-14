<?php
$phone = settings('phone', '021 492 7799');
$email = settings('email', 'info@slgti.ac.lk');
$location = settings('location_label', 'Kilinochchi, Sri Lanka');
$logo = settings('site_logo', '');
$logoSource = $logo !== '' ? media_url($logo) : asset('images/logo.svg');
$logoWidth = max(80, min(500, (int) settings('site_logo_width', '220')));
$logoHeight = max(24, min(180, (int) settings('site_logo_height', '52')));
$logoAlt = settings('site_logo_alt', 'Sri Lanka German Training Institute');
$nav = menu_tree('header');
?>
<div class="topbar">
    <div class="container topbar-inner">
        <ul class="topbar-meta">
            <li><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><i class="bi bi-telephone-fill"></i> <?= e($phone) ?></a></li>
            <li><a href="mailto:<?= e($email) ?>"><i class="bi bi-envelope-fill"></i> <?= e($email) ?></a></li>
            <li><i class="bi bi-geo-alt-fill"></i> <?= e($location) ?></li>
        </ul>
        <ul class="topbar-links">
            <li><a href="<?= e(setting_url('sis_url', url('students'))) ?>">Student Information System</a></li>
            <li><a href="<?= e(setting_url('elearning_url', url('students'))) ?>">E-Learning</a></li>
            <li><a href="<?= e(url('gallery')) ?>">Gallery</a></li>
            <li><a href="<?= e(url('faq')) ?>">FAQ</a></li>
            <li class="socials">
                <a href="<?= e(setting_url('facebook_url')) ?>" aria-label="Facebook" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                <a href="<?= e(setting_url('youtube_url')) ?>" aria-label="YouTube" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>
                <button class="top-search" type="button" data-open-search aria-label="Search"><i class="bi bi-search"></i></button>
            </li>
        </ul>
    </div>
</div>
<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('/')) ?>">
            <img src="<?= e($logoSource) ?>" alt="<?= e($logoAlt) ?>" width="<?= $logoWidth ?>" height="<?= $logoHeight ?>" style="max-height: <?= $logoHeight ?>px; height: auto;">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primaryNav">
            <span class="visually-hidden">Menu</span>
            <i class="bi bi-list"></i>
        </button>
        <?php require APP_PATH . '/Views/partials/navigation.php'; ?>
        <a class="btn btn-red header-apply" href="<?= e(url('apply')) ?>">Apply Now</a>
    </div>
</header>
<?php if ($message = flash('success')): ?>
    <div class="container"><div class="alert alert-success" role="status"><?= e($message) ?></div></div>
<?php endif; ?>
<?php if ($message = flash('error')): ?>
    <div class="container"><div class="alert alert-danger" role="alert"><?= e($message) ?></div></div>
<?php endif; ?>
