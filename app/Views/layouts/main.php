<?php
$settings = settings();
$metaTitle = $metaTitle ?? ($settings['site_name'] ?? 'SLGTI');
$metaDescription = $metaDescription ?? ($settings['meta_description'] ?? '');
$metaKeywords = $metaKeywords ?? '';
$metaCanonical = $metaCanonical ?? absolute_url(current_path() === '/' ? '' : ltrim(current_path(), '/'));
$metaImage = $metaImage ?? absolute_url('assets/images/hero-1.jpg');
$metaTwitterImage = $metaTwitterImage ?? $metaImage;
$metaOgTitle = $metaOgTitle ?? $metaTitle;
$metaOgDescription = $metaOgDescription ?? $metaDescription;
$metaRobots = $metaRobots ?? 'index, follow';
$favicon32 = settings('favicon_32', '');
$favicon16 = settings('favicon_16', '');
$faviconApple = settings('favicon_apple', '');
$faviconLarge = settings('favicon_large', '');
$faviconFallback = asset('images/favicon.svg');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <?php if ($metaKeywords !== ''): ?><meta name="keywords" content="<?= e($metaKeywords) ?>"><?php endif; ?>
    <meta name="robots" content="<?= e($metaRobots) ?>">
    <link rel="canonical" href="<?= e($metaCanonical) ?>">
    <meta property="og:title" content="<?= e($metaOgTitle) ?>">
    <meta property="og:description" content="<?= e($metaOgDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($metaCanonical) ?>">
    <meta property="og:image" content="<?= e($metaImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($metaOgTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaOgDescription) ?>">
    <meta name="twitter:image" content="<?= e($metaTwitterImage) ?>">
    <meta name="theme-color" content="#062B52">
    <link rel="icon" href="<?= e($favicon32 ? media_url($favicon32) : $faviconFallback) ?>" type="<?= $favicon32 && str_ends_with($favicon32, '.svg') ? 'image/svg+xml' : 'image/png' ?>" sizes="32x32">
    <link rel="icon" href="<?= e($favicon16 ? media_url($favicon16) : ($favicon32 ? media_url($favicon32) : $faviconFallback)) ?>" type="<?= $favicon32 && str_ends_with($favicon32, '.svg') ? 'image/svg+xml' : 'image/png' ?>" sizes="16x16">
    <link rel="icon" href="<?= e($faviconLarge ? media_url($faviconLarge) : ($favicon32 ? media_url($favicon32) : $faviconFallback)) ?>" type="<?= $faviconLarge && str_ends_with($faviconLarge, '.svg') ? 'image/svg+xml' : 'image/png' ?>" sizes="512x512">
    <link rel="apple-touch-icon" href="<?= e($faviconApple ? media_url($faviconApple) : ($favicon32 ? media_url($favicon32) : $faviconFallback)) ?>" sizes="180x180">
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
    <?php if (!empty($structuredData)): ?>
        <script type="application/ld+json"><?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif; ?>
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>
<?php require APP_PATH . '/Views/partials/header.php'; ?>
<main id="main" tabindex="-1">
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
