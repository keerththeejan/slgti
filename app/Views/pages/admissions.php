<?php $title = 'Admissions'; $lead = 'NVQ Level 4, 5 and 6 pathways for young women and men from every district.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container steps">
        <article><span>01</span><h2>Choose a programme</h2><p>Compare departments, duration and entry requirements.</p></article>
        <article><span>02</span><h2>Prepare results</h2><p>Have your NIC and GCE Ordinary Level or Advanced Level results ready.</p></article>
        <article><span>03</span><h2>Apply online</h2><p>Submit the application. You will receive a reference number immediately.</p></article>
        <article><span>04</span><h2>Interview</h2><p>Shortlisted applicants are invited for an interview at Kilinochchi.</p></article>
    </div>
    <div class="container admit-grid">
        <div class="prose">
            <h2>Who can apply</h2>
            <p>Youth from all parts of Sri Lanka. The institute offers practice-oriented vocational training for both young women and men.</p>
            <h3>Entry pathways</h3>
            <ul>
                <?php foreach ($levels as $level): ?>
                    <li><strong><?= e($level['code']) ?> — <?= e($level['name']) ?>.</strong> <?= e($level['description']) ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-red" href="<?= e(url('apply')) ?>">Start application</a>
        </div>
        <aside>
            <h2>Admission announcements</h2>
            <?php if (!$announcements): ?><p>New announcements will appear here.</p><?php endif; ?>
            <ul class="link-list">
                <?php foreach ($announcements as $item): ?>
                    <li><a href="<?= e(url('news/' . $item['slug'])) ?>"><?= e($item['title']) ?><small><?= e(format_date($item['published_at'])) ?></small></a></li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </div>
</section>
