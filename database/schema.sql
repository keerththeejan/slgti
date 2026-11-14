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
DROP TABLE IF EXISTS application_functions;
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
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    meta_keywords VARCHAR(320) NULL,
    seo_slug VARCHAR(180) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(10) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow',
    canonical_url VARCHAR(500) NULL,
    structured_data JSON NULL,
    focus_keyword VARCHAR(120) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_departments_status (status, sort_order)
    ,UNIQUE KEY uq_departments_seo_slug (seo_slug)
) ENGINE=InnoDB;

CREATE TABLE department_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(180) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_department_slug_redirect_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE programme_levels (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE,
    qualification VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    short_description TEXT NULL,
    learning_objectives MEDIUMTEXT NULL,
    entry_requirements MEDIUMTEXT NULL,
    progression_pathways MEDIUMTEXT NULL,
    career_opportunities MEDIUMTEXT NULL,
    duration VARCHAR(80) NULL,
    image VARCHAR(255) NULL,
    icon VARCHAR(80) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    seo_slug VARCHAR(80) NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    focus_keyword VARCHAR(120) NULL,
    canonical_url VARCHAR(500) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(10) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow',
    structured_data JSON NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_programme_levels_seo_slug (seo_slug),
    INDEX idx_programme_levels_status_order (status, sort_order)
) ENGINE=InnoDB;

CREATE TABLE programme_level_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    level_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(80) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_programme_level_slug_redirect_level FOREIGN KEY (level_id) REFERENCES programme_levels(id) ON DELETE CASCADE
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
    brochure_path VARCHAR(255) NULL,
    seo_title VARCHAR(200) NULL,
    meta_description VARCHAR(320) NULL,
    meta_keywords VARCHAR(320) NULL,
    seo_slug VARCHAR(200) NULL,
    focus_keyword VARCHAR(120) NULL,
    canonical_url VARCHAR(500) NULL,
    og_title VARCHAR(200) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(10) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow',
    structured_data JSON NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_programmes_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT fk_programmes_level FOREIGN KEY (level_id) REFERENCES programme_levels(id),
    INDEX idx_programmes_status (status, sort_order),
    UNIQUE KEY uq_programmes_seo_slug (seo_slug)
) ENGINE=InnoDB;

CREATE TABLE programme_requirements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_id INT UNSIGNED NULL,
    requirement VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NULL UNIQUE,
    category VARCHAR(120) NULL,
    department_id INT UNSIGNED NULL,
    short_description TEXT NULL,
    description MEDIUMTEXT NULL,
    eligibility_criteria MEDIUMTEXT NULL,
    educational_qualifications MEDIUMTEXT NULL,
    required_documents MEDIUMTEXT NULL,
    application_instructions MEDIUMTEXT NULL,
    submission_method VARCHAR(180) NULL,
    application_deadline DATETIME NULL,
    contact_information VARCHAR(255) NULL,
    image VARCHAR(255) NULL,
    document_path VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    seo_title VARCHAR(255) NULL,
    meta_description VARCHAR(320) NULL,
    focus_keyword VARCHAR(120) NULL,
    canonical_url VARCHAR(500) NULL,
    og_title VARCHAR(255) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(10) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(10) NOT NULL DEFAULT 'follow',
    structured_data JSON NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_requirements_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE requirement_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    requirement_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_requirement_slug_redirect_requirement FOREIGN KEY (requirement_id) REFERENCES programme_requirements(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE programme_slug_redirects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_id INT UNSIGNED NOT NULL,
    old_slug VARCHAR(200) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_programme_slug_redirect_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE staff (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NULL,
    staff_id VARCHAR(60) NULL,
    position VARCHAR(150) NOT NULL,
    category VARCHAR(80) NULL,
    section VARCHAR(120) NULL,
    department_id INT UNSIGNED NULL,
    bio MEDIUMTEXT NULL,
    qualifications TEXT NULL,
    expertise TEXT NULL,
    employment_status VARCHAR(30) NOT NULL DEFAULT 'active',
    joining_date DATE NULL,
    email VARCHAR(180) NULL,
    phone VARCHAR(40) NULL,
    office_location VARCHAR(180) NULL,
    profile_url VARCHAR(255) NULL,
    image VARCHAR(255) NULL,
    image_alt VARCHAR(180) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    display_location VARCHAR(40) NOT NULL DEFAULT 'directory',
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    robots_index VARCHAR(20) NOT NULL DEFAULT 'noindex',
    robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow',
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
    category VARCHAR(80) NULL,
    slug VARCHAR(180) NULL UNIQUE,
    answer MEDIUMTEXT NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_faq_listing (status, category, sort_order)
) ENGINE=InnoDB;

CREATE TABLE facilities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NULL,
    summary TEXT NOT NULL,
    category VARCHAR(80) NULL,
    description MEDIUMTEXT NULL,
    location VARCHAR(180) NULL,
    capacity VARCHAR(80) NULL,
    equipment TEXT NULL,
    availability_status VARCHAR(30) NOT NULL DEFAULT 'available',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    display_location VARCHAR(40) NOT NULL DEFAULT 'facilities',
    image VARCHAR(255) NULL,
    image_alt VARCHAR(180) NULL,
    icon VARCHAR(80) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    updated_at DATETIME NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    robots_index VARCHAR(20) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow',
    INDEX idx_facilities_listing (status, availability_status, sort_order)
) ENGINE=InnoDB;

