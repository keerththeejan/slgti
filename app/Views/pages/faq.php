<?php $title = 'Frequently asked questions'; $lead = 'Admissions, training and student life.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container faq-list">
        <?php foreach ($faqs as $index => $faq): ?>
            <details <?= $index === 0 ? 'open' : '' ?>>
                <summary><?= e($faq['question']) ?></summary>
                <div class="prose"><?= $faq['answer'] ?></div>
            </details>
        <?php endforeach; ?>
    </div>
</section>
