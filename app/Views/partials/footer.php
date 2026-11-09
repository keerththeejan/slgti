<?php $footerNav = menu_tree('footer'); ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img src="<?= e(asset('images/logo-white.svg')) ?>" alt="SLGTI" width="210" height="48">
            <p><?= e(settings('footer_text', 'Providing high quality technical and vocational education and training to empower youth for a successful career.')) ?></p>
        </div>
        <div>
            <h2>Quick Links</h2>
            <ul class="footer-links">
                <?php if ($footerNav): ?>
                    <?php foreach ($footerNav as $item): ?>
                        <li><a href="<?= e(nav_href($item['url'])) ?>"><?= e($item['label']) ?> <i class="bi bi-chevron-right"></i></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div>
            <h2>Contact Information</h2>
            <ul class="contact-list">
                <li><i class="bi bi-telephone"></i> <?= e(settings('phone')) ?></li>
                <li><i class="bi bi-envelope"></i> <a href="mailto:<?= e(settings('email')) ?>"><?= e(settings('email')) ?></a></li>
                <li><i class="bi bi-geo-alt"></i> <?= e(settings('address')) ?></li>
                <li><i class="bi bi-clock"></i> <?= e(settings('hours')) ?></li>
            </ul>
        </div>
        <div class="footer-follow">
            <h2>Follow Us</h2>
            <div class="footer-socials">
                <a href="<?= e(setting_url('facebook_url')) ?>" aria-label="Facebook" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                <a href="<?= e(setting_url('youtube_url')) ?>" aria-label="YouTube" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a>
                <a href="<?= e(setting_url('linkedin_url')) ?>" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
                <a href="<?= e(url('contact')) ?>" aria-label="Location"><i class="bi bi-geo-alt"></i></a>
            </div>
            <img class="campus-art" src="<?= e(asset('images/campus-line.svg')) ?>" alt="" width="180" height="90">
            <p class="campus-caption">Skills for a Brighter Future</p>
        </div>
    </div>
    <div class="footer-base">
        <div class="container footer-base-inner">
            <p>&copy; <?= date('Y') ?> Sri Lanka German Training Institute (SLGTI), Kilinochchi. All rights reserved.</p>
            <ul>
                <li><a href="<?= e(url('privacy')) ?>">Privacy Policy</a></li>
                <li><a href="<?= e(url('terms')) ?>">Terms of Use</a></li>
                <li><a href="<?= e(url('accessibility')) ?>">Accessibility</a></li>
                <li><a href="<?= e(url('admin/login')) ?>">Staff login</a></li>
            </ul>
        </div>
    </div>
</footer>
