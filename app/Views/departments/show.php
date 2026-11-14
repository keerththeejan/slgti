<?php $title = $department['name']; $lead = $department['summary']; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<nav class="container breadcrumb-nav" aria-label="Breadcrumb"><a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><a href="<?= e(url('departments')) ?>">Departments</a><span aria-hidden="true">/</span><span><?= e($department['name']) ?></span></nav>
<section class="section">
    <div class="container prose-grid">
        <article>
            <img class="cover" src="<?= e(media_url($department['image'])) ?>" alt="<?= e($department['name']) ?> workshop">
            <div class="prose"><?= $department['description'] ?></div>
            <?php if ($department['facilities']): ?>
                <h2>Workshops and laboratories</h2>
                <div class="prose"><?= $department['facilities'] ?></div>
            <?php endif; ?>
        </article>
        <aside class="side-card">
            <h2>Department office</h2>
            <p><?= e($department['head_name']) ?></p>
            <p><a href="mailto:<?= e($department['email']) ?>"><?= e($department['email']) ?></a></p>
            <p><?= e($department['phone']) ?></p>
            <a class="btn btn-red" href="<?= e(url('apply')) ?>">Apply to this department</a>
        </aside>
    </div>
    <div class="container">
        <?php if (!empty($requirements)): ?><h2>Admissions requirements</h2><ul class="link-list"><?php foreach ($requirements as $requirement): ?><li><a href="<?= e(url('requirements/' . $requirement['slug'])) ?>"><?= e($requirement['requirement']) ?><small><?= e($requirement['short_description']) ?></small></a></li><?php endforeach; ?></ul><?php endif; ?>
        <h2>Programmes</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Programme</th><th>Level</th><th>Duration</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($programmes as $programme): ?>
                    <tr>
                        <td><?= e($programme['title']) ?></td>
                        <td><?= e($programme['level_code']) ?></td>
                        <td><?= e($programme['duration']) ?></td>
                        <td><a href="<?= e(url('programmes/' . ($programme['seo_slug'] ?: $programme['slug']))) ?>">Details</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$programmes): ?><tr><td colspan="4">Programmes will be published soon.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($staff): ?>
            <h2>Academic staff</h2>
            <div class="staff-grid">
                <?php foreach ($staff as $person): ?>
                    <article class="staff-card"><h3><?= e($person['name']) ?></h3><p><?= e($person['position']) ?></p></article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
