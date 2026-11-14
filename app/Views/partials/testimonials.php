<?php if (!empty($testimonials)): ?>
<section class="section home-testimonials" aria-labelledby="home-testimonials-title">
    <div class="container"><div class="section-heading"><p class="eyebrow">Student voices</p><h2 id="home-testimonials-title">What our community says</h2></div>
        <div class="testimonial-public-grid"><?php foreach (array_slice($testimonials, 0, 3) as $item): ?><article class="testimonial-public-card"><div class="testimonial-public-head"><?php if ($item['profile_image']): ?><img src="<?= e(media_url($item['profile_image'])) ?>" alt="<?= e($item['image_alt'] ?: $item['name']) ?>" loading="lazy" width="64" height="64"><?php else: ?><span class="testimonial-public-avatar" aria-hidden="true"><?= e(strtoupper(substr($item['name'], 0, 1))) ?></span><?php endif; ?><div><h3><?= e($item['name']) ?></h3><p><?= e($item['designation'] ?: $item['role']) ?></p></div></div><blockquote><?= e(excerpt($item['quote'], 170)) ?></blockquote></article><?php endforeach; ?></div>
    </div>
</section>
<?php endif; ?>