CREATE TABLE sliders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eyebrow VARCHAR(150) NULL,
    title VARCHAR(220) NOT NULL,
    subtitle TEXT NOT NULL,
    title_highlight VARCHAR(180) NULL,
    image VARCHAR(255) NULL,
    mobile_image VARCHAR(255) NULL,
    image_alt VARCHAR(180) NULL,
    button_text VARCHAR(80) NULL,
    button_url VARCHAR(255) NULL,
    secondary_text VARCHAR(80) NULL,
    secondary_url VARCHAR(255) NULL,
    text_alignment VARCHAR(20) NOT NULL DEFAULT 'left',
    text_position VARCHAR(20) NOT NULL DEFAULT 'left',
    overlay_color VARCHAR(20) NOT NULL DEFAULT '#0B5CAB',
    overlay_opacity TINYINT UNSIGNED NOT NULL DEFAULT 35,
    display_location VARCHAR(40) NOT NULL DEFAULT 'homepage',
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    INDEX idx_sliders_schedule (status, display_location, starts_at, ends_at, sort_order)
) ENGINE=InnoDB;

CREATE TABLE quick_links (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    subtitle VARCHAR(180) NOT NULL,
    category VARCHAR(80) NOT NULL DEFAULT 'General',
    link_type VARCHAR(20) NOT NULL DEFAULT 'internal',
    icon VARCHAR(80) NOT NULL,
    color VARCHAR(20) NOT NULL DEFAULT '#0B5CAB',
    url VARCHAR(255) NOT NULL,
    target VARCHAR(20) NOT NULL DEFAULT '_self',
    display_style VARCHAR(20) NOT NULL DEFAULT 'card',
    display_location VARCHAR(40) NOT NULL DEFAULT 'homepage',
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    INDEX idx_quick_links_display (status, display_location, sort_order),
    INDEX idx_quick_links_category (category)
) ENGINE=InnoDB;

CREATE TABLE testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NULL,
    role VARCHAR(180) NOT NULL,
    quote TEXT NOT NULL,
    profile_image VARCHAR(255) NULL,
    image_alt VARCHAR(180) NULL,
    designation VARCHAR(180) NULL,
    department_id INT UNSIGNED NULL,
    programme_id INT UNSIGNED NULL,
    qualification VARCHAR(180) NULL,
    graduation_year SMALLINT UNSIGNED NULL,
    organization VARCHAR(180) NULL,
    rating TINYINT UNSIGNED NULL,
    approval_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    rejection_reason VARCHAR(500) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    display_location VARCHAR(40) NOT NULL DEFAULT 'homepage',
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    robots_index VARCHAR(20) NOT NULL DEFAULT 'noindex',
    robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow',
    INDEX idx_testimonials_listing (status, approval_status, display_location, sort_order)
) ENGINE=InnoDB;

CREATE TABLE videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(180) NULL UNIQUE,
    description TEXT NULL,
    video_url VARCHAR(255) NOT NULL,
    provider VARCHAR(20) NOT NULL DEFAULT 'youtube',
    video_id VARCHAR(120) NULL,
    embed_url VARCHAR(255) NULL,
    thumbnail VARCHAR(255) NULL,
    duration VARCHAR(20) NULL,
    category VARCHAR(80) NULL,
    video_date DATE NULL,
    location VARCHAR(180) NULL,
    credits VARCHAR(180) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(320) NULL,
    focus_keyword VARCHAR(120) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(320) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(20) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow',
    INDEX idx_videos_listing (status, sort_order, id)
) ENGINE=InnoDB;

CREATE TABLE galleries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NULL,
    category VARCHAR(100) NULL,
    event_id INT UNSIGNED NULL,
    gallery_date DATE NULL,
    location VARCHAR(180) NULL,
    photographer VARCHAR(180) NULL,
    cover VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    display_location VARCHAR(40) NOT NULL DEFAULT 'gallery',
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    seo_title VARCHAR(180) NULL,
    meta_description VARCHAR(255) NULL,
    focus_keyword VARCHAR(120) NULL,
    canonical_url VARCHAR(255) NULL,
    og_title VARCHAR(180) NULL,
    og_description VARCHAR(255) NULL,
    og_image VARCHAR(255) NULL,
    twitter_image VARCHAR(255) NULL,
    robots_index VARCHAR(20) NOT NULL DEFAULT 'index',
    robots_follow VARCHAR(20) NOT NULL DEFAULT 'follow',
    structured_data TEXT NULL,
    INDEX idx_galleries_listing (status, display_location, sort_order),
    INDEX idx_galleries_event (event_id),
    INDEX idx_galleries_category (category)
) ENGINE=InnoDB;

CREATE TABLE gallery_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT UNSIGNED NOT NULL,
    title VARCHAR(180) NOT NULL,
    image VARCHAR(255) NOT NULL,
    alt_text VARCHAR(180) NULL,
    credits VARCHAR(180) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'published',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    CONSTRAINT fk_gallery_images FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE,
    INDEX idx_gallery_images_listing (gallery_id, status, sort_order)
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

CREATE TABLE application_functions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    icon VARCHAR(80) NOT NULL DEFAULT 'bi-box-arrow-up-right',
    application_type VARCHAR(80) NOT NULL DEFAULT 'External application',
    external_url VARCHAR(500) NULL,
    category VARCHAR(80) NULL,
    open_new_tab TINYINT(1) NOT NULL DEFAULT 1,
    status VARCHAR(20) NOT NULL DEFAULT 'inactive',
    publication_status VARCHAR(20) NOT NULL DEFAULT 'draft',
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_application_functions_listing (publication_status, status, sort_order)
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
