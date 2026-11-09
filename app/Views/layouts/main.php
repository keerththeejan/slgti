<?php
$settings = settings();
$metaTitle = $metaTitle ?? ($settings['site_name'] ?? 'SLGTI');
$metaDescription = $metaDescription ?? ($settings['meta_description'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e(absolute_url(current_path() === '/' ? '' : ltrim(current_path(), '/'))) ?>">
    <meta property="og:title" content="<?= e($metaTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(absolute_url(current_path() === '/' ? '' : ltrim(current_path(), '/'))) ?>">
    <meta property="og:image" content="<?= e(absolute_url('assets/images/hero-1.jpg')) ?>">
    <meta name="theme-color" content="#062B52">
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/app.css')) ?>" rel="stylesheet">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Sri Lanka German Training Institute",
        "alternateName": "SLGTI",
        "url": "<?= e(absolute_url()) ?>",
        "email": "<?= e($settings['email'] ?? 'info@slgti.ac.lk') ?>",
        "telephone": "<?= e($settings['phone'] ?? '') ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Ariviyal Nagar",
            "addressLocality": "Kilinochchi",
            "postalCode": "44000",
            "addressCountry": "LK"
        }
    }
    </script>
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>
<?php require APP_PATH . '/Views/partials/header.php'; ?>
<main id="main">
    <?= $content ?>
</main>
<?php require APP_PATH . '/Views/partials/footer.php'; ?>
<div class="search-modal" id="searchModal" hidden>
    <form class="search-modal-card" action="<?= e(url('search')) ?>" method="get" role="search">
        <label for="site-search">Search SLGTI</label>
        <input id="site-search" name="q" type="search" placeholder="Programmes, departments, news" required minlength="2">
        <button class="btn btn-red" type="submit">Search</button>
        <button class="icon-btn" type="button" data-close-search aria-label="Close search"><i class="bi bi-x-lg"></i></button>
    </form>
</div>
<script>window.SLGTI_BASE = <?= json_encode(rtrim(base_url(), '/')) ?>;</script>
<script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
