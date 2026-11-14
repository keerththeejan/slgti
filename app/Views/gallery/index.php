<?php $title = 'Gallery'; $lead = 'Workshops, training and student activities.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container gallery-tools">
        <div class="filters">
            <a class="<?= $activeAlbum === '' ? 'is-current' : '' ?>" href="<?= e(url('gallery')) ?>">All albums</a>
            <?php foreach ($albums as $album): ?>
                <a class="<?= $activeAlbum === $album['slug'] ? 'is-current' : '' ?>" href="<?= e(url('gallery?album=' . $album['slug'])) ?>"><?= e($album['title']) ?></a>
            <?php endforeach; ?>
        </div>
        <form method="get" action="<?= e(url('gallery')) ?>" role="search">
            <?php if ($activeAlbum): ?><input type="hidden" name="album" value="<?= e($activeAlbum) ?>"><?php endif; ?>
            <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search captions" aria-label="Search gallery">
            <button class="btn btn-navy" type="submit">Search</button>
        </form>
    </div>
    <div class="container gallery-albums"><?php foreach ($albums as $album): ?><a class="gallery-album-card" href="<?= e(url('gallery/' . $album['slug'])) ?>"><?php if ($album['cover']): ?><img src="<?= e(media_url($album['cover'])) ?>" alt="<?= e($album['title']) ?>" loading="lazy" width="320" height="180"><?php endif; ?><strong><?= e($album['title']) ?></strong><small><?= (int) $album['image_count'] ?> photographs</small></a><?php endforeach; ?></div>
    <div class="container masonry" id="galleryGrid">
        <?php foreach ($images as $image): ?>
            <button type="button" class="shot" data-full="<?= e(media_url($image['image'])) ?>" data-caption="<?= e($image['title']) ?>">
                <img src="<?= e(media_url($image['image'])) ?>" alt="<?= e($image['alt_text'] ?: $image['title']) ?>" loading="lazy" width="700" height="480">
            </button>
        <?php endforeach; ?>
    </div>
    <?php if (!$images): ?><div class="container"><p>No photographs match this search.</p></div><?php endif; ?>
    <div class="container">
        <h2>Videos</h2>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
                <?php $thumbnail = $video['thumbnail'] ? media_url($video['thumbnail']) : (($video['provider'] === 'youtube' && $video['video_id']) ? 'https://i.ytimg.com/vi/' . rawurlencode($video['video_id']) . '/hqdefault.jpg' : asset('images/placeholder.svg')); ?>
                <article class="video-card">
                    <a class="video-card-media" href="<?= e(url('videos/' . $video['slug'])) ?>"><img src="<?= e($thumbnail) ?>" alt="<?= e($video['title']) ?>" loading="lazy" width="640" height="360"><span class="video-play"><i class="bi bi-play-fill"></i></span></a>
                    <div class="video-card-body"><h3><a href="<?= e(url('videos/' . $video['slug'])) ?>"><?= e($video['title']) ?></a></h3><p><?= e(excerpt($video['description'] ?? '', 130)) ?></p></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<dialog id="lightbox"><button type="button" data-close-lightbox aria-label="Close">Close</button><img alt="" id="lightboxImage"><p id="lightboxCaption"></p></dialog>
