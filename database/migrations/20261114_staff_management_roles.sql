ALTER TABLE staff
    ADD COLUMN is_management TINYINT(1) NOT NULL DEFAULT 0 AFTER is_featured,
    ADD COLUMN management_role VARCHAR(150) NULL AFTER is_management,
    ADD INDEX idx_staff_management (is_management, status, display_location, sort_order);

UPDATE staff
SET is_management = 1,
    management_role = COALESCE(NULLIF(management_role, ''), position)
WHERE status = 'published'
  AND display_location IN ('directory', 'both');
