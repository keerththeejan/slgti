<section class="quick-access" aria-label="Quick access">
    <div class="container">
        <div class="quick-grid">
            <?php foreach ($quickLinks as $link):
                $color = preg_match('/^#[0-9A-Fa-f]{3,8}$/', (string) $link['color']) ? $link['color'] : '#0B5CAB';
                ?>
                <a class="quick-card" href="<?= e(nav_href($link['url'])) ?>" target="<?= e($link['target'] ?: '_self') ?>" <?= ($link['target'] ?? '') === '_blank' ? 'rel="noopener noreferrer"' : '' ?>>
                    <span class="quick-icon" style="background: <?= e($color) ?>"><i class="bi <?= e($link['icon'] ?: 'bi-link-45deg') ?>"></i></span>
                    <span class="quick-copy">
                        <strong><?= e($link['title']) ?></strong>
                        <small><?= e($link['subtitle']) ?></small>
                    </span>
                    <i class="bi bi-chevron-right quick-go" aria-hidden="true"></i>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
