<?php $title = $programme['title']; $lead = $programme['level_code'] . ' · ' . $programme['department_name']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<nav class="container breadcrumb-nav" aria-label="Breadcrumb"><a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><a href="<?= e(url('programmes')) ?>">Programmes</a><span aria-hidden="true">/</span><span><?= e($programme['title']) ?></span></nav>
<section class="section">
    <div class="container prose-grid">
        <article>
            <img class="cover" src="<?= e(media_url($programme['image'])) ?>" alt="<?= e($programme['title']) ?>">
            <div class="prose">
                <?= $programme['description'] ?>
                <h2>Entry requirements</h2>
                <?= $programme['entry_requirements'] ?>
                <?php if (!empty($requirements)): ?>
                    <ul>
                        <?php foreach ($requirements as $requirement): ?>
                            <li><?php if (!empty($requirement['slug'])): ?><a href="<?= e(url('requirements/' . $requirement['slug'])) ?>"><?= e($requirement['requirement']) ?></a><?php else: ?><?= e($requirement['requirement']) ?><?php endif; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <h2>Career outcomes</h2>
                <?= $programme['career_outcomes'] ?>
            </div>
        </article>
        <aside class="side-card">
            <h2><?= e($programme['qualification']) ?></h2>
            <dl>
                <div><dt>Level</dt><dd><a href="<?= e(url('programme-levels/' . ($programme['level_seo_slug'] ?: $programme['level_slug']))) ?>"><?= e($programme['level_name']) ?></a></dd></div>
                <div><dt>Duration</dt><dd><?= e($programme['duration']) ?></dd></div>
                <div><dt>Department</dt><dd><a href="<?= e(url('departments/' . $programme['department_slug'])) ?>"><?= e($programme['department_name']) ?></a></dd></div>
            </dl>
            <a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply for this programme</a>
            <?php if (!empty($brochure)): ?><a class="btn btn-navy" href="<?= e(media_url($brochure)) ?>" target="_blank" rel="noopener">Download brochure</a><?php endif; ?>
            <?php if ($related): ?>
                <h3>Related programmes</h3>
                <ul class="link-list">
                    <?php foreach ($related as $item): ?>
                        <li><a href="<?= e(url('programmes/' . ($item['seo_slug'] ?: $item['slug']))) ?>"><?= e($item['title']) ?><small><?= e($item['duration']) ?></small></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>
    </div>
</section>
