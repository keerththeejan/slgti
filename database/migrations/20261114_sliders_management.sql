ALTER TABLE sliders
    ADD COLUMN mobile_image VARCHAR(255) NULL AFTER image,
    ADD COLUMN image_alt VARCHAR(180) NULL AFTER mobile_image,
    ADD COLUMN text_alignment VARCHAR(20) NOT NULL DEFAULT 'left' AFTER secondary_url,
    ADD COLUMN text_position VARCHAR(20) NOT NULL DEFAULT 'left' AFTER text_alignment,
    ADD COLUMN overlay_color VARCHAR(20) NOT NULL DEFAULT '#0B5CAB' AFTER text_position,
    ADD COLUMN overlay_opacity TINYINT UNSIGNED NOT NULL DEFAULT 35 AFTER overlay_color,
    ADD COLUMN display_location VARCHAR(40) NOT NULL DEFAULT 'homepage' AFTER overlay_opacity,
    ADD COLUMN starts_at DATETIME NULL AFTER status,
    ADD COLUMN ends_at DATETIME NULL AFTER starts_at,
    ADD COLUMN created_at DATETIME NULL AFTER ends_at,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD INDEX idx_sliders_schedule (status, display_location, starts_at, ends_at, sort_order);

UPDATE sliders
SET image_alt = COALESCE(NULLIF(image_alt, ''), title),
    text_alignment = COALESCE(NULLIF(text_alignment, ''), 'left'),
    text_position = COALESCE(NULLIF(text_position, ''), 'left'),
    overlay_color = COALESCE(NULLIF(overlay_color, ''), '#0B5CAB'),
    overlay_opacity = COALESCE(overlay_opacity, 35),
    display_location = COALESCE(NULLIF(display_location, ''), 'homepage'),
    created_at = COALESCE(created_at, NOW()),
    updated_at = NOW();
