<div class="stat-grid">
    <article><i class="bi bi-newspaper stat-icon" aria-hidden="true"></i><span>Published news</span><strong><?= (int) $counts['news'] ?></strong></article>
    <article><i class="bi bi-building stat-icon" aria-hidden="true"></i><span>Departments</span><strong><?= (int) $counts['departments'] ?></strong></article>
    <article><i class="bi bi-images stat-icon" aria-hidden="true"></i><span>Gallery albums</span><strong><?= (int) $counts['albums'] ?></strong></article>
    <article><i class="bi bi-people stat-icon" aria-hidden="true"></i><span>Staff profiles</span><strong><?= (int) $counts['staff'] ?></strong></article>
    <article><i class="bi bi-calendar-event stat-icon" aria-hidden="true"></i><span>Upcoming content</span><strong><?= (int) $counts['events'] ?></strong></article>
    <article><i class="bi bi-mortarboard stat-icon" aria-hidden="true"></i><span>Programmes</span><strong><?= (int) $counts['programmes'] ?></strong></article>
    <article><i class="bi bi-file-earmark-text stat-icon" aria-hidden="true"></i><span>Applications</span><strong><?= (int) $counts['applications'] ?></strong></article>
    <article><i class="bi bi-hourglass-split stat-icon" aria-hidden="true"></i><span>Pending review</span><strong><?= (int) $counts['pending'] ?></strong></article>
    <article><i class="bi bi-envelope stat-icon" aria-hidden="true"></i><span>New messages</span><strong><?= (int) $counts['messages'] ?></strong></article>
</div>
<div class="admin-columns">
    <section class="panel">
        <h2>Applications, last six months</h2>
        <canvas id="appsChart" height="140"></canvas>
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
    <p class="form-actions">
        <a class="button" href="<?= e(url('admin/news/create')) ?>">New article</a>
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
            }]
        },
        options: {plugins: {legend: {display: false}}, scales: {y: {beginAtZero: true, ticks: {precision: 0}}}}
    });
});
</script>
