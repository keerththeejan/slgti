<?php $title = 'Contact us'; $lead = 'Ariviyal Nagar, Kilinochchi.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container contact-grid">
        <form class="form-card" method="post" action="<?= e(url('contact')) ?>" novalidate>
            <?= csrf_field() ?>
            <p class="hp"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
            <div class="form-row">
                <label>Name <input name="name" value="<?= e((string) old('name')) ?>" required><?php if ($err = field_error('name')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
                <label>Email <input type="email" name="email" value="<?= e((string) old('email')) ?>" required><?php if ($err = field_error('email')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <div class="form-row">
                <label>Phone <input name="phone" value="<?= e((string) old('phone')) ?>"></label>
                <label>Subject <input name="subject" value="<?= e((string) old('subject')) ?>" required><?php if ($err = field_error('subject')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            </div>
            <label>Message <textarea name="message" rows="6" required><?= e((string) old('message')) ?></textarea><?php if ($err = field_error('message')): ?><small class="error"><?= e($err) ?></small><?php endif; ?></label>
            <button class="btn btn-red" type="submit">Send message</button>
        </form>
        <aside class="side-card">
            <h2>Institute office</h2>
            <ul class="contact-list">
                <li><i class="bi bi-geo-alt"></i> <?= e(settings('address')) ?></li>
                <li><i class="bi bi-telephone"></i> <?= e(settings('phone')) ?></li>
                <li><i class="bi bi-printer"></i> <?= e(settings('fax')) ?></li>
                <li><i class="bi bi-envelope"></i> <?= e(settings('email')) ?></li>
                <li><i class="bi bi-clock"></i> <?= e(settings('hours')) ?></li>
            </ul>
            <iframe title="Map of SLGTI Kilinochchi" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=80.36%2C9.36%2C80.43%2C9.41&amp;layer=mapnik&amp;marker=9.384%2C80.397"></iframe>
        </aside>
    </div>
</section>
