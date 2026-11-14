<?php
$query = static fn (int $id): string => url('admin/menu-items?menu=' . $id);
$renderItems = function (array $branch) use (&$renderItems): void {
    foreach ($branch as $item): ?>
        <li class="menu-tree-item" data-id="<?= (int) $item['id'] ?>">
            <div class="menu-item-row">
                <button class="drag-handle" type="button" aria-label="Drag <?= e($item['label']) ?> to reorder"><i class="bi bi-grip-vertical"></i></button>
                <div class="menu-item-info">
                    <strong><?= e($item['label']) ?></strong>
                    <small><?= e($item['url']) ?></small>
                </div>
                <span class="menu-status <?= $item['status'] === 'published' ? 'is-published' : '' ?>"><?= e(ucfirst($item['status'])) ?></span>
                <?php if ($item['children']): ?><span class="menu-child-count"><?= count($item['children']) ?> child<?= count($item['children']) === 1 ? '' : 'ren' ?></span><?php endif; ?>
                <a class="menu-action" href="<?= e(url('admin/menu-items/' . $item['id'] . '/edit')) ?>">Edit</a>
                <form method="post" action="<?= e(url('admin/menu-items/' . $item['id'] . '/delete')) ?>" data-confirm="<?= e($item['children'] ? 'This item has child items. Deleting it will also delete its children. Continue?' : 'Delete this menu item?') ?>">
                    <?= csrf_field() ?><button class="menu-action danger" type="submit">Delete</button>
                </form>
            </div>
            <ol class="menu-tree-list" data-parent-id="<?= (int) $item['id'] ?>"><?php $renderItems($item['children']); ?></ol>
        </li>
    <?php endforeach;
};
?>
<div class="menu-page-header">
    <div><p class="eyebrow">Website navigation</p><h1>Menu Items</h1><p class="admin-lead">Organise the public navigation without changing existing links or content.</p></div>
    <a class="button" href="<?= e(url('admin/menu-items/create')) ?>"><i class="bi bi-plus-lg"></i> Add menu item</a>
</div>
<div class="menu-toolbar">
    <label>Menu
        <select id="menuSelect" onchange="window.location=this.value">
            <?php foreach ($menus as $menu): ?><option value="<?= e($query((int) $menu['id'])) ?>" <?= (int) $menu['id'] === $menuId ? 'selected' : '' ?>><?= e($menu['name']) ?> (<?= e($menu['slug']) ?>)</option><?php endforeach; ?>
        </select>
    </label>
    <label class="menu-filter">Filter items <input id="menuFilter" type="search" placeholder="Search labels or URLs"></label>
    <button class="button" id="saveMenuOrder" type="button" <?= !$items ? 'disabled' : '' ?>><i class="bi bi-check2"></i> Save order</button>
</div>
<div class="menu-layout">
    <section class="panel menu-tree-panel">
        <div class="panel-heading"><h2>Navigation hierarchy</h2><span class="panel-note"><?= count($items) ?> items · drag to reorder</span></div>
        <div id="menuFeedback" class="menu-feedback" role="status" hidden></div>
        <ol class="menu-tree-list menu-root" data-parent-id="0">
            <?php $renderItems($tree); ?>
        </ol>
        <?php if (!$items): ?><div class="menu-empty"><i class="bi bi-menu-button-wide"></i><h3>No menu items found</h3><p>Add an item to start building this menu.</p></div><?php endif; ?>
    </section>
    <aside class="panel menu-preview-panel">
        <div class="panel-heading"><h2>Live preview</h2><span class="panel-note">Published items</span></div>
        <nav class="menu-preview" aria-label="Menu preview">
            <ul id="menuPreviewList">
                <?php
                $renderPreview = function (array $branch) use (&$renderPreview): void {
                    foreach ($branch as $item):
                        if ($item['status'] !== 'published') {
                            continue;
                        } ?>
                        <li><a href="<?= e(nav_href($item['url'])) ?>"><?= e($item['label']) ?><?= $item['children'] ? ' ›' : '' ?></a>
                            <?php if ($item['children']): ?><ul><?php $renderPreview($item['children']); ?></ul><?php endif; ?>
                        </li>
                    <?php endforeach;
                };
                $renderPreview($tree);
                ?>
            </ul>
        </nav>
        <p class="menu-preview-note">The public site reads published items from this menu automatically.</p>
    </aside>
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('.menu-root');
    const feedback = document.getElementById('menuFeedback');
    const save = document.getElementById('saveMenuOrder');
    const filter = document.getElementById('menuFilter');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const lists = () => document.querySelectorAll('.menu-tree-list');
    lists().forEach((list) => new Sortable(list, {
        group: 'slgti-menu',
        animation: 150,
        handle: '.drag-handle',
        fallbackOnBody: true,
        swapThreshold: .65,
        onEnd: () => { save.disabled = false; }
    }));
    const serialize = () => {
        const output = [];
        const walk = (list, parentId) => {
            [...list.children].forEach((item, index) => {
                const id = Number(item.dataset.id);
                output.push({id, parent_id: parentId, sort_order: index + 1});
                const children = item.querySelector(':scope > .menu-tree-list');
                if (children) walk(children, id);
            });
        };
        walk(root, 0);
        return output;
    };
    save?.addEventListener('click', async () => {
        save.disabled = true;
        feedback.hidden = false;
        feedback.className = 'menu-feedback is-loading';
        feedback.textContent = 'Saving menu order...';
        try {
            const response = await fetch(<?= json_encode(url('admin/menu-items/reorder')) ?>, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-Token': token, 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({menu_id: <?= (int) $menuId ?>, items: serialize()})
            });
            const result = await response.json();
            if (!response.ok || !result.ok) throw new Error(result.message || 'Could not save order.');
            feedback.className = 'menu-feedback is-success';
            feedback.textContent = result.message;
        } catch (error) {
            feedback.className = 'menu-feedback is-error';
            feedback.textContent = error.message;
            save.disabled = false;
        }
    });
    filter?.addEventListener('input', () => {
        const query = filter.value.trim().toLowerCase();
        document.querySelectorAll('.menu-tree-item').forEach((item) => {
            item.hidden = query !== '' && !item.textContent.toLowerCase().includes(query);
        });
    });
});
</script>
