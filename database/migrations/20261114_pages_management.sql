ALTER TABLE pages
    ADD COLUMN category VARCHAR(80) NULL AFTER slug,
    ADD COLUMN image VARCHAR(255) NULL AFTER body,
    ADD COLUMN image_alt VARCHAR(180) NULL AFTER image,
    ADD COLUMN parent_id INT UNSIGNED NULL AFTER image_alt,
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER parent_id,
    ADD COLUMN sort_order INT NOT NULL DEFAULT 0 AFTER is_featured,
    ADD COLUMN canonical_url VARCHAR(255) NULL AFTER meta_description,
    ADD COLUMN og_title VARCHAR(180) NULL AFTER canonical_url,
    ADD COLUMN og_description VARCHAR(320) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN twitter_image VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN robots_index VARCHAR(20) NOT NULL DEFAULT 'index' AFTER twitter_image,
    ADD COLUMN robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER robots_follow,
    ADD COLUMN structured_data JSON NULL AFTER focus_keyword,
    ADD INDEX idx_pages_listing (status, parent_id, sort_order);

UPDATE pages
SET category = COALESCE(NULLIF(category, ''), 'General'),
    image_alt = title,
    meta_title = COALESCE(NULLIF(meta_title, ''), CONCAT(title, ' | SLGTI')),
    meta_description = COALESCE(NULLIF(meta_description, ''), LEFT(excerpt, 255)),
    updated_at = NOW();
