-- Add the Admissions landing page to the existing Pages CMS without replacing an administrator's record.
INSERT INTO pages (title, slug, excerpt, body, meta_title, meta_description, status)
SELECT
    'Admissions',
    'admissions',
    'NVQ Level 4, 5 and 6 pathways for young women and men from every district.',
    '<p>SLGTI welcomes young women and men from every district to practice-oriented vocational training. Explore the published programmes and entry pathways below before applying.</p><p>Applicants should prepare their NIC and educational results. Shortlisted applicants are invited for selection at the Kilinochchi campus.</p>',
    'Admissions | SLGTI',
    'Apply for NVQ Level 4, 5 and 6 programmes at the Sri Lanka German Training Institute in Kilinochchi.',
    'published'
WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'admissions');
