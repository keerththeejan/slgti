<?php
$phone = settings('phone', '021 492 7799');
$email = settings('email', 'info@slgti.ac.lk');
$location = settings('location_label', 'Kilinochchi, Sri Lanka');
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
            <img src="<?= e(asset('images/logo.svg')) ?>" alt="Sri Lanka German Training Institute" width="220" height="52">
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
