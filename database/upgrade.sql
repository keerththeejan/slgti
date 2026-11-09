ALTER TABLE users ADD COLUMN department_id INT UNSIGNED NULL AFTER role_id;
ALTER TABLE news ADD COLUMN tags VARCHAR(255) NULL AFTER meta_description;
ALTER TABLE events ADD COLUMN registration_url VARCHAR(255) NULL AFTER ends_at;
ALTER TABLE contact_messages ADD COLUMN reply_status VARCHAR(20) NOT NULL DEFAULT 'pending' AFTER status;

CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS programme_requirements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    programme_id INT UNSIGNED NOT NULL,
    requirement VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_requirements_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS application_documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    path VARCHAR(255) NOT NULL,
    original_name VARCHAR(180) NOT NULL,
    mime VARCHAR(80) NOT NULL,
    size INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_documents_application FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT IGNORE INTO permissions (slug, name) VALUES
('news.manage', 'Manage news'),
('events.manage', 'Manage events'),
('departments.manage', 'Manage departments'),
('programmes.manage', 'Manage programmes'),
('gallery.manage', 'Manage gallery'),
('staff.manage', 'Manage staff'),
('pages.manage', 'Manage pages'),
('slides.manage', 'Manage homepage slides');

INSERT IGNORE INTO roles (slug, name) VALUES
('administrator', 'Administrator'),
('department_manager', 'Department Manager'),
('admissions_officer', 'Admissions Officer'),
('news_editor', 'News Editor'),
('gallery_manager', 'Gallery Manager');

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r CROSS JOIN permissions p WHERE r.slug = 'super_admin';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN (
    'content.manage','news.manage','events.manage','departments.manage','programmes.manage',
    'gallery.manage','staff.manage','pages.manage','slides.manage','messages.view','media.manage'
) WHERE r.slug = 'administrator';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN (
    'departments.manage','programmes.manage','staff.manage','gallery.manage','media.manage'
) WHERE r.slug = 'department_manager';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN (
    'applications.manage','applications.view','messages.view'
) WHERE r.slug = 'admissions_officer';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('news.manage','events.manage','media.manage')
WHERE r.slug = 'news_editor';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('gallery.manage','media.manage')
WHERE r.slug = 'gallery_manager';

INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT id, role_id FROM users;

INSERT INTO users (name, email, password, role_id, department_id, status)
SELECT 'News Editor', 'news@slgti.ac.lk', '$2y$10$zCb9prSU1RgoF1cV8Em4juTM6GyzIoiqTkQYldx46ZneFh39J5n9q', r.id, NULL, 'active'
FROM roles r WHERE r.slug = 'news_editor'
AND NOT EXISTS (SELECT 1 FROM users WHERE email = 'news@slgti.ac.lk');

INSERT INTO users (name, email, password, role_id, department_id, status)
SELECT 'ICT Department Manager', 'ict@slgti.ac.lk', '$2y$10$zCb9prSU1RgoF1cV8Em4juTM6GyzIoiqTkQYldx46ZneFh39J5n9q', r.id, d.id, 'active'
FROM roles r JOIN departments d ON d.slug = 'information-communication-technology'
WHERE r.slug = 'department_manager'
AND NOT EXISTS (SELECT 1 FROM users WHERE email = 'ict@slgti.ac.lk');

INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, u.role_id FROM users u;

UPDATE sliders SET secondary_text = 'Apply Now', secondary_url = '/apply' WHERE sort_order = 1 AND (secondary_text IS NULL OR secondary_text = '');

INSERT INTO programme_requirements (programme_id, requirement, sort_order)
SELECT p.id, 'G.C.E. Ordinary Level with passes in relevant subjects.', 1
FROM programmes p
WHERE NOT EXISTS (SELECT 1 FROM programme_requirements r WHERE r.programme_id = p.id)
LIMIT 12;
