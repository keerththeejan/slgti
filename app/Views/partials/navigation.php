<?php $nav = $nav ?? menu_tree('header'); ?>
<nav class="primary-nav" id="primaryNav" aria-label="Primary">
    <ul>
        <?php if ($nav): ?>
            <?php foreach ($nav as $item): ?>
                <li class="<?= $item['children'] ? 'has-sub' : '' ?>">
                    <a href="<?= e(nav_href($item['url'])) ?>" <?= is_active($item['url']) ? 'aria-current="page"' : '' ?>>
                        <?= e($item['label']) ?>
                        <?php if ($item['children']): ?><i class="bi bi-chevron-down" aria-hidden="true"></i><?php endif; ?>
                    </a>
                    <?php if ($item['children']): ?>
                        <ul>
                            <?php foreach ($item['children'] as $child): ?>
                                <li><a href="<?= e(nav_href($child['url'])) ?>"><?= e($child['label']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><a href="<?= e(url('/')) ?>">Home</a></li>
            <li><a href="<?= e(url('about')) ?>">About Us</a></li>
            <li class="has-sub"><a href="<?= e(url('programmes')) ?>">Programmes <i class="bi bi-chevron-down" aria-hidden="true"></i></a><ul><li><a href="<?= e(url('programmes')) ?>">All programmes</a></li><li><a href="<?= e(url('departments')) ?>">Departments</a></li></ul></li>
            <li class="has-sub"><a href="<?= e(url('admissions')) ?>">Admissions <i class="bi bi-chevron-down" aria-hidden="true"></i></a><ul><li><a href="<?= e(url('admissions')) ?>">Admission information</a></li><li><a href="<?= e(url('apply')) ?>">Apply now</a></li></ul></li>
            <li class="has-sub"><a href="<?= e(url('students')) ?>">Students <i class="bi bi-chevron-down" aria-hidden="true"></i></a><ul><li><a href="<?= e(url('students')) ?>">Student services</a></li><li><a href="<?= e(url('dashboard')) ?>">Institutional dashboard</a></li><li><a href="<?= e(url('faq')) ?>">FAQ</a></li></ul></li>
            <li><a href="<?= e(url('news')) ?>">News &amp; Events</a></li>
            <li><a href="<?= e(url('contact')) ?>">Contact Us</a></li>
        <?php endif; ?>
    </ul>
</nav>
