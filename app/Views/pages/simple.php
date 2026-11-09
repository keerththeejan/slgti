<?php $title = $page['title']; $lead = $page['excerpt']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose narrow"><?= $page['body'] ?></div>
</section>
