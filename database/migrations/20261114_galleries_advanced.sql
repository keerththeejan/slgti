ALTER TABLE galleries
    ADD COLUMN category VARCHAR(100) NULL AFTER description,
    ADD COLUMN event_id INT UNSIGNED NULL AFTER category,
    ADD COLUMN gallery_date DATE NULL AFTER event_id,
    ADD COLUMN location VARCHAR(180) NULL AFTER gallery_date,
    ADD COLUMN photographer VARCHAR(180) NULL AFTER location,
    ADD COLUMN seo_title VARCHAR(180) NULL AFTER status,
    ADD COLUMN meta_description VARCHAR(255) NULL AFTER seo_title,
    ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER meta_description,
    ADD COLUMN canonical_url VARCHAR(255) NULL AFTER focus_keyword,
    ADD COLUMN og_title VARCHAR(180) NULL AFTER canonical_url,
    ADD COLUMN og_description VARCHAR(255) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN twitter_image VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN robots_index VARCHAR(20) NOT NULL DEFAULT 'index' AFTER twitter_image,
    ADD COLUMN robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD COLUMN structured_data TEXT NULL AFTER robots_follow,
    ADD INDEX idx_galleries_event (event_id),
    ADD INDEX idx_galleries_category (category);

ALTER TABLE gallery_images
    ADD COLUMN credits VARCHAR(180) NULL AFTER alt_text;

CREATE TABLE gallery_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(150) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_gallery_slug_redirect_gallery FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
) ENGINE=InnoDB;

UPDATE galleries
SET seo_title = COALESCE(NULLIF(seo_title, ''), CONCAT(title, ' | SLGTI')),
    meta_description = COALESCE(NULLIF(meta_description, ''), LEFT(COALESCE(description, title), 255)),
    canonical_url = COALESCE(NULLIF(canonical_url, ''), CONCAT('http://localhost/slgti/gallery/', slug)),
    robots_index = COALESCE(NULLIF(robots_index, ''), 'index'),
    robots_follow = COALESCE(NULLIF(robots_follow, ''), 'follow'),
    updated_at = NOW();
