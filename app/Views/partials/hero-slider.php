<section class="hero" aria-roledescription="carousel" aria-label="Featured">
    <div class="hero-track" id="heroTrack">
        <?php foreach ($slides as $index => $slide): ?>
            <?php $alignment = in_array($slide['text_alignment'] ?? 'left', ['left', 'center', 'right'], true) ? $slide['text_alignment'] : 'left'; $position = in_array($slide['text_position'] ?? 'left', ['left', 'center', 'right'], true) ? $slide['text_position'] : 'left'; $overlay = preg_match('/^#[0-9A-Fa-f]{3,8}$/', (string) ($slide['overlay_color'] ?? '')) ? $slide['overlay_color'] : '#0B5CAB'; $opacity = max(0, min(100, (int) ($slide['overlay_opacity'] ?? 35))) / 100; ?>
            <article class="hero-slide <?= $index === 0 ? 'is-active' : '' ?>" <?= $index === 0 ? '' : 'hidden' ?> aria-label="Slide <?= $index + 1 ?>: <?= e($slide['title']) ?>">
                <picture>
                    <?php if (!empty($slide['mobile_image'])): ?><source media="(max-width: 700px)" srcset="<?= e(media_url($slide['mobile_image'])) ?>"><?php endif; ?>
                    <img class="hero-bg" src="<?= e(media_url($slide['image'])) ?>" alt="<?= e(($slide['image_alt'] ?? '') ?: $slide['title']) ?>" width="1600" height="700" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                </picture>
                <div class="hero-blue" style="--hero-overlay-color: <?= e($overlay) ?>; --hero-overlay-opacity: <?= e((string) $opacity) ?>" aria-hidden="true"></div>
                <div class="hero-red" aria-hidden="true"></div>
                <div class="hero-copy hero-copy-<?= e($position) ?>" style="text-align: <?= e($alignment) ?>">
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
