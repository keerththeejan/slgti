<main class="page-shell">
    <header class="section-heading"><p class="eyebrow">SLGTI community</p><h1>Testimonials</h1><p>Hear from students, graduates and partners who have experienced SLGTI.</p></header>
    <div class="testimonial-public-grid">
        <?php foreach ($testimonials as $item): ?>
            <article class="testimonial-public-card">
                <div class="testimonial-public-head"><?php if ($item['profile_image']): ?><img src="<?= e(media_url($item['profile_image'])) ?>" alt="<?= e($item['image_alt'] ?: $item['name']) ?>" loading="lazy" width="72" height="72"><?php else: ?><span class="testimonial-public-avatar" aria-hidden="true"><?= e(strtoupper(substr($item['name'], 0, 1))) ?></span><?php endif; ?><div><h2><?= e($item['name']) ?></h2><p><?= e($item['designation'] ?: $item['role']) ?></p></div></div>
                <blockquote><?= e($item['quote']) ?></blockquote>
                <?php if ($item['department_name'] || $item['programme_name']): ?><small><?= e($item['department_name'] ?: $item['programme_name']) ?></small><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!$testimonials): ?><p>No testimonials are currently available.</p><?php endif; ?>
</main>
