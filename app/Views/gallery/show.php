<?php $title = $gallery['title']; $lead = $gallery['description']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container">
        <p class="text-link"><a href="<?= e(url('gallery')) ?>">Gallery</a> / <?= e($gallery['title']) ?></p>
        <?php if ($gallery['gallery_date'] || $gallery['location'] || $gallery['event_title']): ?><p class="gallery-meta"><?php if ($gallery['gallery_date']): ?><?= e(format_date($gallery['gallery_date'], 'd M Y')) ?><?php endif; ?><?php if ($gallery['location']): ?> · <?= e($gallery['location']) ?><?php endif; ?><?php if ($gallery['event_title']): ?> · <a href="<?= e(url('events/' . $gallery['event_slug'])) ?>"><?= e($gallery['event_title']) ?></a><?php endif; ?></p><?php endif; ?>
        <div class="masonry" id="galleryGrid">
            <?php foreach ($images as $image): ?><button type="button" class="shot" data-full="<?= e(media_url($image['image'])) ?>" data-caption="<?= e($image['title']) ?>"><img src="<?= e(media_url($image['image'])) ?>" alt="<?= e($image['alt_text'] ?: $image['title']) ?>" loading="lazy" width="700" height="480"></button><?php endforeach; ?>
        </div>
        <?php if (!$images): ?><p>No published images are available in this album.</p><?php endif; ?>
        <?php if ($gallery['photographer']): ?><p class="small">Photography: <?= e($gallery['photographer']) ?></p><?php endif; ?>
    </div>
</section>
<dialog id="lightbox"><button type="button" data-close-lightbox aria-label="Close">Close</button><img alt="" id="lightboxImage"><p id="lightboxCaption"></p></dialog>
