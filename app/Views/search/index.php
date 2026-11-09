<?php $title = 'Search'; $lead = $q !== '' ? 'Results for “' . $q . '”' : 'Search programmes, departments and news.'; require APP_PATH . '/Views/partials/page-hero.php'; ?>
<section class="section">
    <div class="container narrow">
        <form class="search-page" action="<?= e(url('search')) ?>" method="get">
            <input type="search" name="q" value="<?= e($q) ?>" minlength="2" required aria-label="Search">
            <button class="btn btn-red" type="submit">Search</button>
        </form>
        <?php if ($q !== '' && !$results): ?><p>No matches. Try a department name or programme title.</p><?php endif; ?>
        <ul class="link-list">
            <?php foreach ($results as $result): ?>
                <li>
                    <a href="<?= e(nav_href($result['url'])) ?>"><?= e($result['title']) ?><small><?= e($result['type']) ?> · <?= e(excerpt($result['text'], 140)) ?></small></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
