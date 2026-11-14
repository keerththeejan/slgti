<?php $title = 'About SLGTI'; $lead = $page['excerpt']; $eyebrow = 'The institute'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section about-intro">
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
<section class="section section-soft about-principal" id="principal">
    <div class="container about-principal-inner">
        <p class="eyebrow">Principal's message</p>
        <h2><?= e($principal['title']) ?></h2>
        <div class="about-principal-layout">
            <?php if (!empty($principal['image'])): ?><img class="principal-image" src="<?= e(media_url($principal['image'])) ?>" alt="<?= e($principal['image_alt'] ?: $principal['title']) ?>" loading="lazy" width="720" height="420"><?php endif; ?>
            <div class="prose principal-copy"><?= $principal['body'] ?></div>
        </div>
    </div>
</section>
<section class="section management-section" id="management">
    <div class="container">
        <p class="eyebrow">The management</p>
        <h2>Academic leadership</h2>
        <div class="staff-grid">
            <?php foreach ($staff as $person): ?>
                <article class="staff-card">
                    <?php if (!empty($person['image'])): ?>
                        <img class="staff-photo" src="<?= e(media_url($person['image'])) ?>" alt="<?= e($person['image_alt'] ?: $person['name']) ?>" loading="lazy" width="104" height="104">
                    <?php else: ?>
                        <div class="avatar" aria-hidden="true"><?= e(strtoupper(substr($person['name'], 0, 1))) ?></div>
                    <?php endif; ?>
                    <h3><?= e($person['name']) ?></h3>
                    <p><?= e($person['management_role'] ?: $person['position']) ?></p>
                    <?php if ($person['department_name']): ?><small><?= e($person['department_name']) ?></small><?php endif; ?>
                    <?php if (!empty($person['bio'])): ?><div class="staff-bio"><?= e(trim(strip_tags($person['bio']))) ?></div><?php endif; ?>
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
