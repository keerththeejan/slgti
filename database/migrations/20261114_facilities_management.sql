ALTER TABLE facilities
    ADD COLUMN slug VARCHAR(180) NULL AFTER title,
    ADD COLUMN category VARCHAR(80) NULL AFTER slug,
    ADD COLUMN description MEDIUMTEXT NULL AFTER summary,
    ADD COLUMN location VARCHAR(180) NULL AFTER description,
    ADD COLUMN capacity VARCHAR(80) NULL AFTER location,
    ADD COLUMN equipment TEXT NULL AFTER capacity,
    ADD COLUMN availability_status VARCHAR(30) NOT NULL DEFAULT 'available' AFTER equipment,
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER availability_status,
    ADD COLUMN display_location VARCHAR(40) NOT NULL DEFAULT 'facilities' AFTER is_featured,
    ADD COLUMN image_alt VARCHAR(180) NULL AFTER image,
    ADD COLUMN updated_at DATETIME NULL AFTER status,
    ADD COLUMN seo_title VARCHAR(180) NULL AFTER updated_at,
    ADD COLUMN meta_description VARCHAR(320) NULL AFTER seo_title,
    ADD COLUMN canonical_url VARCHAR(255) NULL AFTER meta_description,
    ADD COLUMN og_title VARCHAR(180) NULL AFTER canonical_url,
    ADD COLUMN og_description VARCHAR(320) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN robots_index VARCHAR(20) NOT NULL DEFAULT 'index' AFTER og_image,
    ADD COLUMN robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD INDEX idx_facilities_listing (status, availability_status, sort_order);

UPDATE facilities
SET slug = CONCAT('facility-', id),
    category = COALESCE(NULLIF(category, ''), 'Campus facility'),
    description = COALESCE(NULLIF(description, ''), summary),
    image_alt = title,
    display_location = 'facilities',
    updated_at = NOW(),
    seo_title = CONCAT(title, ' | SLGTI'),
    meta_description = LEFT(COALESCE(description, summary, title), 320)
WHERE slug IS NULL OR slug = '';
