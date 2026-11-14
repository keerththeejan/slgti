<?php
$monthStart = strtotime($month . '-01');
$days = (int) date('t', $monthStart);
$offset = (int) date('N', $monthStart) - 1;
$previous = date('Y-m', strtotime('-1 month', $monthStart));
$next = date('Y-m', strtotime('+1 month', $monthStart));
$byDay = [];
foreach ($events as $event) {
    $day = (int) date('j', strtotime($event['starts_at']));
    $byDay[$day][] = $event;
}
$title = 'Events calendar';
$lead = date('F Y', $monthStart) . ' · SLGTI institutional events';
require APP_PATH . '/Views/partials/page-hero.php';
?>
<section class="section">
    <div class="container calendar-toolbar">
        <a class="button button-secondary" href="<?= e(url('events/calendar?month=' . $previous)) ?>"><i class="bi bi-chevron-left"></i> Previous</a>
        <strong><?= e(date('F Y', $monthStart)) ?></strong>
        <a class="button button-secondary" href="<?= e(url('events/calendar?month=' . $next)) ?>">Next <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="container event-calendar" aria-label="<?= e(date('F Y', $monthStart)) ?>">
        <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $weekday): ?><div class="calendar-weekday"><?= e($weekday) ?></div><?php endforeach; ?>
        <?php for ($cell = 0; $cell < $offset; $cell++): ?><div class="calendar-day is-empty"></div><?php endfor; ?>
        <?php for ($day = 1; $day <= $days; $day++): ?>
            <div class="calendar-day"><strong><?= $day ?></strong>
                <?php foreach ($byDay[$day] ?? [] as $event): ?><a href="<?= e(url('events/' . $event['slug'])) ?>"><?= e($event['title']) ?><small><?= e(format_date($event['starts_at'], 'h:i A')) ?></small></a><?php endforeach; ?>
            </div>
        <?php endfor; ?>
    </div>
</section>
