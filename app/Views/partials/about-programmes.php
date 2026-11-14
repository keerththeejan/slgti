<section class="home-mid">
    <div class="container home-split">
        <div class="home-about">
            <p class="eyebrow"><?= e(settings('home_about_kicker', 'About SLGTI')) ?></p>
            <h2><?= e($aboutPage['title'] ?? settings('home_about_title', 'Building Skills for a Better Tomorrow')) ?></h2>
            <p><?= e($aboutPage['excerpt'] ?? settings('home_about_text', 'Sri Lanka German Training Institute (SLGTI) is a premier technical and vocational training institute established to provide industry-oriented training to youth in Sri Lanka. We are committed to developing skilled professionals through high-quality training, modern facilities and industry collaboration.')) ?></p>
            <a class="btn btn-navy" href="<?= e(nav_href(settings('home_about_button_url', '/about'))) ?>"><?= e(settings('home_about_button_label', 'Learn More About Us')) ?> <i class="bi bi-arrow-right"></i></a>
        </div>
        <img class="home-photo" src="<?= e(media_url($aboutPage['image'] ?? settings('home_about_image', 'assets/images/about-students.jpg'))) ?>" alt="<?= e($aboutPage['image_alt'] ?? 'SLGTI students in practical workshop training') ?>" width="640" height="420">
        <div class="home-programmes">
            <p class="eyebrow"><?= e(settings('home_programmes_kicker', 'Our Programmes')) ?></p>
            <h2><?= e(settings('home_programmes_title', 'NVQ Programmes')) ?></h2>
            <p class="home-lead"><?= e(settings('home_programmes_text', 'Industry-oriented training programmes for a successful career.')) ?></p>
            <div class="home-levels">
                <?php foreach ($levels as $level): ?>
                    <a class="home-level level-<?= e($level['slug']) ?>" href="<?= e(url('programme-levels/' . ($level['seo_slug'] ?: $level['slug']))) ?>">
                        <i class="bi bi-mortarboard" aria-hidden="true"></i>
                        <strong><?= e($level['code']) ?></strong>
                        <span><?= e($level['qualification']) ?></span>
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
