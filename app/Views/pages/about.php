<?php $title = 'About SLGTI'; $lead = $page['excerpt']; $eyebrow = 'The institute'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose-grid">
        <article class="prose"><?php if (!empty($page['image'])): ?><img class="about-featured-image" src="<?= e(media_url($page['image'])) ?>" alt="<?= e($page['image_alt'] ?: $page['title']) ?>" loading="eager"><?php endif; ?><?= $page['body'] ?></article>
        <aside class="side-card">
            <h2>At a glance</h2>
            <ul>
                <li>NVQ Levels 4, 5 and 6</li>
                <li>Six academic departments</li>
                <li>Workshops, laboratories and hostels</li>
                <li>Industry and German technical cooperation</li>
                <li>Kilinochchi, Northern Province</li>
            </ul>
            <a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply now</a>
        </aside>
    </div>
</section>
<section class="section section-soft" id="principal">
    <div class="container narrow">
        <p class="eyebrow">Principal's message</p>
        <h2><?= e($principal['title']) ?></h2>
        <div class="prose"><?= $principal['body'] ?></div>
    </div>
</section>
<section class="section" id="management">
    <div class="container">
        <p class="eyebrow">The management</p>
        <h2>Academic leadership</h2>
        <div class="staff-grid">
            <?php foreach ($staff as $person): ?>
                <article class="staff-card">
                    <div class="avatar"><?= e(strtoupper(substr($person['name'], 0, 1))) ?></div>
                    <h3><?= e($person['name']) ?></h3>
                    <p><?= e($person['position']) ?></p>
                    <?php if ($person['department_name']): ?><small><?= e($person['department_name']) ?></small><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section section-soft" id="facilities">
    <div class="container">
        <p class="eyebrow">Facilities</p>
        <h2>A campus built for practice</h2>
        <div class="life-grid">
            <?php foreach ($facilities as $facility): ?>
                <article class="life-card">
                    <a href="<?= e(url('facilities/' . $facility['slug'])) ?>"><img src="<?= e(media_url($facility['image'])) ?>" alt="<?= e($facility['image_alt'] ?: $facility['title']) ?>" loading="lazy" width="800" height="520"></a>
                    <div>
                        <h3><a href="<?= e(url('facilities/' . $facility['slug'])) ?>"><?= e($facility['title']) ?></a></h3>
                        <p><?= e($facility['summary']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
