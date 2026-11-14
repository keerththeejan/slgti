<main class="page-shell">
    <header class="section-heading"><p class="eyebrow">Our people</p><h1>Staff Directory</h1><p>Meet the people who support learning, training and institutional excellence at SLGTI.</p></header>
    <form class="public-filter" method="get"><input type="search" name="q" value="<?= e($query) ?>" placeholder="Search staff" aria-label="Search staff"><select name="department_id" aria-label="Filter by department"><option value="0">All departments</option><?php foreach ($departments as $department): ?><option value="<?= (int) $department['id'] ?>" <?= $departmentId === (int) $department['id'] ? 'selected' : '' ?>><?= e($department['name']) ?></option><?php endforeach; ?></select><button class="button" type="submit">Filter</button></form>
    <div class="staff-public-grid">
        <?php foreach ($staff as $person): ?>
            <article class="staff-public-card"><?php if ($person['image']): ?><img src="<?= e(media_url($person['image'])) ?>" alt="<?= e($person['image_alt'] ?: $person['name']) ?>" loading="lazy" width="300" height="340"><?php else: ?><span class="staff-public-avatar" aria-hidden="true"><?= e(strtoupper(substr($person['name'], 0, 1))) ?></span><?php endif; ?><div><h2><a href="<?= e(url('staff/' . $person['slug'])) ?>"><?= e($person['name']) ?></a></h2><p><?= e($person['position']) ?></p><?php if ($person['department_name']): ?><small><?= e($person['department_name']) ?></small><?php endif; ?></div></article>
        <?php endforeach; ?>
    </div>
    <?php if (!$staff): ?><p>No staff profiles match this search.</p><?php endif; ?>
</main>
