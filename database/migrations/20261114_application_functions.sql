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

INSERT INTO application_functions (name, description, category, sort_order)
VALUES
    ('Online application', 'Configure the official online application service URL.', 'Admissions', 1),
    ('Application status', 'Configure the official application status service URL.', 'Admissions', 2);
