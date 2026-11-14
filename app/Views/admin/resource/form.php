<?php
$locked = !can($config['permission']);
$action = $record
    ? url('admin/' . $resource . '/' . $record['id'])
    : url('admin/' . $resource);
?>
<form class="panel form-grid" id="<?= $resource === 'sliders' ? 'sliderForm' : (in_array($resource, ['departments', 'programmes', 'requirements'], true) ? 'contentSeoForm' : '') ?>" method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if ($record && $resource === 'applications'):
        $documents = \App\Core\Model::fetchAll('SELECT * FROM application_documents WHERE application_id = ?', [(int) $record['id']]);
        if ($documents): ?>
            <div class="record-details">
                <?php foreach ($documents as $document): ?>
                    <div><dt>Document</dt><dd><a href="<?= e(url('admin/applications/documents/' . $document['id'])) ?>"><?= e($document['original_name']) ?></a></dd></div>
                <?php endforeach; ?>
            </div>
        <?php endif; endif; ?>
    <?php if ($record && in_array($resource, ['applications', 'messages'], true)): ?>
        <dl class="record-details">
            <?php foreach ($record as $key => $value): if (in_array($key, ['admin_notes', 'status', 'password'], true)) continue; ?>
                <div><dt><?= e(ucwords(str_replace('_', ' ', $key))) ?></dt><dd><?= e((string) $value) ?></dd></div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php foreach ($config['fields'] as $name => $field):
        $type = $field['type'];
        $value = old($name, $record[$name] ?? '');
        if ($type === 'datetime' && $value) {
            $value = date('Y-m-d\TH:i', strtotime((string) $value));
        }
        ?>
        <label>
            <?= e($field['label']) ?>
            <?php if ($type === 'textarea' || $type === 'editor'): ?>
                <textarea name="<?= e($name) ?>" rows="<?= $type === 'editor' ? 12 : 4 ?>" class="<?= $type === 'editor' ? 'richtext' : '' ?>" <?= $locked ? 'readonly' : '' ?>><?= e((string) $value) ?></textarea>
            <?php elseif ($type === 'select'): ?>
                <select name="<?= e($name) ?>" <?= $locked ? 'disabled' : '' ?>>
                    <option value="">Select</option>
                    <?php if (!empty($field['options'])): ?>
                        <?php foreach ($field['options'] as $optionValue => $optionLabel): ?>
                            <option value="<?= e((string) $optionValue) ?>" <?= (string) $value === (string) $optionValue ? 'selected' : '' ?>><?= e($optionLabel) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($options[$name] ?? [] as $option): ?>
                            <option value="<?= (int) $option['id'] ?>" <?= (string) $value === (string) $option['id'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            <?php elseif ($type === 'checkbox'): ?>
                <input type="checkbox" name="<?= e($name) ?>" value="1" <?= (string) old($name, $record[$name] ?? 0) === '1' || old($name, $record[$name] ?? 0) === 1 ? 'checked' : '' ?>>
            <?php elseif ($type === 'file' || $type === 'slider_image' || $type === 'document'): ?>
                <?php if (!empty($record[$name]) && ($type === 'file' || $type === 'slider_image')): ?><img class="thumb" src="<?= e(media_url($record[$name])) ?>" alt="<?= e($record['image_alt'] ?? '') ?>"><?php endif; ?>
                <?php if (!empty($record[$name]) && $type === 'document'): ?><small>Current document: <a href="<?= e(media_url($record[$name])) ?>" target="_blank" rel="noopener">download</a></small><label><input type="checkbox" name="<?= e('remove_' . $name) ?>" value="1"> Remove current document</label><?php endif; ?>
                <?php $multiple = $resource === 'gallery-images' && !$record; $accept = $type === 'document' ? 'application/pdf' : 'image/jpeg,image/png,image/webp,image/gif'; ?>
                <?php if ($resource === 'gallery-images' && $name === 'image' && !$record): ?>
                    <div class="gallery-upload-dropzone" data-gallery-dropzone tabindex="0">
                        <i class="bi bi-cloud-arrow-up" aria-hidden="true"></i>
                        <strong>Drop images here or choose files</strong>
                        <small>JPG, PNG, WEBP or GIF · up to 5 MB each</small>
                    </div>
                <?php endif; ?>
                <input class="<?= $resource === 'gallery-images' && $name === 'image' ? 'gallery-image-upload-input' : '' ?>" type="file" name="<?= e($multiple ? $name . '[]' : $name) ?>" accept="<?= e($accept) ?>" <?= $multiple ? 'multiple' : '' ?>>
                <?php if ($resource === 'gallery-images' && $name === 'image' && !$record): ?><div class="gallery-upload-previews" data-gallery-previews></div><?php endif; ?>
            <?php elseif ($type === 'password'): ?>
                <input type="password" name="<?= e($name) ?>" autocomplete="new-password" placeholder="<?= $record ? 'Leave blank to keep the current password' : '' ?>">
            <?php else: ?>
                <input type="<?= e($type === 'number' ? 'number' : ($type === 'email' ? 'email' : ($type === 'url' ? 'url' : ($type === 'datetime' ? 'datetime-local' : ($type === 'date' ? 'date' : 'text'))))) ?>" name="<?= e($name) ?>" value="<?= e((string) $value) ?>" <?= $locked ? 'readonly' : '' ?>>
            <?php endif; ?>
            <?php if ($err = field_error($name)): ?><small class="error"><?= e($err) ?></small><?php endif; ?>
        </label>
    <?php endforeach; ?>
    <div class="form-actions">
        <?php if (!$locked): ?><button type="submit"><?= $record ? 'Save changes' : 'Create' ?></button><?php endif; ?>
        <a href="<?= e(url('admin/' . $resource)) ?>">Back</a>
    </div>
</form>
<?php if ($resource === 'gallery-images' && !$record): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('.gallery-image-upload-input');
    const dropzone = document.querySelector('[data-gallery-dropzone]');
    const previews = document.querySelector('[data-gallery-previews]');
    if (!input || !dropzone || !previews) return;
    const render = () => {
        previews.replaceChildren();
        Array.from(input.files || []).forEach((file) => {
            if (!file.type.startsWith('image/')) return;
            const item = document.createElement('div');
            item.className = 'gallery-upload-preview';
            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = file.name;
            const label = document.createElement('small');
            label.textContent = file.name;
            item.append(image, label);
            previews.append(item);
        });
    };
    const acceptFiles = (files) => {
        const transfer = new DataTransfer();
        Array.from(files).forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
        render();
    };
    input.addEventListener('change', render);
    ['dragenter', 'dragover'].forEach((event) => dropzone.addEventListener(event, (e) => {
        e.preventDefault();
        dropzone.classList.add('is-dragging');
    }));
    ['dragleave', 'drop'].forEach((event) => dropzone.addEventListener(event, (e) => {
        e.preventDefault();
        dropzone.classList.remove('is-dragging');
    }));
    dropzone.addEventListener('drop', (e) => acceptFiles(e.dataTransfer.files));
    dropzone.addEventListener('click', () => input.click());
    dropzone.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') input.click(); });
});
</script>
<?php endif; ?>
<?php if ($resource === 'sliders'): ?>
<section class="panel slider-live-preview" aria-labelledby="slider-preview-title">
    <div class="panel-heading"><h2 id="slider-preview-title">Live slide preview</h2><span class="panel-note">Updates as you edit the form</span></div>
    <div class="slider-preview-stage" id="sliderPreviewStage">
        <img id="sliderPreviewImage" src="<?= e(!empty($record['image']) ? media_url($record['image']) : '') ?>" alt="">
        <div class="slider-preview-overlay" id="sliderPreviewOverlay"></div>
        <div class="slider-preview-copy" id="sliderPreviewCopy">
            <small id="sliderPreviewEyebrow"></small><strong id="sliderPreviewTitle"></strong><span id="sliderPreviewSubtitle"></span>
            <div><button type="button" class="button" id="sliderPreviewButton">Primary action</button><button type="button" class="button button-secondary" id="sliderPreviewSecondary">Secondary action</button></div>
        </div>
        <span class="slider-preview-mobile-note" id="sliderPreviewMobile"></span>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('sliderForm');
    const field = (name) => form.querySelector(`[name="${name}"]`);
    const value = (name, fallback = '') => field(name)?.value.trim() || fallback;
    const preview = () => {
        const copy = document.getElementById('sliderPreviewCopy');
        document.getElementById('sliderPreviewEyebrow').textContent = value('eyebrow');
        document.getElementById('sliderPreviewTitle').textContent = value('title', 'Slide headline');
        document.getElementById('sliderPreviewSubtitle').textContent = value('subtitle', 'Supporting slide text');
        const primary = document.getElementById('sliderPreviewButton');
        const secondary = document.getElementById('sliderPreviewSecondary');
        primary.textContent = value('button_text', 'Primary action');
        secondary.textContent = value('secondary_text', 'Secondary action');
        primary.hidden = !value('button_text');
        secondary.hidden = !value('secondary_text');
        document.getElementById('sliderPreviewMobile').textContent = field('mobile_image')?.files?.length || <?= !empty($record['mobile_image']) ? 'true' : 'false' ?> ? 'Mobile image configured' : 'Mobile devices use the desktop image';
        copy.style.textAlign = value('text_alignment', 'left');
        copy.className = `slider-preview-copy slider-preview-${value('text_position', 'left')}`;
        const color = /^#[0-9a-f]{3,8}$/i.test(value('overlay_color', '#0B5CAB')) ? value('overlay_color', '#0B5CAB') : '#0B5CAB';
        const opacity = Math.max(0, Math.min(100, Number(value('overlay_opacity', '35')) || 0)) / 100;
        const overlay = document.getElementById('sliderPreviewOverlay');
        overlay.style.backgroundColor = color;
        overlay.style.opacity = opacity;
    };
    const imageInput = field('image');
    imageInput?.addEventListener('change', () => {
        const file = imageInput.files?.[0];
        if (file) document.getElementById('sliderPreviewImage').src = URL.createObjectURL(file);
    });
    form.querySelectorAll('input, textarea, select').forEach((input) => input.addEventListener('input', preview));
    preview();
});
</script>
<?php endif; ?>
<?php if (in_array($resource, ['departments', 'programmes', 'requirements'], true)): ?>
<section class="panel seo-analysis" aria-labelledby="seo-analysis-title">
    <div class="panel-heading"><h2 id="seo-analysis-title">SEO preview &amp; analysis</h2><button class="button button-secondary" type="button" id="seoGenerate">Fill safe suggestions</button></div>
    <div class="seo-preview"><div class="seo-preview-url" id="seoPreviewUrl"></div><div class="seo-preview-title" id="seoPreviewTitle"></div><div class="seo-preview-description" id="seoPreviewDescription"></div></div>
    <div class="seo-analysis-grid"><p>Title: <strong id="seoTitleCount">0</strong>/60</p><p>Description: <strong id="seoDescriptionCount">0</strong>/160</p><p>Focus keyword: <strong id="seoKeywordStatus">Not set</strong></p><p>Score: <strong id="seoScore">0</strong>/100</p></div>
    <ul class="seo-recommendations" id="seoRecommendations"></ul>
