<section class="page-hero">
    <div class="container">
        <p class="eyebrow"><?= e($eyebrow ?? 'SLGTI Kilinochchi') ?></p>
        <h1><?= e($title) ?></h1>
        <?php if (!empty($lead)): ?><p><?= e($lead) ?></p><?php endif; ?>
    </div>
</section>
