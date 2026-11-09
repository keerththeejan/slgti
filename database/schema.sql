CREATE DATABASE IF NOT EXISTS webslgti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webslgti;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS application_documents;
DROP TABLE IF EXISTS programme_requirements;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS login_attempts;
DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS gallery_images;
DROP TABLE IF EXISTS galleries;
DROP TABLE IF EXISTS videos;
DROP TABLE IF EXISTS testimonials;
DROP TABLE IF EXISTS sliders;
DROP TABLE IF EXISTS facilities;
DROP TABLE IF EXISTS faqs;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS menus;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS event_categories;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS news_categories;
DROP TABLE IF EXISTS programmes;
DROP TABLE IF EXISTS programme_levels;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_rp_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    department_id INT UNSIGNED NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE login_attempts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    email VARCHAR(180) NOT NULL DEFAULT '',
    attempted_at DATETIME NOT NULL,
    INDEX idx_login_ip (ip_address, attempted_at)
) ENGINE=InnoDB;

CREATE TABLE password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_reset_email (email)
) ENGINE=InnoDB;

CREATE TABLE departments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    summary TEXT NOT NULL,
    description MEDIUMTEXT NOT NULL,
    facilities MEDIUMTEXT NULL,
    image VARCHAR(255) NULL,
    icon VARCHAR(80) NULL,
    head_name VARCHAR(150) NULL,
    email VARCHAR(180) NULL,
    phone VARCHAR(40) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_departments_status (status, sort_order)
) ENGINE=InnoDB;

CREATE TABLE programme_levels (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE,
    qualification VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE programmes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT UNSIGNED NOT NULL,
    level_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    summary TEXT NOT NULL,
    description MEDIUMTEXT NOT NULL,
    duration VARCHAR(80) NOT NULL,
    entry_requirements MEDIUMTEXT NOT NULL,
    career_outcomes MEDIUMTEXT NULL,
    image VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_programmes_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT fk_programmes_level FOREIGN KEY (level_id) REFERENCES programme_levels(id),
    INDEX idx_programmes_status (status, sort_order)
) ENGINE=InnoDB;

CREATE TABLE programme_requirements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_id INT UNSIGNED NOT NULL,
    requirement VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_requirements_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE staff (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    position VARCHAR(150) NOT NULL,
    department_id INT UNSIGNED NULL,
    bio MEDIUMTEXT NULL,
    email VARCHAR(180) NULL,
    phone VARCHAR(40) NULL,
    image VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_staff_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE news_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    author_id INT UNSIGNED NULL,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt TEXT NOT NULL,
    body MEDIUMTEXT NOT NULL,
    image VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    meta_title VARCHAR(180) NULL,
    meta_description VARCHAR(255) NULL,
    tags VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_news_category FOREIGN KEY (category_id) REFERENCES news_categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_news_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_news_listing (status, published_at)
) ENGINE=InnoDB;

CREATE TABLE event_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt TEXT NOT NULL,
    body MEDIUMTEXT NOT NULL,
    venue VARCHAR(180) NULL,
    image VARCHAR(255) NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NULL,
    registration_url VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_category FOREIGN KEY (category_id) REFERENCES event_categories(id) ON DELETE SET NULL,
    INDEX idx_events_start (status, starts_at)
) ENGINE=InnoDB;

CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    excerpt TEXT NULL,
    body MEDIUMTEXT NOT NULL,
    meta_title VARCHAR(180) NULL,
    meta_description VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE menus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_id INT UNSIGNED NOT NULL,
    parent_id INT UNSIGNED NULL,
    label VARCHAR(150) NOT NULL,
    url VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    CONSTRAINT fk_menu_items_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    CONSTRAINT fk_menu_items_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    INDEX idx_menu_order (menu_id, sort_order)
) ENGINE=InnoDB;

CREATE TABLE faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer MEDIUMTEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published'
) ENGINE=InnoDB;

CREATE TABLE facilities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    summary TEXT NOT NULL,
    image VARCHAR(255) NULL,
    icon VARCHAR(80) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published'
) ENGINE=InnoDB;

CREATE TABLE sliders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eyebrow VARCHAR(150) NULL,
    title VARCHAR(220) NOT NULL,
    subtitle TEXT NOT NULL,
    title_highlight VARCHAR(180) NULL,
    image VARCHAR(255) NULL,
    button_text VARCHAR(80) NULL,
    button_url VARCHAR(255) NULL,
    secondary_text VARCHAR(80) NULL,
    secondary_url VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published'
) ENGINE=InnoDB;

CREATE TABLE quick_links (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    subtitle VARCHAR(180) NOT NULL,
    icon VARCHAR(80) NOT NULL,
    color VARCHAR(20) NOT NULL DEFAULT '#0B5CAB',
    url VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published'
) ENGINE=InnoDB;

CREATE TABLE testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    role VARCHAR(180) NOT NULL,
    quote TEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published'
) ENGINE=InnoDB;

CREATE TABLE videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    description TEXT NULL,
    video_url VARCHAR(255) NOT NULL,
    category VARCHAR(80) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE galleries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    cover VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE gallery_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT UNSIGNED NOT NULL,
    title VARCHAR(180) NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    CONSTRAINT fk_gallery_images FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference_no VARCHAR(40) NOT NULL UNIQUE,
    full_name VARCHAR(180) NOT NULL,
    nic VARCHAR(20) NOT NULL,
    email VARCHAR(180) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    address VARCHAR(255) NOT NULL,
    district VARCHAR(120) NOT NULL,
    programme_id INT UNSIGNED NULL,
    education_level VARCHAR(80) NOT NULL,
    results TEXT NOT NULL,
    message TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    admin_notes TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_applications_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE SET NULL,
    INDEX idx_applications_status (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE application_documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    original_name VARCHAR(180) NOT NULL,
    mime VARCHAR(80) NOT NULL,
    size INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_documents_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    reply_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_messages_status (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(180) NOT NULL,
    original_name VARCHAR(180) NULL,
    path VARCHAR(255) NOT NULL,
    mime VARCHAR(80) NOT NULL,
    size INT UNSIGNED NOT NULL DEFAULT 0,
    uploaded_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(80) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_group VARCHAR(40) NOT NULL DEFAULT 'general',
    label VARCHAR(150) NOT NULL,
    field_type VARCHAR(20) NOT NULL DEFAULT 'text'
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(80) NOT NULL,
    description VARCHAR(500) NOT NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_logs_created (created_at)
) ENGINE=InnoDB;
