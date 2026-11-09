<section class="hero" aria-roledescription="carousel" aria-label="Featured">
    <div class="hero-track" id="heroTrack">
        <?php foreach ($slides as $index => $slide): ?>
            <article class="hero-slide <?= $index === 0 ? 'is-active' : '' ?>" <?= $index === 0 ? '' : 'hidden' ?>>
                <img class="hero-bg" src="<?= e(media_url($slide['image'])) ?>" alt="" width="1600" height="700" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                <div class="hero-blue" aria-hidden="true"></div>
                <div class="hero-red" aria-hidden="true"></div>
                <div class="hero-copy">
                    <?php if (!empty($slide['eyebrow'])): ?>
                        <p class="hero-kicker"><?= e($slide['eyebrow']) ?></p>
                    <?php endif; ?>
                    <h1>
                        <?= e($slide['title']) ?>
                        <?php if (!empty($slide['title_highlight'])): ?>
                            <span><?= e($slide['title_highlight']) ?></span>
                        <?php endif; ?>
                    </h1>
                    <p><?= e($slide['subtitle']) ?></p>
                    <div class="hero-actions">
                        <?php if (!empty($slide['button_text'])): ?>
                            <a class="btn btn-red" href="<?= e(nav_href($slide['button_url'] ?: '/programmes')) ?>"><?= e($slide['button_text']) ?> <i class="bi bi-arrow-right"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($slide['secondary_text'])): ?>
                            <a class="btn btn-ghost" href="<?= e(nav_href($slide['secondary_url'] ?: '/apply')) ?>"><?= e($slide['secondary_text']) ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <button class="hero-arrow prev" type="button" data-hero="prev" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button>
    <button class="hero-arrow next" type="button" data-hero="next" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>
    <div class="hero-dots" role="tablist" aria-label="Slides">
        <?php foreach ($slides as $index => $slide): ?>
            <button type="button" class="<?= $index === 0 ? 'is-active' : '' ?>" data-hero-dot="<?= $index ?>" aria-label="Show slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
</section>
