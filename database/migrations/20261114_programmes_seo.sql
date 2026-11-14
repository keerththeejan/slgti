ALTER TABLE programmes
    ADD COLUMN brochure_path VARCHAR(255) NULL AFTER image,
    ADD COLUMN seo_title VARCHAR(200) NULL AFTER brochure_path,
    ADD COLUMN meta_description VARCHAR(320) NULL AFTER seo_title,
    ADD COLUMN meta_keywords VARCHAR(320) NULL AFTER meta_description,
    ADD COLUMN seo_slug VARCHAR(200) NULL AFTER meta_keywords,
    ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER seo_slug,
    ADD COLUMN canonical_url VARCHAR(500) NULL AFTER focus_keyword,
    ADD COLUMN og_title VARCHAR(200) NULL AFTER canonical_url,
    ADD COLUMN og_description VARCHAR(320) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN twitter_image VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN robots_index VARCHAR(10) NOT NULL DEFAULT 'index' AFTER twitter_image,
    ADD COLUMN robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD COLUMN structured_data JSON NULL AFTER robots_follow,
    ADD UNIQUE KEY uq_programmes_seo_slug (seo_slug);

CREATE TABLE programme_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(200) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_programme_slug_redirect_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB;
