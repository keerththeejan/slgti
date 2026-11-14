ALTER TABLE faqs
    ADD COLUMN category VARCHAR(80) NULL AFTER question,
    ADD COLUMN slug VARCHAR(180) NULL AFTER category,
    ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER sort_order,
    ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER status,
    ADD COLUMN updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at,
    ADD UNIQUE KEY uq_faq_slug (slug),
    ADD INDEX idx_faq_listing (status, category, sort_order);

UPDATE faqs
SET category = COALESCE(NULLIF(category, ''), 'General'),
    slug = CONCAT('faq-', id),
    updated_at = NOW()
WHERE slug IS NULL OR slug = '';
