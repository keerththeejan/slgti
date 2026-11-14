<?php $title = $selectedLevel ? $selectedLevel['code'] . ' programmes' : 'NVQ programmes'; $lead = $selectedLevel ? 'Published programmes assigned to ' . $selectedLevel['name'] . '.' : 'Certificate, diploma and higher national diploma pathways.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container programme-tools">
        <form class="programme-filters" method="get" action="<?= e(url('programmes')) ?>">
            <label class="visually-hidden" for="programme-search">Search programmes</label>
            <input id="programme-search" type="search" name="q" value="<?= e($query) ?>" placeholder="Search programme name or level code">
            <label class="visually-hidden" for="programme-duration">Duration</label>
            <input id="programme-duration" type="search" name="duration" value="<?= e($duration) ?>" placeholder="Filter by duration">
            <?php if ($activeLevel !== ''): ?><input type="hidden" name="level" value="<?= e($activeLevel) ?>"><?php endif; ?>
            <?php if ($activeDepartment !== ''): ?><input type="hidden" name="department" value="<?= e($activeDepartment) ?>"><?php endif; ?>
            <button class="btn btn-navy" type="submit">Search</button>
            <?php if ($query !== '' || $duration !== '' || $activeLevel !== '' || $activeDepartment !== ''): ?><a class="btn btn-line btn-line-dark" href="<?= e(url('programmes')) ?>">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="container filters" aria-label="Filter programmes">
        <a class="<?= $activeLevel === '' && $activeDepartment === '' ? 'is-current' : '' ?>" href="<?= e(url('programmes')) ?>">All</a>
        <?php foreach ($levels as $level): ?>
            <a class="<?= $activeLevel === $level['slug'] ? 'is-current' : '' ?>" href="<?= e(url('programmes?level=' . $level['slug'])) ?>"><?= e($level['code']) ?></a>
        <?php endforeach; ?>
        <?php foreach ($departments as $department): ?>
            <a class="<?= $activeDepartment === $department['slug'] ? 'is-current' : '' ?>" href="<?= e(url('programmes?department=' . $department['slug'])) ?>"><?= e($department['name']) ?></a>
        <?php endforeach; ?>
    </div>
    <div class="container programme-results">
        <p class="programme-result-count"><?= (int) $pager['total'] ?> programme<?= $pager['total'] === 1 ? '' : 's' ?> found</p>
    </div>
    <div class="container programme-grid">
        <?php foreach ($programmes as $programme): ?>
            <article class="programme-card">
                <?php if (!empty($programme['image'])): ?><img src="<?= e(media_url($programme['image'])) ?>" alt="<?= e($programme['title']) ?>" loading="lazy" width="640" height="400"><?php else: ?><div class="programme-card-placeholder" aria-hidden="true"><i class="bi bi-mortarboard"></i></div><?php endif; ?>
                <div>
                    <p class="badge"><?= e($programme['level_code']) ?> · <?= e($programme['qualification']) ?></p>
                    <h2><?= e($programme['title']) ?></h2>
                    <p><?= e($programme['summary']) ?></p>
                    <ul><li><i class="bi bi-clock"></i> <?= e($programme['duration']) ?></li><li><i class="bi bi-building"></i> <?= e($programme['department_name']) ?></li></ul>
                    <div class="programme-card-actions"><a class="btn btn-navy" href="<?= e(url('programmes/' . ($programme['seo_slug'] ?: $programme['slug']))) ?>">View details</a><a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply now</a></div>
                </div>
            </article>
        <?php endforeach; ?>
        <?php if (!$programmes): ?><p class="empty-state">No published programmes match these filters.</p><?php endif; ?>
    </div>
    <div class="container"><?= render_pagination($pager, '/programmes', array_filter(['level' => $activeLevel, 'department' => $activeDepartment, 'q' => $query, 'duration' => $duration])) ?></div>
</section>
