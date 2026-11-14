<div class="news-admin-header">
    <div><p class="eyebrow">Institutional calendar</p><h1>Events Management</h1><p class="admin-lead">Publish and maintain campus events and activities.</p></div>
    <?php if (can($config['permission'])): ?><a class="button" href="<?= e(url('admin/events/create')) ?>"><i class="bi bi-plus-lg"></i> Add event</a><?php endif; ?>
</div>
<form class="panel news-filters" method="get">
    <label class="news-filter-search">Search <input type="search" name="q" value="<?= e($q) ?>" placeholder="Title, description or venue"></label>
    <label>Status <select name="status"><option value="">All statuses</option><?php foreach ($config['status_filter'] as $option): ?><option value="<?= e($option) ?>" <?= $status === $option ? 'selected' : '' ?>><?= e(ucfirst($option)) ?></option><?php endforeach; ?></select></label>
    <label>Category <select name="category_id"><option value="">All categories</option><?php foreach ($categories as $item): ?><option value="<?= (int) $item['id'] ?>" <?= $category === (int) $item['id'] ? 'selected' : '' ?>><?= e($item['name']) ?></option><?php endforeach; ?></select></label>
    <label>Date state <select name="date_state"><option value="">All dates</option><?php foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $key => $label): ?><option value="<?= $key ?>" <?= $dateState === $key ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></label>
    <label>From <input type="date" name="from" value="<?= e($from) ?>"></label><label>To <input type="date" name="to" value="<?= e($to) ?>"></label>
    <button class="button" type="submit"><i class="bi bi-funnel"></i> Filter</button><a class="button button-secondary" href="<?= e(url('admin/events')) ?>">Reset</a>
</form>
<section class="panel"><div class="panel-heading"><h2>All events</h2><span class="panel-note"><?= (int) $pager['total'] ?> records</span></div>
<div class="table-wrap"><table class="data-table news-admin-table"><thead><tr><th>Event</th><th>Category</th><th>When</th><th>Venue</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($rows as $row):
    $now = time(); $start = strtotime($row['starts_at']); $end = strtotime($row['ends_at'] ?: $row['starts_at']);
    $stateLabel = $start > $now ? 'Upcoming' : ($end >= $now ? 'Ongoing' : 'Completed');
?>
<tr><td class="news-story-cell"><img src="<?= e(media_url($row['image'])) ?>" alt="" width="72" height="48" loading="lazy"><div><strong><?= e($row['title']) ?></strong><small><?= e($row['excerpt']) ?></small></div></td><td><?= e($row['category_name'] ?: 'Event') ?></td><td><strong><?= e($stateLabel) ?></strong><br><?= e(format_date($row['starts_at'], 'd M Y, h:i A')) ?></td><td><?= e($row['venue'] ?: '—') ?></td><td><span class="news-status status-<?= e($row['status']) ?>"><?= e(ucfirst($row['status'])) ?></span></td><td class="actions"><a href="<?= e(url('admin/events/' . $row['id'] . '/edit')) ?>">Edit</a><?php if ($row['status'] === 'published'): ?><a href="<?= e(url('events/' . $row['slug'])) ?>" target="_blank" rel="noopener">View</a><?php endif; ?><?php if (can($config['permission'])): ?><form method="post" action="<?= e(url('admin/events/' . $row['id'] . '/delete')) ?>" data-confirm="Delete this event?"><?= csrf_field() ?><button type="submit">Delete</button></form><?php endif; ?></td></tr>
<?php endforeach; ?>
<?php if (!$rows): ?><tr><td colspan="6"><div class="empty-state">No events match the selected filters.</div></td></tr><?php endif; ?>
</tbody></table></div></section>
<?= render_pagination($pager, '/admin/events', array_filter(['q' => $q, 'status' => $status, 'category_id' => $category, 'date_state' => $dateState, 'from' => $from, 'to' => $to])) ?>
