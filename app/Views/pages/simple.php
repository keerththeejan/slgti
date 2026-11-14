<?php $title = $page['title']; $lead = $page['excerpt']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container page-content-layout">
        <article class="prose narrow">
            <?php if (!empty($page['image'])): ?><img class="page-featured-image" src="<?= e(media_url($page['image'])) ?>" alt="<?= e($page['image_alt'] ?: $page['title']) ?>" loading="eager"><?php endif; ?>
            <?php if (!empty($page['parent_title'])): ?><nav class="page-breadcrumbs" aria-label="Breadcrumb"><a href="<?= e(url('page/' . $page['parent_slug'])) ?>"><?= e($page['parent_title']) ?></a><span aria-hidden="true">/</span><span><?= e($page['title']) ?></span></nav><?php endif; ?>
            <?= $page['body'] ?>
        </article>
        <?php if (!empty($children)): ?><aside class="page-child-nav"><h2>On this page</h2><ul><?php foreach ($children as $child): ?><li><a href="<?= e(url('page/' . $child['slug'])) ?>"><?= e($child['title']) ?></a></li><?php endforeach; ?></ul></aside><?php endif; ?>
    </div>
</section>
