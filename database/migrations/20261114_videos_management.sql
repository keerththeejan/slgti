ALTER TABLE videos
    ADD COLUMN slug VARCHAR(180) NULL AFTER title,
    ADD COLUMN provider VARCHAR(20) NOT NULL DEFAULT 'youtube' AFTER video_url,
    ADD COLUMN video_id VARCHAR(120) NULL AFTER provider,
    ADD COLUMN embed_url VARCHAR(255) NULL AFTER video_id,
    ADD COLUMN thumbnail VARCHAR(255) NULL AFTER embed_url,
    ADD COLUMN duration VARCHAR(20) NULL AFTER thumbnail,
    ADD COLUMN video_date DATE NULL AFTER duration,
    ADD COLUMN location VARCHAR(180) NULL AFTER video_date,
    ADD COLUMN credits VARCHAR(180) NULL AFTER location,
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER credits,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD COLUMN seo_title VARCHAR(180) NULL AFTER updated_at,
    ADD COLUMN meta_description VARCHAR(320) NULL AFTER seo_title,
    ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER meta_description,
    ADD COLUMN canonical_url VARCHAR(255) NULL AFTER focus_keyword,
    ADD COLUMN og_title VARCHAR(180) NULL AFTER canonical_url,
    ADD COLUMN og_description VARCHAR(320) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN twitter_image VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN robots_index VARCHAR(20) NOT NULL DEFAULT 'index' AFTER twitter_image,
    ADD COLUMN robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD INDEX idx_videos_listing (status, sort_order, id),
    ADD UNIQUE KEY uq_videos_slug (slug);

UPDATE videos
SET slug = CONCAT('video-', id),
    provider = CASE WHEN video_url LIKE '%vimeo.com%' THEN 'vimeo' ELSE 'youtube' END,
    seo_title = CONCAT(title, ' | SLGTI'),
    meta_description = LEFT(COALESCE(description, title), 320),
    updated_at = NOW()
WHERE slug IS NULL OR slug = '';
