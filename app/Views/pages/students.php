<?php $title = 'Students'; $lead = 'Academic services, learning materials and campus support.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container quick-grid">
        <a class="quick-card tone-green" href="<?= e(setting_url('sis_url', '#')) ?>"><span class="quick-icon"><i class="bi bi-mortarboard"></i></span><span><strong>Student Information System</strong><small>Results, registration and student records</small></span></a>
        <a class="quick-card tone-purple" href="<?= e(setting_url('elearning_url', '#')) ?>"><span class="quick-icon"><i class="bi bi-laptop"></i></span><span><strong>E-Learning</strong><small>Learning materials for enrolled students</small></span></a>
        <a class="quick-card tone-blue" href="<?= e(url('faq')) ?>"><span class="quick-icon"><i class="bi bi-question-circle"></i></span><span><strong>Student FAQ</strong><small>Hostels, attendance and assessments</small></span></a>
        <a class="quick-card tone-teal" href="<?= e(url('contact')) ?>"><span class="quick-icon"><i class="bi bi-chat-dots"></i></span><span><strong>Student affairs</strong><small>Write to the institute</small></span></a>
    </div>
    <div class="container prose narrow">
        <h2>Academic calendar</h2>
        <?= $calendar['body'] ?>
    </div>
</section>
