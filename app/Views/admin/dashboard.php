<div class="stat-grid">
    <?php foreach ([
        ['news_total', 'Total news', 'bi-newspaper', 'news'],
        ['news_draft', 'Draft news', 'bi-pencil-square', 'news?status=draft'],
        ['news_archived', 'Archived news', 'bi-archive', 'news?status=archived'],
        ['news_featured', 'Featured news', 'bi-star', 'news?featured=1'],
    ] as [$key, $label, $icon, $resource]): ?>
        <a class="admin-stat-card" href="<?= e(url('admin/' . $resource)) ?>"><i class="bi <?= e($icon) ?> stat-icon"></i><span><?= e($label) ?></span><strong><?= (int) $counts[$key] ?></strong><small>News management <i class="bi bi-arrow-up-right"></i></small></a>
    <?php endforeach; ?>
    <?php foreach ([['events_upcoming', 'Upcoming events', 'bi-calendar-event'], ['events_ongoing', 'Ongoing events', 'bi-broadcast'], ['events_completed', 'Completed events', 'bi-calendar-check'], ['events_draft', 'Draft events', 'bi-calendar-x']] as [$key, $label, $icon]): ?>
        <a class="admin-stat-card" href="<?= e(url('admin/events')) ?>"><i class="bi <?= e($icon) ?> stat-icon"></i><span><?= e($label) ?></span><strong><?= (int) $counts[$key] ?></strong><small>Events management <i class="bi bi-arrow-up-right"></i></small></a>
    <?php endforeach; ?>
    <?php foreach ([['departments_total', 'Total departments', 'bi-building'], ['departments_inactive', 'Inactive departments', 'bi-building-x']] as [$key, $label, $icon]): ?>
        <a class="admin-stat-card" href="<?= e(url('admin/departments')) ?>"><i class="bi <?= e($icon) ?> stat-icon"></i><span><?= e($label) ?></span><strong><?= (int) $counts[$key] ?></strong><small>Departments management <i class="bi bi-arrow-up-right"></i></small></a>
    <?php endforeach; ?>
    <?php foreach ([
        ['news', 'Published news', 'bi-newspaper', 'news'],
        ['departments', 'Departments', 'bi-building', 'departments'],
        ['albums', 'Gallery albums', 'bi-collection', 'galleries'],
        ['staff', 'Staff profiles', 'bi-people', 'staff'],
        ['events', 'Upcoming events', 'bi-calendar-event', 'events'],
        ['programmes', 'Programmes', 'bi-mortarboard', 'programmes'],
        ['applications', 'Applications', 'bi-file-earmark-text', 'applications'],
        ['pending', 'Pending applications', 'bi-hourglass-split', 'applications'],
        ['messages', 'New messages', 'bi-envelope', 'messages'],
        ['media', 'Media files', 'bi-images', 'media'],
    ] as [$key, $label, $icon, $resource]): ?>
        <a class="admin-stat-card" href="<?= e(url('admin/' . $resource)) ?>">
            <i class="bi <?= e($icon) ?> stat-icon" aria-hidden="true"></i><span><?= e($label) ?></span><strong><?= (int) $counts[$key] ?></strong><small>Open module <i class="bi bi-arrow-up-right"></i></small>
        </a>
    <?php endforeach; ?>
</div>
<div class="admin-columns">
    <section class="panel">
        <div class="panel-heading"><h2>Content activity</h2><span class="panel-note">Last six months</span></div>
        <canvas id="appsChart" height="140"></canvas>
    </section>
    <section class="panel"><div class="panel-heading"><h2>Recent events</h2><a class="text-link" href="<?= e(url('admin/events')) ?>">Manage events <i class="bi bi-arrow-right"></i></a></div><div class="recent-news-grid">
    <?php foreach ($recentEvents as $item): ?><a class="recent-news-card" href="<?= e(url('admin/events/' . $item['id'] . '/edit')) ?>"><img src="<?= e(media_url($item['image'])) ?>" alt="" width="90" height="60" loading="lazy"><span><strong><?= e($item['title']) ?></strong><small><?= e(ucfirst($item['status'])) ?> · <?= e(format_date($item['starts_at'])) ?></small></span></a><?php endforeach; ?>
    </div></section>
    <section class="panel">
        <div class="panel-heading"><h2>Recent news publications</h2><a class="text-link" href="<?= e(url('admin/news')) ?>">Manage news <i class="bi bi-arrow-right"></i></a></div>
        <div class="recent-news-grid">
            <?php foreach ($recentNews as $item): ?>
                <a class="recent-news-card" href="<?= e(url('admin/news/' . $item['id'] . '/edit')) ?>">
                    <img src="<?= e(media_url($item['image'])) ?>" alt="" width="90" height="60" loading="lazy">
                    <span><strong><?= e($item['title']) ?></strong><small><?= e(ucfirst($item['status'])) ?> · <?= e(format_date($item['published_at'])) ?></small></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="panel">
        <h2>Latest applications</h2>
        <div class="table-wrap">
        <table>
            <tbody>
            <?php foreach ($recentApplications as $row): ?>
                <tr>
                    <td><a href="<?= e(url('admin/applications/' . $row['id'] . '/edit')) ?>"><?= e($row['reference_no']) ?></a><br><small><?= e($row['full_name']) ?></small></td>
                    <td><?= e($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </section>
</div>
<section class="panel">
    <h2>Recent activity</h2>
    <div class="table-wrap">
    <table>
        <tbody>
        <?php foreach ($activity as $log): ?>
            <tr>
                <td><?= e($log['user_name'] ?: 'System') ?><br><small><?= e($log['action']) ?></small></td>
                <td><?= e($log['description']) ?></td>
                <td><?= e(format_date($log['created_at'], 'd M H:i')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <p class="form-actions quick-actions">
        <a class="button" href="<?= e(url('admin/news/create')) ?>">New article</a>
        <a class="button button-secondary" href="<?= e(url('admin/events/create')) ?>">New event</a>
        <a class="button" href="<?= e(url('admin/applications')) ?>">Review applications</a>
    </p>
</section>
<section class="panel">
    <h2>Recent messages</h2>
        <div class="table-wrap">
        <table class="data-table">
        <thead><tr><th>From</th><th>Subject</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recentMessages as $row): ?>
            <tr>
                <td><a href="<?= e(url('admin/messages/' . $row['id'] . '/edit')) ?>"><?= e($row['name']) ?></a></td>
                <td><?= e($row['subject']) ?></td>
                <td><?= e($row['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
        </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('appsChart');
    if (!canvas || !window.Chart) return;
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Applications',
                data: <?= json_encode($chartSeries) ?>,
                borderColor: '#EF233C',
                backgroundColor: 'rgba(239,35,60,0.12)',
                fill: true,
                tension: 0.35
            }, {
                label: 'News',
                data: <?= json_encode($newsSeries) ?>,
                borderColor: '#104398',
                backgroundColor: 'rgba(16,67,152,0.08)',
                fill: true,
                tension: 0.35
            }]
        },
        options: {plugins: {legend: {display: false}}, scales: {y: {beginAtZero: true, ticks: {precision: 0}}}}
    });
});
</script>
