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
    <div class="container masonry" id="galleryGrid">
        <?php foreach ($images as $image): ?>
            <button type="button" class="shot" data-full="<?= e(media_url($image['image'])) ?>" data-caption="<?= e($image['title']) ?>">
                <img src="<?= e(media_url($image['image'])) ?>" alt="<?= e($image['title']) ?>" loading="lazy" width="700" height="480">
            </button>
        <?php endforeach; ?>
    </div>
    <?php if (!$images): ?><div class="container"><p>No photographs match this search.</p></div><?php endif; ?>
    <div class="container">
        <h2>Videos</h2>
        <div class="video-grid">
            <?php foreach ($videos as $video): ?>
                <?php $youtube = youtube_id($video['video_url']); ?>
                <article>
                    <h3><?= e($video['title']) ?></h3>
                    <p><?= e($video['description']) ?></p>
                    <?php if ($youtube): ?>
                        <iframe loading="lazy" title="<?= e($video['title']) ?>" src="https://www.youtube-nocookie.com/embed/<?= e($youtube) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php else: ?>
                        <p><a href="<?= e($video['video_url']) ?>" target="_blank" rel="noopener">Watch video</a></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<dialog id="lightbox"><button type="button" data-close-lightbox aria-label="Close">Close</button><img alt="" id="lightboxImage"><p id="lightboxCaption"></p></dialog>
