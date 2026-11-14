<?php
$title = $page['title'] ?? 'Admissions';
$lead = $page['excerpt'] ?? 'NVQ Level 4, 5 and 6 pathways for young women and men from every district.';
$admissionImage = $page && $page['image'] ? media_url($page['image']) : asset('images/about-workshop.jpg');
$settings = settings();
require APP_PATH . '/Views/partials/page-hero.php';
?>
<nav class="container breadcrumb-nav admissions-breadcrumb" aria-label="Breadcrumb"><a href="<?= e(url('/')) ?>">Home</a><span aria-hidden="true">/</span><span>Admissions</span></nav>

<section class="admissions-hero section">
    <div class="container admissions-hero-grid">
        <div class="admissions-hero-content">
            <p class="eyebrow">Start your next chapter</p>
            <h2>Build practical skills for a brighter future</h2>
            <?php if ($page && $page['body']): ?><div class="prose"><?= $page['body'] ?></div><?php else: ?><p>Explore industry-oriented training at SLGTI, then choose the pathway and programme that match your goals.</p><?php endif; ?>
            <div class="admissions-actions">
                <a class="btn btn-red" href="<?= e(url('programmes')) ?>">Explore programmes <i class="bi bi-arrow-right"></i></a>
                <?php if ($applyOpen): ?><a class="btn btn-line-dark" href="<?= e(url('apply')) ?>">Apply online <i class="bi bi-arrow-up-right"></i></a><?php endif; ?>
            </div>
            <?php if (!$applyOpen): ?><p class="admissions-status"><i class="bi bi-info-circle"></i> Online applications are currently closed. Watch Admission Announcements for the next intake.</p><?php endif; ?>
        </div>
        <div class="admissions-hero-media"><img src="<?= e($admissionImage) ?>" alt="SLGTI practical training workshop" width="1200" height="720"></div>
    </div>
</section>

<section class="section admissions-process" id="process">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">How to apply</p><h2>Your admission journey</h2><p class="text-muted">Follow the current application steps published by SLGTI.</p></div></div>
        <div class="admissions-step-grid">
            <article><span>01</span><i class="bi bi-search"></i><h3>Choose a programme</h3><p>Compare departments, duration and entry requirements.</p></article>
            <article><span>02</span><i class="bi bi-file-earmark-check"></i><h3>Prepare your results</h3><p>Have your NIC and educational results ready.</p></article>
            <article><span>03</span><i class="bi bi-send"></i><h3>Apply online</h3><p>Submit the application and keep your reference number.</p></article>
            <article><span>04</span><i class="bi bi-people"></i><h3>Attend selection</h3><p>Shortlisted applicants are invited for an interview at Kilinochchi.</p></article>
        </div>
    </div>
</section>

<section class="section section-soft admissions-pathways" id="requirements">
    <div class="container admissions-content-grid">
        <div>
            <p class="eyebrow">Entry pathways</p>
            <h2>Find the right level</h2>
            <div class="admissions-level-list">
                <?php foreach ($levels as $level): ?>
                    <a href="<?= e(url('programme-levels/' . ($level['seo_slug'] ?: $level['slug']))) ?>"><span class="badge"><?= e($level['code']) ?></span><span><strong><?= e($level['name']) ?></strong><small><?= e($level['description']) ?></small></span><i class="bi bi-arrow-up-right"></i></a>
                <?php endforeach; ?>
            </div>
            <a class="text-link" href="<?= e(url('requirements')) ?>">Browse all requirements <i class="bi bi-arrow-right"></i></a>
        </div>
        <aside class="admissions-announcements side-card">
            <p class="eyebrow">Stay informed</p><h2>Admission announcements</h2>
            <?php if (!$announcements): ?><p class="text-muted">New announcements will appear here.</p><?php endif; ?>
            <ul class="link-list"><?php foreach ($announcements as $item): ?><li><a href="<?= e(url('news/' . $item['slug'])) ?>"><?= e($item['title']) ?><small><?= e(format_date($item['published_at'])) ?></small></a></li><?php endforeach; ?></ul>
        </aside>
    </div>
</section>

<?php $downloadableRequirements = array_values(array_filter($requirements, static fn (array $item): bool => !empty($item['document_path']))); ?>
<?php if ($downloadableRequirements): ?>
<section class="section admissions-documents" id="documents">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">Official resources</p><h2>Admission documents</h2></div></div>
        <div class="admissions-document-grid">
            <?php foreach ($downloadableRequirements as $document): ?><article class="admissions-document-card"><span class="section-icon"><i class="bi bi-file-earmark-arrow-down"></i></span><h3><?= e($document['requirement']) ?></h3><p><?= e($document['short_description'] ?: 'Official admission information and guidance.') ?></p><a class="btn btn-line-dark" href="<?= e(media_url($document['document_path'])) ?>" target="_blank" rel="noopener">Download <i class="bi bi-download"></i></a></article><?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($applicationFunctions): ?>
<section class="section section-soft admissions-services" id="application-services">
    <div class="container">
        <div class="section-head"><div><p class="eyebrow">Official application services</p><h2>Choose an application service</h2></div></div>
        <div class="public-application-grid"><?php foreach ($applicationFunctions as $function): ?><a class="public-application-card" href="<?= e($function['external_url']) ?>"<?= $function['open_new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : '' ?>><span class="public-application-icon"><i class="bi <?= e($function['icon']) ?>" aria-hidden="true"></i></span><span><strong><?= e($function['name']) ?></strong><small><?= $function['description'] ? e($function['description']) : e($function['application_type']) ?></small></span><i class="bi bi-arrow-up-right" aria-hidden="true"></i></a><?php endforeach; ?></div>
    </div>
</section>
<?php endif; ?>

<section class="section admissions-faq" id="faq">
    <div class="container admissions-content-grid">
        <div><p class="eyebrow">Questions answered</p><h2>Admission FAQs</h2><p class="text-muted">Find answers about eligibility, applications, selection and campus life.</p><a class="text-link" href="<?= e(url('faq')) ?>">View all FAQs <i class="bi bi-arrow-right"></i></a></div>
        <div class="faq-list"><?php foreach (array_slice($faqs, 0, 6) as $index => $faq): ?><details <?= $index === 0 ? 'open' : '' ?>><summary><span><?= e($faq['question']) ?></span><i class="bi bi-plus"></i></summary><div class="prose"><?= $faq['answer'] ?></div></details><?php endforeach; ?><?php if (!$faqs): ?><p class="text-muted">Admission FAQs will appear here.</p><?php endif; ?></div>
    </div>
</section>

<section class="section section-soft admissions-contact" id="contact">
    <div class="container admissions-contact-card">
        <div><p class="eyebrow">Need help?</p><h2>Talk to the admissions office</h2><p>Contact SLGTI for guidance about programmes, eligibility and the application process.</p></div>
        <div class="admissions-contact-details"><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string) ($settings['phone'] ?? ''))) ?>"><i class="bi bi-telephone"></i><?= e($settings['phone'] ?? '') ?></a><a href="mailto:<?= e($settings['email'] ?? '') ?>"><i class="bi bi-envelope"></i><?= e($settings['email'] ?? '') ?></a><span><i class="bi bi-geo-alt"></i><?= e($settings['address'] ?? 'Ariviyal Nagar, Kilinochchi 44000, Sri Lanka') ?></span></div>
        <a class="btn btn-red" href="<?= e(url('contact')) ?>">Contact us <i class="bi bi-arrow-right"></i></a>
    </div>
</section>
