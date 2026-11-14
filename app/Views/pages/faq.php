<?php $title = 'Frequently asked questions'; $lead = 'Admissions, training and student life.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container">
        <form class="faq-public-filters" method="get" role="search">
            <label class="sr-only" for="faq-search">Search FAQs</label>
            <input id="faq-search" type="search" name="q" value="<?= e($q ?? '') ?>" placeholder="Search questions and answers">
            <select name="category" aria-label="Filter by category"><option value="">All categories</option><?php foreach ($categories ?? [] as $item): ?><option value="<?= e($item['category']) ?>" <?= ($category ?? '') === $item['category'] ? 'selected' : '' ?>><?= e($item['category']) ?></option><?php endforeach; ?></select>
            <button class="btn btn-red" type="submit">Search</button>
            <?php if (($q ?? '') !== '' || ($category ?? '') !== ''): ?><a class="btn btn-outline-primary" href="<?= e(url('faq')) ?>">Clear</a><?php endif; ?>
        </form>
        <?php if ($faqs): ?><div class="faq-list"><?php foreach ($faqs as $index => $faq): ?>
            <details id="faq-<?= (int) $faq['id'] ?>" <?= $index === 0 ? 'open' : '' ?>>
                <summary><span><?= e($faq['question']) ?></span><?php if (!empty($faq['category'])): ?><small><?= e($faq['category']) ?></small><?php endif; ?></summary>
                <div class="prose"><?= $faq['answer'] ?></div>
            </details>
        <?php endforeach; ?></div><?php else: ?><div class="faq-empty"><i class="bi bi-search"></i><h2>No FAQs found</h2><p>Try a different search or category.</p></div><?php endif; ?>
    </div>
</section>
