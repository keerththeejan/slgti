ALTER TABLE quick_links
    ADD COLUMN category VARCHAR(80) NOT NULL DEFAULT 'General' AFTER subtitle,
    ADD COLUMN link_type VARCHAR(20) NOT NULL DEFAULT 'internal' AFTER category,
    ADD COLUMN target VARCHAR(20) NOT NULL DEFAULT '_self' AFTER url,
    ADD COLUMN display_style VARCHAR(20) NOT NULL DEFAULT 'card' AFTER target,
    ADD COLUMN display_location VARCHAR(40) NOT NULL DEFAULT 'homepage' AFTER display_style,
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER display_location,
    ADD COLUMN created_at DATETIME NULL AFTER status,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD INDEX idx_quick_links_display (status, display_location, sort_order),
    ADD INDEX idx_quick_links_category (category);

UPDATE quick_links
SET category = CASE
        WHEN title = 'Apply Now' THEN 'Admissions'
        WHEN title IN ('Student Information System', 'E-Learning') THEN 'Student Services'
        ELSE 'General'
    END,
    link_type = CASE WHEN url LIKE 'http://%' OR url LIKE 'https://%' THEN 'external' ELSE 'internal' END,
    target = CASE WHEN url LIKE 'http://%' OR url LIKE 'https://%' THEN '_blank' ELSE '_self' END,
    display_style = 'card',
    display_location = 'homepage_footer',
    is_featured = 0,
    created_at = COALESCE(created_at, NOW()),
    updated_at = NOW();
