<main class="page-shell">
    <article class="staff-profile">
        <div class="staff-profile-head"><?php if ($person['image']): ?><img src="<?= e(media_url($person['image'])) ?>" alt="<?= e($person['image_alt'] ?: $person['name']) ?>" width="220" height="250"><?php else: ?><span class="staff-public-avatar" aria-hidden="true"><?= e(strtoupper(substr($person['name'], 0, 1))) ?></span><?php endif; ?><div><p class="eyebrow"><?= e($person['department_name'] ?: 'SLGTI Staff') ?></p><h1><?= e($person['name']) ?></h1><h2><?= e($person['position']) ?></h2><?php if ($person['staff_id']): ?><p>Staff ID: <?= e($person['staff_id']) ?></p><?php endif; ?></div></div>
        <?php if ($person['bio']): ?><section><h2>Profile</h2><div class="rich-content"><?= clean_html((string) $person['bio']) ?></div></section><?php endif; ?>
        <?php if ($person['qualifications']): ?><section><h2>Qualifications</h2><p><?= e($person['qualifications']) ?></p></section><?php endif; ?>
        <?php if ($person['expertise']): ?><section><h2>Areas of expertise</h2><p><?= e($person['expertise']) ?></p></section><?php endif; ?>
        <?php if ($person['email'] || $person['phone'] || $person['office_location']): ?><section><h2>Contact</h2><p><?php if ($person['email']): ?><a href="mailto:<?= e($person['email']) ?>"><?= e($person['email']) ?></a><?php endif; ?><?= $person['phone'] ? ' · ' . e($person['phone']) : '' ?><?= $person['office_location'] ? ' · ' . e($person['office_location']) : '' ?></p></section><?php endif; ?>
    </article>
</main>
