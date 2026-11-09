<?php $title = 'Application received'; $lead = 'Keep your reference number for all correspondence.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container narrow">
        <?php if ($reference): ?><p class="reference">Reference <strong><?= e($reference) ?></strong></p><?php endif; ?>
        <p>The admissions office will contact shortlisted applicants by phone or email. You can also watch the admissions announcements.</p>
        <a class="btn btn-navy" href="<?= e(url('admissions')) ?>">Admission information</a>
    </div>
</section>
