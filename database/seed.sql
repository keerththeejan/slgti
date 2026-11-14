USE webslgti;
SET NAMES utf8mb4;

INSERT INTO roles (id, name, slug) VALUES
(1, 'Super Administrator', 'super_admin'),
(2, 'Editor', 'editor'),
(3, 'Staff', 'staff');

INSERT INTO permissions (id, name, slug) VALUES
(1, 'Manage content', 'content.manage'),
(2, 'View messages', 'messages.view'),
(3, 'Manage applications', 'applications.manage'),
(4, 'View applications', 'applications.view'),
(5, 'Manage media', 'media.manage'),
(6, 'Manage users', 'users.manage'),
(7, 'Manage settings', 'settings.manage'),
(8, 'View logs', 'logs.view'),
(9, 'Manage backups', 'backups.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions
UNION ALL SELECT 2, id FROM permissions WHERE slug IN ('content.manage','messages.view','applications.manage','media.manage')
UNION ALL SELECT 3, id FROM permissions WHERE slug IN ('messages.view','applications.view');

INSERT INTO users (name, email, password, role_id, status) VALUES
('SLGTI Administrator', 'admin@slgti.ac.lk', '$2y$12$VTS6tXOOCeWyUSxbWwuPr.jB4levXkMS1roLGdIWgxYXm/z/utcme', 1, 'active');

INSERT INTO departments (id, name, slug, summary, description, facilities, image, icon, head_name, email, phone, sort_order, status) VALUES
(1, 'Automotive & Agricultural Technology', 'automotive-agricultural-technology',
 'Vehicle systems, farm machinery and practical diagnostics for the transport and agriculture sectors.',
 '<p>The department trains technicians who can service modern vehicles and agricultural machinery. Students spend most of their time in workshops, moving from measurement and safety to diagnosis, repair and preventive maintenance.</p><p>Training follows the National Vocational Qualification and is arranged so that classroom theory is tested on real equipment.</p>',
 '<ul><li>Automobile workshop</li><li>Farm machinery bay</li><li>Engine and electrical diagnostics benches</li></ul>',
 'assets/images/dept-auto.jpg', 'bi-truck', 'Head of Department', 'auto@slgti.ac.lk', '021 492 7799', 1, 'published'),
(2, 'Electrical & Electronic Technology', 'electrical-electronic-technology',
 'Power, installation, electronics and control systems for industry and building services.',
 '<p>Students learn safe electrical installation, measurement, electronics and the control systems used in workshops and industry. The programme builds the discipline required for live systems and the judgement required for fault finding.</p>',
 '<ul><li>Electrical installation bays</li><li>Electronics laboratory</li><li>Measurement and control benches</li></ul>',
 'assets/images/dept-electrical.jpg', 'bi-lightning-charge', 'Head of Department', 'electrical@slgti.ac.lk', '021 492 7799', 2, 'published'),
(3, 'Mechanical Technology', 'mechanical-technology',
 'Fitting, machining, welding and maintenance for production and service industries.',
 '<p>Mechanical technology at SLGTI is taught through fitting, machining, fabrication and maintenance tasks. Students learn to read drawings, hold tolerances and work safely with machine tools.</p>',
 '<ul><li>Machine shop</li><li>Fitting and fabrication bays</li><li>Metrology section</li></ul>',
 'assets/images/dept-mechanical.jpg', 'bi-gear', 'Head of Department', 'mechanical@slgti.ac.lk', '021 492 7799', 3, 'published'),
(4, 'Food Technology', 'food-technology',
 'Food processing, hygiene and quality control for Sri Lankan food industries.',
 '<p>The department prepares technicians for processing, packaging and quality control. Hygiene, measurement and product safety are taught as daily workshop practice, not as a separate subject.</p>',
 '<ul><li>Food processing laboratory</li><li>Quality testing benches</li><li>Hygiene training area</li></ul>',
 'assets/images/dept-food.jpg', 'bi-cup-hot', 'Head of Department', 'food@slgti.ac.lk', '021 492 7799', 4, 'published'),
(5, 'Information & Communication Technology', 'information-communication-technology',
 'Computing, networks and digital systems that support technical workplaces.',
 '<p>ICT training covers computer systems, networks, software tools and the digital skills technical workplaces now expect. Students practise on laboratory equipment and project work.</p>',
 '<ul><li>Computer laboratories</li><li>Network practice racks</li><li>Project studio</li></ul>',
 'assets/images/dept-ict.jpg', 'bi-pc-display', 'Head of Department', 'ict@slgti.ac.lk', '021 492 7799', 5, 'published'),
(6, 'Construction Technology', 'construction-technology',
 'Building trades, site practice and the technical skills used on civil works.',
 '<p>Construction students learn setting out, building services and site practice. The department connects drawing, measurement and safe work on site.</p>',
 '<ul><li>Construction yard</li><li>Materials laboratory</li><li>Drawing studio</li></ul>',
 'assets/images/dept-construction.jpg', 'bi-building', 'Head of Department', 'construction@slgti.ac.lk', '021 492 7799', 6, 'published');

INSERT INTO programme_levels (id, code, name, slug, qualification, description, sort_order) VALUES
(1, 'NVQ Level 4', 'Certificate', 'nvq-4', 'Certificate Level Technical Training', 'Craft-level training for school leavers who meet the subject requirements for the selected occupation.', 1),
(2, 'NVQ Level 5', 'Diploma', 'nvq-5', 'Advanced Technical Training', 'Technician-level study for students who have completed a relevant Level 4 qualification or equivalent.', 2),
(3, 'NVQ Level 6', 'Higher National Diploma', 'nvq-6', 'Higher National Diploma', 'Advanced technical education for students progressing from Level 5 or an accepted equivalent.', 3);

INSERT INTO programmes (department_id, level_id, title, slug, summary, description, duration, entry_requirements, career_outcomes, image, sort_order, status) VALUES
(1, 1, 'Automobile Technology', 'automobile-technology',
 'Service, diagnosis and repair of light vehicles.',
 '<p>Students learn workshop safety, engines, brakes, steering and electrical systems. Assessment is based on practical tasks and the NVQ standard for the occupation.</p>',
 '12 months', '<p>GCE Ordinary Level with passes in relevant subjects, including mathematics or science where specified for the intake.</p>',
 '<p>Workshop technician, service advisor trainee, or progression to NVQ Level 5.</p>',
 'assets/images/dept-auto.jpg', 1, 'published'),
(1, 1, 'Agricultural Machinery', 'agricultural-machinery',
 'Operation and maintenance of farm machinery.',
 '<p>Training covers engines, power transmission, field machinery and safe operation. Students practise maintenance routines used by farms and contractors.</p>',
 '12 months', '<p>GCE Ordinary Level. Interest in agriculture or machinery is expected.</p>',
 '<p>Machinery technician in farms, cooperatives and equipment dealers.</p>',
 'assets/images/dept-auto.jpg', 2, 'published'),
(2, 1, 'Electrical Technology', 'electrical-technology',
 'Installation, testing and maintenance of electrical systems.',
 '<p>The certificate develops safe installation practice, circuit reading and fault finding for domestic and light industrial systems.</p>',
 '12 months', '<p>GCE Ordinary Level with a pass in mathematics or science.</p>',
 '<p>Electrical installer, maintenance assistant, or Level 5 progression.</p>',
 'assets/images/dept-electrical.jpg', 3, 'published'),
(2, 2, 'Electronic Technology', 'electronic-technology',
 'Electronics, measurement and basic control systems.',
 '<p>Diploma study extends circuit theory into measurement, electronic repair and industrial control. Laboratory tasks are assessed against the NVQ standard.</p>',
 '18 months', '<p>NVQ Level 4 in a relevant electrical or electronic occupation, or an accepted equivalent.</p>',
 '<p>Electronics technician in industry, service centres and building systems.</p>',
 'assets/images/dept-electrical.jpg', 4, 'published'),
(3, 1, 'Mechanical Fitting and Machining', 'mechanical-fitting-machining',
 'Fitting, measurement and basic machine-shop practice.',
 '<p>Students learn hand fitting, drilling, turning and the measurement habits required in a production workshop.</p>',
 '12 months', '<p>GCE Ordinary Level. Workshop aptitude is assessed at interview.</p>',
 '<p>Fitter, machinist trainee, or maintenance assistant.</p>',
 'assets/images/dept-mechanical.jpg', 5, 'published'),
(3, 2, 'Mechanical Technology', 'mechanical-technology-diploma',
 'Maintenance and production support at technician level.',
 '<p>The diploma adds planning, technical drawing and maintenance management to practical machining and fabrication.</p>',
 '18 months', '<p>Relevant NVQ Level 4 or an accepted technician entry qualification.</p>',
 '<p>Mechanical technician in manufacturing and facilities maintenance.</p>',
 'assets/images/dept-mechanical.jpg', 6, 'published'),
(4, 1, 'Food Processing Technology', 'food-processing-technology',
 'Processing, hygiene and basic quality checks.',
 '<p>Students practise food handling, processing steps and the records a quality-conscious plant expects.</p>',
 '12 months', '<p>GCE Ordinary Level, including science where required for the intake.</p>',
 '<p>Process operator or quality assistant in food manufacturing.</p>',
 'assets/images/dept-food.jpg', 7, 'published'),
(4, 2, 'Food Technology', 'food-technology-diploma',
 'Quality, production and product safety at diploma level.',
 '<p>Diploma students connect processing practice with quality systems, testing and supervision of a production line.</p>',
 '18 months', '<p>NVQ Level 4 in food technology or an accepted equivalent.</p>',
 '<p>Production supervisor trainee or quality technician.</p>',
 'assets/images/dept-food.jpg', 8, 'published'),
(5, 1, 'Information and Communication Technology', 'ict-certificate',
 'Computer systems, software tools and basic networks.',
 '<p>Students build, maintain and use computer systems, and practise the software tools used in technical offices.</p>',
 '12 months', '<p>GCE Ordinary Level with passes in relevant subjects.</p>',
 '<p>IT support assistant or progression to the diploma.</p>',
 'assets/images/dept-ict.jpg', 9, 'published'),
(5, 2, 'ICT Technician', 'ict-technician',
 'Networks, systems support and workplace digital projects.',
 '<p>The diploma prepares technicians who can support networks, user systems and small digital projects.</p>',
 '18 months', '<p>NVQ Level 4 in ICT or an accepted equivalent.</p>',
 '<p>ICT technician in education, industry or service companies.</p>',
 'assets/images/dept-ict.jpg', 10, 'published'),
(6, 1, 'Construction Technology', 'construction-technology-certificate',
 'Building trades, measurement and site safety.',
 '<p>Students learn setting out, basic construction tasks and the safety rules of a working site.</p>',
 '12 months', '<p>GCE Ordinary Level. Physical readiness for site work is discussed at interview.</p>',
 '<p>Site technician trainee or skilled worker in building trades.</p>',
 'assets/images/dept-construction.jpg', 11, 'published'),
(6, 3, 'Construction Technology Higher National Diploma', 'construction-hnd',
 'Technical coordination for building and civil works.',
 '<p>Level 6 study develops the judgement needed to read project information, coordinate trades and check quality on site.</p>',
 '24 months', '<p>NVQ Level 5 in construction or an accepted advanced entry qualification.</p>',
 '<p>Technical officer or site supervisor trainee.</p>',
 'assets/images/dept-construction.jpg', 12, 'published');

INSERT INTO staff (name, position, department_id, bio, email, sort_order, status) VALUES
('Principal', 'Principal', NULL, '<p>The Principal leads academic standards, student welfare and the institute relationship with industry and public partners.</p>', 'principal@slgti.ac.lk', 1, 'published'),
('Director Academic Affairs', 'Academic Affairs', NULL, '<p>Academic Affairs coordinates departments, assessments and the NVQ training calendar.</p>', 'academic@slgti.ac.lk', 2, 'published'),
('Director Student Affairs', 'Student Affairs', NULL, '<p>Student Affairs supports hostels, welfare, discipline and student activities.</p>', 'students@slgti.ac.lk', 3, 'published'),
('Head of ICT', 'Head of Department', 5, '<p>Leads the Information and Communication Technology department.</p>', 'ict@slgti.ac.lk', 4, 'published');

INSERT INTO news_categories (id, name, slug) VALUES
(1, 'Announcement', 'announcement'),
(2, 'News', 'news'),
(3, 'Admission', 'admission'),
(4, 'Achievement', 'achievement');

INSERT INTO news (category_id, title, slug, excerpt, body, image, is_featured, published_at, status, meta_title, meta_description) VALUES
(3, 'Interview Eligibility List – Admission 2026', 'interview-eligibility-list-admission-2026',
 'The eligibility list for admission interviews is now published for shortlisted applicants.',
 '<p>Shortlisted applicants for the 2026 intake should check the eligibility list and bring their NIC, educational certificates and the application reference number to the interview.</p><p>The admissions office will not accept incomplete documents. Applicants who cannot attend must inform the institute before the interview date.</p>',
 'assets/images/news-3.jpg', 1, '2026-10-20 09:00:00', 'published', 'Interview Eligibility List 2026', 'Admission interview eligibility list for SLGTI.'),
(2, 'SLGTI Expo 2025 Successfully Concluded', 'slgti-expo-2025-successfully-concluded',
 'Students, industry partners and schools joined the annual skills exhibition at Kilinochchi.',
 '<p>The SLGTI Expo presented work from every department. Visitors saw automobile diagnostics, electrical installations, food processing, ICT projects and construction practice.</p><p>The exhibition is part of the institute commitment to show technical training as a first-choice pathway.</p>',
 'assets/images/news-2.jpg', 1, '2026-10-15 09:00:00', 'published', 'SLGTI Expo 2025', 'Report from the SLGTI skills exhibition.'),
(2, 'Youth Skill Parade 2025 at SLGTI Kilinochchi', 'youth-skill-parade-2025',
 'Students demonstrated occupational skills to schools and families from the Northern Province.',
 '<p>The Youth Skill Parade gave school students a direct view of workshop training. Department staff explained entry requirements and the difference between NVQ Levels 4, 5 and 6.</p>',
 'assets/images/news-1.jpg', 0, '2026-09-18 09:00:00', 'published', 'Youth Skill Parade 2025', 'Skill parade at SLGTI Kilinochchi.'),
(2, 'Kilinochchi Central College visits SLGTI', 'kilinochchi-central-college-visit',
 'Advanced Level technology and arts students toured workshops and laboratories.',
 '<p>Students of Kilinochchi Central College visited the campus to see practical training in automobile, electrical, mechanical, food, ICT and construction departments.</p>',
 'assets/images/about-workshop.jpg', 0, '2026-02-20 09:00:00', 'published', 'School visit to SLGTI', 'Kilinochchi Central College visit.'),
(2, 'Prime Minister visits SLGTI', 'prime-minister-visits-slgti',
 'Hon. Prime Minister Harini Amarasuriya visited the institute and met students and staff.',
 '<p>The visit recognised the role of SLGTI in technical education for youth in the Northern Province and the wider country. Students demonstrated workshop tasks during the tour.</p>',
 'assets/images/hero-1.jpg', 1, '2026-02-16 09:00:00', 'published', 'Prime Minister visits SLGTI', 'Official visit to the Sri Lanka German Training Institute.');

INSERT INTO event_categories (id, name, slug) VALUES
(1, 'Open Day', 'open-day'),
(2, 'Student Activity', 'student-activity'),
(3, 'Training', 'training');

INSERT INTO events (category_id, title, slug, excerpt, body, venue, image, starts_at, ends_at, status) VALUES
(1, 'Campus Open Day 2026', 'campus-open-day-2026',
 'Meet lecturers, walk through the workshops and ask about the next intake.',
 '<p>Prospective students and families are welcome. Bring certificates if you would like informal advice on a programme. The open day does not replace the formal application.</p>',
 'Main auditorium and workshops', 'assets/images/campus-auditorium.jpg', '2026-12-05 09:00:00', '2026-12-05 15:00:00', 'published'),
(2, 'Inter-department Sports Meet', 'inter-department-sports-meet',
 'A one-day sports meet for enrolled students.',
 '<p>Departments will field teams for track and team games. Student Affairs will publish the schedule two weeks before the meet.</p>',
 'Campus grounds', 'assets/images/life-sports.jpg', '2026-11-20 08:30:00', '2026-11-20 16:30:00', 'published'),
(3, 'Industry Practice Briefing', 'industry-practice-briefing',
 'A briefing for students preparing for industry exposure.',
 '<p>The briefing covers conduct, safety and the records students must keep during industry exposure.</p>',
 'Auditorium', 'assets/images/life-lab.jpg', '2026-11-28 10:00:00', '2026-11-28 12:00:00', 'published');

INSERT INTO pages (title, slug, excerpt, body, meta_title, meta_description, status) VALUES
('About SLGTI', 'about-slgti',
 'A state technical training institute in Kilinochchi, shaped by German cooperation and Sri Lankan industry.',
 '<p>Sri Lanka German Training Institute provides practice-oriented vocational training at NVQ Levels 4, 5 and 6. The institute was established to offer demand-oriented education for technical occupations and to support skilled employment for young women and men.</p><p>SLGTI stands for precise workshop habits, fair access and a direct relationship with employers. Classrooms, laboratories, workshops, hostels and an auditorium are arranged so that students can live and train on one campus.</p><h2 id="values">How we train</h2><p>Every programme joins theory with repeated practice. Students are assessed on tasks they can perform, not only on what they can describe. Industry exposure is part of the pathway from the workshop to the workplace.</p>',
 'About SLGTI', 'About the Sri Lanka German Training Institute in Kilinochchi.', 'published'),
('Welcome from the Principal', 'principal-message',
 'A welcome to students, families and industry partners.',
 '<p>SLGTI exists so that young people can learn a technical occupation to a standard that employers recognise. Our workshops are the centre of the campus. I invite students to bring discipline and curiosity, and I invite industry to keep telling us what the workplace now requires.</p><p>We will continue to strengthen NVQ training, student welfare and the partnerships that make practical education credible.</p>',
 'Principal message', 'Welcome from the Principal of SLGTI.', 'published'),
('Quality Management', 'quality-management',
 'How training quality is reviewed.',
 '<p>Quality management at SLGTI covers curriculum delivery, assessment, workshop safety and student feedback. Departments review practical tasks against the NVQ standard and record corrective actions.</p><p>Students can raise academic concerns through Student Affairs. Serious safety issues should be reported to the department head the same day.</p>',
 'Quality Management', 'Quality management at SLGTI.', 'published'),
('Green College', 'green-college',
 'A campus that treats energy, water and materials with care.',
 '<p>The Green College practice asks departments to reduce waste, maintain equipment and teach students the environmental duties of a technician. Workshops separate scrap, switch off unused machines and treat chemicals and food waste according to the department procedure.</p>',
 'Green College', 'Environmental practice at SLGTI.', 'published'),
('Academic Calendar', 'academic-calendar',
 'The shape of the training year.',
 '<table><thead><tr><th>Period</th><th>Activity</th></tr></thead><tbody><tr><td>January</td><td>Orientation and workshop induction</td></tr><tr><td>February to October</td><td>Training, assessments and industry exposure</td></tr><tr><td>November</td><td>Final assessments and skill demonstrations</td></tr><tr><td>December</td><td>Open day and admissions advice</td></tr></tbody></table><p>Exact dates are confirmed by Academic Affairs for each intake.</p>',
 'Academic calendar', 'Academic calendar for SLGTI.', 'published'),
('Privacy Policy', 'privacy-policy',
 'How SLGTI handles personal information.',
 '<p>Application and contact forms collect only what the institute needs to respond. Records are stored in the institute database, accessed by authorised staff, and are not sold. You may ask the office to correct your contact details.</p><p>This website uses a session cookie so that forms and the administration panel can work securely. It is not used for advertising.</p>',
 'Privacy Policy', 'Privacy policy of the SLGTI website.', 'published'),
('Terms of Use', 'terms-of-use',
 'Conditions for using this website.',
 '<p>Content on this website is published for information about SLGTI programmes and services. Admission decisions are made by the institute, not by the website alone. Do not misuse forms, attempt to access the administration panel without authorisation, or copy workshop materials for commercial use.</p>',
 'Terms of Use', 'Terms of use for the SLGTI website.', 'published'),
('Accessibility', 'accessibility',
 'Our commitment to an accessible website.',
 '<p>Pages use semantic headings, visible focus, labels on form fields and text that can be resized. If you find a barrier, email info@slgti.ac.lk and describe the page and the problem. We will correct what we can.</p>',
 'Accessibility', 'Accessibility statement for the SLGTI website.', 'published');

INSERT INTO menus (id, name, slug) VALUES
(1, 'Header', 'header'),
(2, 'Footer', 'footer');

INSERT INTO menu_items (id, menu_id, parent_id, label, url, sort_order, status) VALUES
(1, 1, NULL, 'Home', '/', 1, 'published'),
(2, 1, NULL, 'About Us', '/about', 2, 'published'),
(3, 1, 2, 'About SLGTI', '/about', 1, 'published'),
(4, 1, 2, 'Principal''s Message', '/about#principal', 2, 'published'),
(5, 1, 2, 'The Management', '/about#management', 3, 'published'),
(6, 1, 2, 'Facilities', '/about#facilities', 4, 'published'),
(7, 1, 2, 'Quality Management', '/page/quality-management', 5, 'published'),
(8, 1, 2, 'Green College', '/page/green-college', 6, 'published'),
(9, 1, NULL, 'Programmes', '/programmes', 3, 'published'),
(10, 1, 9, 'NVQ Level 4', '/programmes?level=nvq-4', 1, 'published'),
(11, 1, 9, 'NVQ Level 5', '/programmes?level=nvq-5', 2, 'published'),
(12, 1, 9, 'NVQ Level 6', '/programmes?level=nvq-6', 3, 'published'),
(13, 1, NULL, 'Departments', '/departments', 4, 'published'),
(14, 1, 13, 'Automotive & Agriculture', '/departments/automotive-agricultural-technology', 1, 'published'),
(15, 1, 13, 'Electrical & Electronic', '/departments/electrical-electronic-technology', 2, 'published'),
(16, 1, 13, 'Mechanical', '/departments/mechanical-technology', 3, 'published'),
(17, 1, 13, 'Food Technology', '/departments/food-technology', 4, 'published'),
(18, 1, 13, 'ICT', '/departments/information-communication-technology', 5, 'published'),
(19, 1, 13, 'Construction', '/departments/construction-technology', 6, 'published'),
(20, 1, NULL, 'Admissions', '/admissions', 5, 'published'),
(21, 1, 20, 'How to Apply', '/admissions', 1, 'published'),
(22, 1, 20, 'Apply Now', '/apply', 2, 'published'),
(23, 1, 20, 'Announcements', '/news?category=admission', 3, 'published'),
(24, 1, NULL, 'Students', '/students', 6, 'published'),
(25, 1, NULL, 'News & Events', '/news', 7, 'published'),
(26, 1, NULL, 'Contact Us', '/contact', 8, 'published'),
(30, 2, NULL, 'Home', '/', 1, 'published'),
(31, 2, NULL, 'About Us', '/about', 2, 'published'),
(32, 2, NULL, 'Programmes', '/programmes', 3, 'published'),
(33, 2, NULL, 'Admissions', '/admissions', 4, 'published'),
(34, 2, NULL, 'Students', '/students', 5, 'published'),
(35, 2, NULL, 'News', '/news', 6, 'published'),
(36, 2, NULL, 'Gallery', '/gallery', 7, 'published'),
(37, 2, NULL, 'Contact', '/contact', 8, 'published');

INSERT INTO faqs (question, answer, sort_order, status) VALUES
('Who can apply to SLGTI?', '<p>Young women and men from any district in Sri Lanka who meet the entry requirement of the selected NVQ programme.</p>', 1, 'published'),
('Which qualifications do you offer?', '<p>NVQ Level 4 certificate programmes, NVQ Level 5 diplomas and NVQ Level 6 higher national diploma study.</p>', 2, 'published'),
('Is there student accommodation?', '<p>Hostel places are available for enrolled students. Allocation is confirmed by Student Affairs after admission.</p>', 3, 'published'),
('How do I know my application was received?', '<p>The online form shows a reference number immediately. Quote that number in every email or call to the admissions office.</p>', 4, 'published'),
('Where is the campus?', '<p>Ariviyal Nagar, Kilinochchi 44000. The office is open Monday to Friday, 8:30 AM to 4:15 PM.</p>', 5, 'published');

INSERT INTO facilities (title, summary, image, icon, sort_order, status) VALUES
('Workshops', 'Occupation workshops where students repeat the tasks of the NVQ standard.', 'assets/images/about-workshop.jpg', 'bi-tools', 1, 'published'),
('Laboratories', 'Electrical, electronics, food and ICT laboratories for measurement and testing.', 'assets/images/life-lab.jpg', 'bi-clipboard-data', 2, 'published'),
('Library', 'A quiet collection for technical reference, assignments and examination preparation.', 'assets/images/life-library.jpg', 'bi-book', 3, 'published'),
('Auditorium', 'Briefings, guest lectures and institute events.', 'assets/images/campus-auditorium.jpg', 'bi-easel', 4, 'published'),
('Hostels', 'Accommodation for students who train away from home.', 'assets/images/life-hostel.jpg', 'bi-house', 5, 'published'),
('Sports', 'Grounds and student activities organised with Student Affairs.', 'assets/images/life-sports.jpg', 'bi-trophy', 6, 'published');

INSERT INTO sliders (eyebrow, title, title_highlight, subtitle, image, button_text, button_url, secondary_text, secondary_url, sort_order, status) VALUES
('Skills for a brighter future', 'Sri Lanka – German', 'Training Institute',
 'Providing high quality technical and vocational education and training to empower youth for a successful career.',
 'assets/images/hero-1.jpg', 'Explore Programmes', '/programmes', 'Apply Now', '/apply', 1, 'published'),
('German technology', 'Meet your future', 'at SLGTI',
 'Step into workshops built for practical NVQ training, industry exposure and a recognised technical qualification.',
 'assets/images/hero-2.jpg', 'Explore Programmes', '/programmes', '', '', 2, 'published'),
('Practical training', 'Skills for a', 'brighter future',
 'Six departments. NVQ Levels 4, 5 and 6. A campus for young women and men preparing for technical careers.',
 'assets/images/hero-3.jpg', 'Explore Programmes', '/programmes', '', '', 3, 'published');

INSERT INTO quick_links (title, subtitle, icon, color, url, sort_order, status) VALUES
('Apply Now', 'Admission Information', 'bi-person-fill', '#2F80ED', '/apply', 1, 'published'),
('Student Information System', 'Academic & Student Services', 'bi-mortarboard-fill', '#27AE60', 'https://www.slgti.ac.lk/', 2, 'published'),
('E-Learning', 'Access Learning Materials', 'bi-laptop', '#7C3AED', 'https://www.slgti.ac.lk/', 3, 'published'),
('News & Events', 'Latest Updates', 'bi-calendar-event', '#EF233C', '/news', 4, 'published'),
('Gallery', 'Photos & Videos', 'bi-image', '#F2C14E', '/gallery', 5, 'published'),
('Contact Us', 'Get in Touch', 'bi-telephone-fill', '#1ABC9C', '/contact', 6, 'published');

INSERT INTO testimonials (name, role, quote, sort_order, status) VALUES
('T. Jayaruban', 'Sewing Machine Technician Training',
 'With this sewing machine technician programme and the opportunity I received from MAS to work at their factory, I am confident my future will be prosperous.',
 1, 'published'),
('T. Ilham', 'Student, Sewing Machine Technician',
 'During the first three months we learned theory at SLGTI. Afterward we went to MAS and learned the machines, the needles and how air compression is used in a factory.',
 2, 'published'),
('Industry partner', 'Apparel sector',
 'SLGTI students arrive with workshop discipline. They still have to learn our factory, but they already know how to listen, measure and work safely.',
 3, 'published'),
('Graduate', 'Electrical technology',
 'The hours on the installation bays mattered more than any single lesson. I left knowing how to test a circuit before I trusted it.',
 4, 'published');

INSERT INTO galleries (id, title, slug, description, cover, sort_order, status) VALUES
(1, 'Workshops', 'workshops', 'Practical training across the departments.', 'assets/images/gallery-1.jpg', 1, 'published'),
(2, 'Campus life', 'campus-life', 'Students, sports and institute gatherings.', 'assets/images/gallery-6.jpg', 2, 'published'),
(3, 'Industry exposure', 'industry-exposure', 'Training beyond the campus.', 'assets/images/gallery-4.jpg', 3, 'published');

INSERT INTO gallery_images (gallery_id, title, image, sort_order, status) VALUES
(1, 'Mechanical workshop practice', 'assets/images/gallery-1.jpg', 1, 'published'),
(1, 'Technical instruction', 'assets/images/gallery-2.jpg', 2, 'published'),
(1, 'Engineering bench work', 'assets/images/gallery-4.jpg', 3, 'published'),
(2, 'Students in discussion', 'assets/images/gallery-6.jpg', 1, 'published'),
(2, 'Campus planning session', 'assets/images/gallery-3.jpg', 2, 'published'),
(3, 'Applied technology session', 'assets/images/gallery-5.jpg', 1, 'published');

INSERT INTO videos (title, description, video_url, category, sort_order, status) VALUES
('SLGTI Media', 'Official video channel of the Sri Lanka German Training Institute.', 'https://www.youtube.com/@SLGTI-MEDIA', 'Campus', 1, 'published');

INSERT INTO applications (reference_no, full_name, nic, email, phone, gender, date_of_birth, address, district, programme_id, education_level, results, message, status, created_at) VALUES
('SLGTI-2026-A1B2C3', 'N. Krishanthi', '200145678901', 'krishanthi.example@slgti.ac.lk', '0771234567', 'Female', '2001-04-12', 'Kilinochchi', 'Kilinochchi', 1, 'GCE O/L', 'O/L with Mathematics and Science', '', 'pending', '2026-06-12 10:00:00'),
('SLGTI-2026-D4E5F6', 'S. Arun', '199912345678', 'arun.example@slgti.ac.lk', '0772345678', 'Male', '1999-11-02', 'Jaffna', 'Jaffna', 3, 'GCE A/L', 'A/L Technology stream', '', 'reviewing', '2026-07-03 10:00:00'),
('SLGTI-2026-G7H8I9', 'F. Ameera', '200256789012', 'ameera.example@slgti.ac.lk', '0773456789', 'Female', '2002-08-21', 'Vavuniya', 'Vavuniya', 7, 'GCE O/L', 'O/L 6 passes', '', 'accepted', '2026-08-19 10:00:00'),
('SLGTI-2026-J1K2L3', 'R. Dilshan', '200078901234', 'dilshan.example@slgti.ac.lk', '0774567890', 'Male', '2000-01-30', 'Mannar', 'Mannar', 5, 'GCE O/L', 'O/L Mathematics B', '', 'pending', '2026-09-02 10:00:00'),
('SLGTI-2026-M4N5O6', 'K. Tharshi', '200367890123', 'tharshi.example@slgti.ac.lk', '0775678901', 'Female', '2003-03-18', 'Mullaitivu', 'Mullaitivu', 9, 'GCE O/L', 'O/L with ICT', '', 'pending', '2026-10-11 10:00:00'),
('SLGTI-2026-P7Q8R9', 'P. Rajan', '199834567890', 'rajan.example@slgti.ac.lk', '0776789012', 'Male', '1998-07-07', 'Kilinochchi', 'Kilinochchi', 11, 'NVQ Level 4', 'NVQ 4 Construction', '', 'reviewing', '2026-10-28 10:00:00'),
('SLGTI-2026-S1T2U3', 'M. Inoshi', '200412345678', 'inoshi.example@slgti.ac.lk', '0777890123', 'Female', '2004-12-01', 'Colombo', 'Colombo', 4, 'NVQ Level 4', 'NVQ 4 Electrical', '', 'pending', '2026-11-02 10:00:00');

INSERT INTO contact_messages (name, email, phone, subject, message, status, created_at) VALUES
('School teacher', 'teacher.example@school.lk', '0212223344', 'School visit request', 'We would like to bring Advanced Level students to see the workshops.', 'new', '2026-11-01 09:00:00'),
('Parent', 'parent.example@mail.lk', '0770001122', 'Hostel question', 'Is hostel accommodation available for a student from Mannar?', 'read', '2026-10-22 09:00:00');

INSERT INTO settings (setting_key, setting_value, setting_group, label, field_type) VALUES
('site_name', 'Sri Lanka German Training Institute', 'general', 'Site name', 'text'),
('tagline', 'Skills for a brighter future', 'general', 'Tagline', 'text'),
('meta_description', 'Sri Lanka German Training Institute in Kilinochchi offers NVQ Level 4, 5 and 6 technical and vocational programmes.', 'seo', 'Default meta description', 'textarea'),
('phone', '021 492 7799', 'contact', 'Primary phone', 'text'),
('phone_alt', '021 206 0137', 'contact', 'Alternate phone', 'text'),
('fax', '021 206 0138', 'contact', 'Fax', 'text'),
('email', 'info@slgti.ac.lk', 'contact', 'Email', 'text'),
('address', 'Ariviyal Nagar, Kilinochchi 44000, Sri Lanka', 'contact', 'Address', 'textarea'),
('location_label', 'Kilinochchi, Sri Lanka', 'contact', 'Short location', 'text'),
('hours', 'Monday to Friday, 8:30 AM – 4:15 PM', 'contact', 'Office hours', 'text'),
('footer_text', 'Providing high quality technical and vocational education and training to empower youth for a successful career.', 'general', 'Footer text', 'textarea'),
('site_logo', '', 'logo', 'Website logo', 'file'),
('site_logo_alt', 'Sri Lanka German Training Institute', 'logo', 'Logo alternative text', 'text'),
('site_logo_width', '220', 'logo', 'Logo display width', 'number'),
('site_logo_height', '52', 'logo', 'Logo maximum height', 'number'),
('favicon_16', '', 'favicon', 'Favicon 16px', 'file'),
('favicon_32', '', 'favicon', 'Favicon 32px', 'file'),
('favicon_48', '', 'favicon', 'Favicon 48px', 'file'),
('favicon_apple', '', 'favicon', 'Apple touch icon', 'file'),
('favicon_android', '', 'favicon', 'Android icon', 'file'),
('favicon_large', '', 'favicon', 'Large favicon', 'file'),
('footer_logo', '', 'footer', 'Footer logo', 'file'),
('footer_visible', '1', 'footer', 'Show footer', 'select'),
('footer_copyright', 'Sri Lanka German Training Institute (SLGTI), Kilinochchi. All rights reserved.', 'footer', 'Copyright text', 'text'),
('footer_website', 'https://www.slgti.ac.lk/', 'footer', 'Footer website URL', 'text'),
('facebook_url', 'https://www.facebook.com/', 'social', 'Facebook URL', 'text'),
('youtube_url', 'https://www.youtube.com/@SLGTI-MEDIA', 'social', 'YouTube URL', 'text'),
('linkedin_url', 'https://www.linkedin.com/', 'social', 'LinkedIn URL', 'text'),
('sis_url', 'https://www.slgti.ac.lk/', 'links', 'Student Information System URL', 'text'),
('elearning_url', 'https://www.slgti.ac.lk/', 'links', 'E-Learning URL', 'text'),
('apply_open', '1', 'admissions', 'Applications open', 'select'),
('stat_students', '1200', 'homepage', 'Statistic: students', 'text'),
('stat_programmes', '12', 'homepage', 'Statistic: programmes', 'text'),
('stat_departments', '6', 'homepage', 'Statistic: departments', 'text'),
('stat_partners', '40', 'homepage', 'Statistic: partners', 'text'),
('home_about_kicker', 'About SLGTI', 'homepage', 'About kicker', 'text'),
('home_about_title', 'Building Skills for a Better Tomorrow', 'homepage', 'About heading', 'text'),
('home_about_text', 'Sri Lanka German Training Institute (SLGTI) is a premier technical and vocational training institute established to provide industry-oriented training to youth in Sri Lanka. We are committed to developing skilled professionals through high-quality training, modern facilities and industry collaboration.', 'homepage', 'About paragraph', 'textarea'),
('home_about_image', 'assets/images/about-students.jpg', 'homepage', 'About photograph path', 'text'),
('home_about_button_label', 'Learn More About Us', 'homepage', 'About button label', 'text'),
('home_about_button_url', '/about', 'homepage', 'About button URL', 'text'),
('home_programmes_kicker', 'Our Programmes', 'homepage', 'Programmes kicker', 'text'),
('home_programmes_title', 'NVQ Programmes', 'homepage', 'Programmes heading', 'text'),
('home_programmes_text', 'Industry-oriented training programmes for a successful career.', 'homepage', 'Programmes introduction', 'text');

INSERT INTO roles (id, name, slug) VALUES
(4, 'Administrator', 'administrator'),
(5, 'Department Manager', 'department_manager'),
(6, 'Admissions Officer', 'admissions_officer'),
(7, 'News Editor', 'news_editor'),
(8, 'Gallery Manager', 'gallery_manager');

INSERT INTO permissions (id, name, slug) VALUES
(10, 'Manage news', 'news.manage'),
(11, 'Manage events', 'events.manage'),
(12, 'Manage departments', 'departments.manage'),
(13, 'Manage programmes', 'programmes.manage'),
(14, 'Manage gallery', 'gallery.manage'),
(15, 'Manage staff', 'staff.manage'),
(16, 'Manage pages', 'pages.manage'),
(17, 'Manage homepage slides', 'slides.manage');

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

INSERT INTO role_permissions (role_id, permission_id)
SELECT 4, id FROM permissions WHERE slug IN (
    'content.manage','news.manage','events.manage','departments.manage','programmes.manage',
    'gallery.manage','staff.manage','pages.manage','slides.manage','messages.view','media.manage'
);

INSERT INTO role_permissions (role_id, permission_id)
SELECT 5, id FROM permissions WHERE slug IN (
    'departments.manage','programmes.manage','staff.manage','gallery.manage','media.manage'
);

INSERT INTO role_permissions (role_id, permission_id)
SELECT 6, id FROM permissions WHERE slug IN ('applications.manage','applications.view','messages.view');

INSERT INTO role_permissions (role_id, permission_id)
SELECT 7, id FROM permissions WHERE slug IN ('news.manage','events.manage','media.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT 8, id FROM permissions WHERE slug IN ('gallery.manage','media.manage');

INSERT INTO user_roles (user_id, role_id) SELECT id, role_id FROM users;

INSERT INTO users (name, email, password, role_id, department_id, status)
SELECT 'News Editor', 'news@slgti.ac.lk', '$2y$12$VTS6tXOOCeWyUSxbWwuPr.jB4levXkMS1roLGdIWgxYXm/z/utcme', id, NULL, 'active'
FROM roles WHERE slug = 'news_editor';

INSERT INTO users (name, email, password, role_id, department_id, status)
SELECT 'ICT Department Manager', 'ict@slgti.ac.lk', '$2y$12$VTS6tXOOCeWyUSxbWwuPr.jB4levXkMS1roLGdIWgxYXm/z/utcme', r.id, d.id, 'active'
FROM roles r JOIN departments d ON d.slug = 'information-communication-technology'
WHERE r.slug = 'department_manager';

INSERT IGNORE INTO user_roles (user_id, role_id) SELECT id, role_id FROM users;

INSERT INTO programme_requirements (programme_id, requirement, sort_order)
SELECT id, 'G.C.E. Ordinary Level with passes in relevant subjects.', 1 FROM programmes;
