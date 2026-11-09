<?php
$crumbs = array_values(array_filter(explode('/', trim(current_path(), '/'))));
$icons = [
    'news' => 'bi-newspaper',
    'events' => 'bi-calendar-event',
    'departments' => 'bi-building',
    'programmes' => 'bi-mortarboard',
    'requirements' => 'bi-list-check',
    'programme-levels' => 'bi-award',
    'quick-links' => 'bi-grid',
    'sliders' => 'bi-images',
    'galleries' => 'bi-collection',
    'gallery-images' => 'bi-image',
    'videos' => 'bi-camera-video',
    'testimonials' => 'bi-chat-quote',
    'staff' => 'bi-people',
    'facilities' => 'bi-house-gear',
    'pages' => 'bi-file-earmark-richtext',
    'faqs' => 'bi-question-circle',
    'menu-items' => 'bi-menu-button-wide',
    'applications' => 'bi-file-earmark-text',
    'messages' => 'bi-envelope',
    'media' => 'bi-images',
    'users' => 'bi-person-gear',
    'settings' => 'bi-gear',
    'logs' => 'bi-clock-history',
    'backups' => 'bi-database',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle ?? 'Admin') ?> · SLGTI</title>
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/admin.css')) ?>" rel="stylesheet">
    <link href="<?= e(asset('css/admin-responsive.css')) ?>" rel="stylesheet">
</head>
<body class="admin-body">
<div class="admin-overlay" data-admin-overlay hidden></div>
<div class="admin-shell">
    <aside class="admin-side" id="adminSide">
        <div class="admin-side-head">
            <a class="admin-brand" href="<?= e(url('admin')) ?>">SLGTI <span>CMS</span></a>
            <button class="side-close" type="button" data-admin-close aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
        </div>
        <nav aria-label="Administration">
            <a class="<?= current_path() === '/admin' ? 'is-current' : '' ?>" href="<?= e(url('admin')) ?>"><i class="bi bi-grid"></i> Dashboard</a>
            <?php if (can('content.manage')): ?>
                <p>Content</p>
                <?php foreach ([
                    'news' => 'News', 'events' => 'Events', 'departments' => 'Departments', 'programmes' => 'Programmes', 'requirements' => 'Requirements',
                    'programme-levels' => 'NVQ cards', 'quick-links' => 'Quick links', 'sliders' => 'Homepage slides',
                    'galleries' => 'Albums', 'gallery-images' => 'Gallery images',
                    'videos' => 'Videos', 'testimonials' => 'Testimonials', 'staff' => 'Staff', 'facilities' => 'Facilities',
                    'pages' => 'Pages', 'faqs' => 'FAQs', 'menu-items' => 'Menus',
                ] as $key => $label): ?>
                    <a class="<?= current_path() === '/admin/' . $key || str_starts_with(current_path(), '/admin/' . $key . '/') ? 'is-current' : '' ?>" href="<?= e(url('admin/' . $key)) ?>"><i class="bi <?= e($icons[$key] ?? 'bi-dot') ?>"></i> <?= e($label) ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (can('applications.view') || can('applications.manage')): ?>
                <a href="<?= e(url('admin/applications')) ?>"><i class="bi bi-file-earmark-text"></i> Applications</a>
            <?php endif; ?>
            <?php if (can('messages.view')): ?>
                <a href="<?= e(url('admin/messages')) ?>"><i class="bi bi-envelope"></i> Messages</a>
            <?php endif; ?>
            <?php if (can('media.manage')): ?>
                <a href="<?= e(url('admin/media')) ?>"><i class="bi bi-images"></i> Media</a>
            <?php endif; ?>
            <?php if (can('users.manage')): ?>
                <p>System</p>
                <a href="<?= e(url('admin/users')) ?>"><i class="bi bi-person-gear"></i> Users</a>
            <?php endif; ?>
            <?php if (can('settings.manage')): ?><a href="<?= e(url('admin/settings')) ?>"><i class="bi bi-gear"></i> Settings &amp; SEO</a><?php endif; ?>
            <?php if (can('logs.view')): ?><a href="<?= e(url('admin/logs')) ?>"><i class="bi bi-clock-history"></i> Activity log</a><?php endif; ?>
            <?php if (can('backups.manage')): ?><a href="<?= e(url('admin/backups')) ?>"><i class="bi bi-database"></i> Backups</a><?php endif; ?>
        </nav>
    </aside>
    <div class="admin-main">
        <header class="admin-top">
            <button class="nav-toggle" type="button" data-admin-nav aria-controls="adminSide" aria-expanded="false" aria-label="Open menu"><i class="bi bi-list"></i></button>
            <nav class="admin-crumb" aria-label="Breadcrumb">
                <ol>
                    <?php foreach ($crumbs as $index => $crumb): ?>
                        <?php $href = url(implode('/', array_slice($crumbs, 0, $index + 1))); ?>
                        <li>
                            <?php if ($index < count($crumbs) - 1): ?>
                                <a href="<?= e($href) ?>"><?= e(ucwords(str_replace('-', ' ', $crumb))) ?></a>
                            <?php else: ?>
                                <span aria-current="page"><?= e($metaTitle ?? ucwords(str_replace('-', ' ', $crumb))) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <form class="admin-search" method="get" action="<?= e(url(ltrim(current_path(), '/'))) ?>">
                <label class="visually-hidden" for="adminSearch">Search this section</label>
                <input id="adminSearch" type="search" name="q" value="<?= e((string) ($_GET['q'] ?? '')) ?>" placeholder="Search">
            </form>
            <div class="admin-tools">
                <button class="icon-btn search-toggle" type="button" data-search-toggle aria-label="Search"><i class="bi bi-search"></i></button>
                <div class="dropdown">
                    <button class="icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications"><i class="bi bi-bell"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <?php if (can('messages.view')): ?><li><a class="dropdown-item" href="<?= e(url('admin/messages')) ?>"><i class="bi bi-envelope"></i> Messages</a></li><?php endif; ?>
                        <?php if (can('applications.view') || can('applications.manage')): ?><li><a class="dropdown-item" href="<?= e(url('admin/applications')) ?>"><i class="bi bi-file-earmark-text"></i> Applications</a></li><?php endif; ?>
                        <?php if (can('logs.view')): ?><li><a class="dropdown-item" href="<?= e(url('admin/logs')) ?>"><i class="bi bi-clock-history"></i> Activity log</a></li><?php endif; ?>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span><?= e($_SESSION['user_name'] ?? 'Account') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text"><?= e($_SESSION['user_name'] ?? '') ?></span></li>
                        <li><a class="dropdown-item" href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> View site</a></li>
                        <li>
                            <form method="post" action="<?= e(url('admin/logout')) ?>"><?= csrf_field() ?><button type="submit">Sign out</button></form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="admin-content">
            <?php if ($message = flash('success')): ?><div class="alert alert-success" role="status"><?= e($message) ?></div><?php endif; ?>
            <?php if ($message = flash('error')): ?><div class="alert alert-danger" role="alert"><?= e($message) ?></div><?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="confirmTitle">Please confirm</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmText"></div>
            <div class="modal-footer">
                <button type="button" class="button" style="background:#5C6B82" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="button" id="confirmYes">Continue</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.1/tinymce.min.js"></script>
<script src="<?= e(asset('js/admin.js')) ?>" defer></script>
</body>
</html>
