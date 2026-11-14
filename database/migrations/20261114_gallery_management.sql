ALTER TABLE galleries
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER cover,
    ADD COLUMN display_location VARCHAR(40) NOT NULL DEFAULT 'gallery' AFTER is_featured,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD INDEX idx_galleries_listing (status, display_location, sort_order);

ALTER TABLE gallery_images
    ADD COLUMN alt_text VARCHAR(180) NULL AFTER image,
    ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER status,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD INDEX idx_gallery_images_listing (gallery_id, status, sort_order);

UPDATE galleries
SET display_location = COALESCE(NULLIF(display_location, ''), 'gallery'),
    is_featured = COALESCE(is_featured, 0),
    updated_at = NOW();

UPDATE gallery_images
SET alt_text = COALESCE(NULLIF(alt_text, ''), title),
    updated_at = NOW();
