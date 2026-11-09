<?php $title = $programme['title']; $lead = $programme['level_code'] . ' · ' . $programme['department_name']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container prose-grid">
        <article>
            <img class="cover" src="<?= e(media_url($programme['image'])) ?>" alt="">
            <div class="prose">
                <?= $programme['description'] ?>
                <h2>Entry requirements</h2>
                <?= $programme['entry_requirements'] ?>
                <?php if (!empty($requirements)): ?>
                    <ul>
                        <?php foreach ($requirements as $requirement): ?>
                            <li><?= e($requirement['requirement']) ?></li>
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
                <div><dt>Level</dt><dd><?= e($programme['level_name']) ?></dd></div>
                <div><dt>Duration</dt><dd><?= e($programme['duration']) ?></dd></div>
                <div><dt>Department</dt><dd><a href="<?= e(url('departments/' . $programme['department_slug'])) ?>"><?= e($programme['department_name']) ?></a></dd></div>
            </dl>
            <a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply for this programme</a>
            <?php if ($related): ?>
                <h3>Related programmes</h3>
                <ul class="link-list">
                    <?php foreach ($related as $item): ?>
                        <li><a href="<?= e(url('programmes/' . $item['slug'])) ?>"><?= e($item['title']) ?><small><?= e($item['duration']) ?></small></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>
    </div>
</section>
