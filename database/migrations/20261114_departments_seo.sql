ALTER TABLE departments
    ADD COLUMN seo_title VARCHAR(180) NULL AFTER phone,
    ADD COLUMN meta_description VARCHAR(320) NULL AFTER seo_title,
    ADD COLUMN meta_keywords VARCHAR(320) NULL AFTER meta_description,
    ADD COLUMN seo_slug VARCHAR(180) NULL AFTER meta_keywords,
    ADD COLUMN og_title VARCHAR(180) NULL AFTER seo_slug,
    ADD COLUMN og_description VARCHAR(320) NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description,
    ADD COLUMN twitter_image VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN robots_index VARCHAR(10) NOT NULL DEFAULT 'index' AFTER twitter_image,
    ADD COLUMN robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow' AFTER robots_index,
    ADD COLUMN canonical_url VARCHAR(500) NULL AFTER robots_follow,
    ADD COLUMN structured_data JSON NULL AFTER canonical_url,
    ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER structured_data,
    ADD UNIQUE KEY uq_departments_seo_slug (seo_slug);

CREATE TABLE department_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(180) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_department_slug_redirect_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB;