</section>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('departmentSeoForm');
    const duplicates = <?= json_encode(\App\Core\Model::fetchAll('SELECT id, seo_title, meta_description FROM ' . ($resource === 'programmes' ? 'programmes' : 'departments') . ' WHERE id != ?', [(int) ($record['id'] ?? 0)]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
    const field = (name) => form.querySelector(`[name="${name}"]`);
    const text = (name) => field(name)?.value.trim() || '';
    const title = field(<?= json_encode($resource === 'programmes' ? 'title' : ($resource === 'requirements' ? 'requirement' : 'name')) ?>);
    const slug = field('slug');
    const seoSlug = field('seo_slug');
    const seoTitle = field('seo_title');
    const description = field('description');
    const metaDescription = field('meta_description');
    const focus = field('focus_keyword');
    const preview = () => {
        const titleValue = text('seo_title') || text(<?= json_encode($resource === 'programmes' ? 'title' : ($resource === 'requirements' ? 'requirement' : 'name')) ?>) || 'Content title';
        const descriptionValue = text('meta_description') || text(<?= json_encode($resource === 'requirements' ? 'short_description' : 'summary') ?>) || 'Content description';
        const path = text('seo_slug') || text('slug') || 'department';
        const keyword = text('focus_keyword').toLowerCase();
        let score = 0;
        const recommendations = [];
        if (duplicates.some((item) => item.seo_title && item.seo_title.trim() === text('seo_title'))) recommendations.push('The SEO title duplicates another department.');
        if (duplicates.some((item) => item.meta_description && item.meta_description.trim() === text('meta_description'))) recommendations.push('The meta description duplicates another department.');
        if (text('seo_title')) score += 15; else recommendations.push('Add an SEO title.');
        if (titleValue.length >= 30 && titleValue.length <= 60) score += 15; else recommendations.push('Keep the SEO title between 30 and 60 characters.');
        if (text('meta_description')) score += 15; else recommendations.push('Add a meta description.');
        if (descriptionValue.length >= 100 && descriptionValue.length <= 160) score += 15; else recommendations.push('Keep the meta description between 100 and 160 characters.');
        if (text('seo_slug')) score += 10; else recommendations.push('Set an explicit SEO slug.');
        if (keyword && (titleValue + ' ' + descriptionValue).toLowerCase().includes(keyword)) score += 15; else if (keyword) recommendations.push('Use the focus keyword in the title or description.');
        if (text('og_image')) score += 5; else recommendations.push('Add an Open Graph image.');
        if (text('canonical_url')) score += 5; else recommendations.push('Add a canonical URL or use the generated canonical.');
        document.getElementById('seoPreviewUrl').textContent = `${window.SLGTI_BASE || ''}/<?= $resource ?>/` + path;
        document.getElementById('seoPreviewTitle').textContent = titleValue;
        document.getElementById('seoPreviewDescription').textContent = descriptionValue;
        document.getElementById('seoTitleCount').textContent = titleValue.length;
        document.getElementById('seoDescriptionCount').textContent = descriptionValue.length;
        document.getElementById('seoKeywordStatus').textContent = keyword && (titleValue + ' ' + descriptionValue).toLowerCase().includes(keyword) ? 'Detected' : 'Not detected';
        document.getElementById('seoScore').textContent = score;
        document.getElementById('seoRecommendations').innerHTML = recommendations.map((item) => `<li>${item}</li>`).join('');
    };
    document.getElementById('seoGenerate').addEventListener('click', () => {
        if ([seoTitle, metaDescription, seoSlug].some((input) => input?.value.trim()) && !window.confirm('Replace existing SEO values with safe suggestions?')) return;
        const name = text(<?= json_encode($resource === 'programmes' ? 'title' : ($resource === 'requirements' ? 'requirement' : 'name')) ?>);
        const summary = text('summary') || description?.value.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim() || '';
        seoTitle.value = `${name} | SLGTI`;
        metaDescription.value = summary.slice(0, 160);
        seoSlug.value = (slug.value || name).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
        field('og_title').value = seoTitle.value;
        field('og_description').value = metaDescription.value;
        preview();
    });
    form.querySelectorAll('input, textarea, select').forEach((input) => input.addEventListener('input', preview));
    preview();
});
</script>
<?php endif; ?>
