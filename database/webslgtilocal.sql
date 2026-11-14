-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 14, 2026 at 09:58 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webslgti`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_logs_user` (`user_id`),
  KEY `idx_logs_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'auth.login', 'Signed in', '::1', '2026-11-09 14:32:32'),
(2, 1, 'auth.login', 'Signed in', '::1', '2026-11-09 14:33:59'),
(3, 1, 'auth.login', 'Signed in', '::1', '2026-11-14 12:57:33'),
(4, 1, 'galleries.reordered', 'Gallery album order updated', '::1', '2026-11-14 13:57:38'),
(5, 1, 'galleries.reordered', 'Gallery album order updated', '::1', '2026-11-14 13:57:39'),
(6, 1, 'pages.updated', 'Pages #2', '::1', '2026-11-14 14:55:16'),
(7, 1, 'pages.updated', 'Pages #2', '::1', '2026-11-14 14:55:36'),
(8, 1, 'settings.updated', 'Website settings updated', '::1', '2026-11-14 15:24:26'),
(9, 1, 'settings.updated', 'Website settings updated', '::1', '2026-11-14 15:24:32'),
(10, 1, 'settings.updated', 'Website settings updated', '::1', '2026-11-14 15:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nic` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `programme_id` int UNSIGNED DEFAULT NULL,
  `education_level` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `results` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_no` (`reference_no`),
  KEY `fk_applications_programme` (`programme_id`),
  KEY `idx_applications_status` (`status`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `reference_no`, `full_name`, `nic`, `email`, `phone`, `gender`, `date_of_birth`, `address`, `district`, `programme_id`, `education_level`, `results`, `message`, `status`, `admin_notes`, `ip_address`, `created_at`) VALUES
(1, 'SLGTI-2026-A1B2C3', 'N. Krishanthi', '200145678901', 'krishanthi.example@slgti.ac.lk', '0771234567', 'Female', '2001-04-12', 'Kilinochchi', 'Kilinochchi', 1, 'GCE O/L', 'O/L with Mathematics and Science', '', 'pending', NULL, NULL, '2026-06-12 10:00:00'),
(2, 'SLGTI-2026-D4E5F6', 'S. Arun', '199912345678', 'arun.example@slgti.ac.lk', '0772345678', 'Male', '1999-11-02', 'Jaffna', 'Jaffna', 3, 'GCE A/L', 'A/L Technology stream', '', 'reviewing', NULL, NULL, '2026-07-03 10:00:00'),
(3, 'SLGTI-2026-G7H8I9', 'F. Ameera', '200256789012', 'ameera.example@slgti.ac.lk', '0773456789', 'Female', '2002-08-21', 'Vavuniya', 'Vavuniya', 7, 'GCE O/L', 'O/L 6 passes', '', 'accepted', NULL, NULL, '2026-08-19 10:00:00'),
(4, 'SLGTI-2026-J1K2L3', 'R. Dilshan', '200078901234', 'dilshan.example@slgti.ac.lk', '0774567890', 'Male', '2000-01-30', 'Mannar', 'Mannar', 5, 'GCE O/L', 'O/L Mathematics B', '', 'pending', NULL, NULL, '2026-09-02 10:00:00'),
(5, 'SLGTI-2026-M4N5O6', 'K. Tharshi', '200367890123', 'tharshi.example@slgti.ac.lk', '0775678901', 'Female', '2003-03-18', 'Mullaitivu', 'Mullaitivu', 9, 'GCE O/L', 'O/L with ICT', '', 'pending', NULL, NULL, '2026-10-11 10:00:00'),
(6, 'SLGTI-2026-P7Q8R9', 'P. Rajan', '199834567890', 'rajan.example@slgti.ac.lk', '0776789012', 'Male', '1998-07-07', 'Kilinochchi', 'Kilinochchi', 11, 'NVQ Level 4', 'NVQ 4 Construction', '', 'reviewing', NULL, NULL, '2026-10-28 10:00:00'),
(7, 'SLGTI-2026-S1T2U3', 'M. Inoshi', '200412345678', 'inoshi.example@slgti.ac.lk', '0777890123', 'Female', '2004-12-01', 'Colombo', 'Colombo', 4, 'NVQ Level 4', 'NVQ 4 Electrical', '', 'pending', NULL, NULL, '2026-11-02 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

DROP TABLE IF EXISTS `application_documents`;
CREATE TABLE IF NOT EXISTS `application_documents` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id` int UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_documents_application` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `application_functions`
--

DROP TABLE IF EXISTS `application_functions`;
CREATE TABLE IF NOT EXISTS `application_functions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bi-box-arrow-up-right',
  `application_type` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'External application',
  `external_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_new_tab` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `publication_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application_functions_listing` (`publication_status`,`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_functions`
--

INSERT INTO `application_functions` (`id`, `name`, `description`, `icon`, `application_type`, `external_url`, `category`, `open_new_tab`, `status`, `publication_status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Online application', 'Configure the official online application service URL.', 'bi-box-arrow-up-right', 'External application', NULL, 'Admissions', 1, 'inactive', 'draft', 1, '2026-11-14 14:36:53', '2026-11-14 14:36:53'),
(2, 'Application status', 'Configure the official application status service URL.', 'bi-box-arrow-up-right', 'External application', NULL, 'Admissions', 1, 'inactive', 'draft', 2, '2026-11-14 14:36:53', '2026-11-14 14:36:53');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `reply_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_status` (`status`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `reply_status`, `ip_address`, `created_at`) VALUES
(1, 'School teacher', 'teacher.example@school.lk', '0212223344', 'School visit request', 'We would like to bring Advanced Level students to see the workshops.', 'new', 'pending', NULL, '2026-11-01 09:00:00'),
(2, 'Parent', 'parent.example@mail.lk', '0770001122', 'Hostel question', 'Is hostel accommodation available for a student from Mannar?', 'read', 'pending', NULL, '2026-10-22 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `facilities` mediumtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `head_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `canonical_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `structured_data` json DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `uq_departments_seo_slug` (`seo_slug`),
  KEY `idx_departments_status` (`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `slug`, `summary`, `description`, `facilities`, `image`, `icon`, `head_name`, `email`, `phone`, `seo_title`, `meta_description`, `meta_keywords`, `seo_slug`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `canonical_url`, `structured_data`, `focus_keyword`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Automotive & Agricultural Technology', 'automotive-agricultural-technology', 'Vehicle systems, farm machinery and practical diagnostics for the transport and agriculture sectors.', '<p>The department trains technicians who can service modern vehicles and agricultural machinery. Students spend most of their time in workshops, moving from measurement and safety to diagnosis, repair and preventive maintenance.</p><p>Training follows the National Vocational Qualification and is arranged so that classroom theory is tested on real equipment.</p>', '<ul><li>Automobile workshop</li><li>Farm machinery bay</li><li>Engine and electrical diagnostics benches</li></ul>', 'assets/images/dept-auto.jpg', 'bi-truck', 'Head of Department', 'auto@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 1, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(2, 'Electrical & Electronic Technology', 'electrical-electronic-technology', 'Power, installation, electronics and control systems for industry and building services.', '<p>Students learn safe electrical installation, measurement, electronics and the control systems used in workshops and industry. The programme builds the discipline required for live systems and the judgement required for fault finding.</p>', '<ul><li>Electrical installation bays</li><li>Electronics laboratory</li><li>Measurement and control benches</li></ul>', 'assets/images/dept-electrical.jpg', 'bi-lightning-charge', 'Head of Department', 'electrical@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 2, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(3, 'Mechanical Technology', 'mechanical-technology', 'Fitting, machining, welding and maintenance for production and service industries.', '<p>Mechanical technology at SLGTI is taught through fitting, machining, fabrication and maintenance tasks. Students learn to read drawings, hold tolerances and work safely with machine tools.</p>', '<ul><li>Machine shop</li><li>Fitting and fabrication bays</li><li>Metrology section</li></ul>', 'assets/images/dept-mechanical.jpg', 'bi-gear', 'Head of Department', 'mechanical@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 3, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(4, 'Food Technology', 'food-technology', 'Food processing, hygiene and quality control for Sri Lankan food industries.', '<p>The department prepares technicians for processing, packaging and quality control. Hygiene, measurement and product safety are taught as daily workshop practice, not as a separate subject.</p>', '<ul><li>Food processing laboratory</li><li>Quality testing benches</li><li>Hygiene training area</li></ul>', 'assets/images/dept-food.jpg', 'bi-cup-hot', 'Head of Department', 'food@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 4, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(5, 'Information & Communication Technology', 'information-communication-technology', 'Computing, networks and digital systems that support technical workplaces.', '<p>ICT training covers computer systems, networks, software tools and the digital skills technical workplaces now expect. Students practise on laboratory equipment and project work.</p>', '<ul><li>Computer laboratories</li><li>Network practice racks</li><li>Project studio</li></ul>', 'assets/images/dept-ict.jpg', 'bi-pc-display', 'Head of Department', 'ict@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 5, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(6, 'Construction Technology', 'construction-technology', 'Building trades, site practice and the technical skills used on civil works.', '<p>Construction students learn setting out, building services and site practice. The department connects drawing, measurement and safe work on site.</p>', '<ul><li>Construction yard</li><li>Materials laboratory</li><li>Drawing studio</li></ul>', 'assets/images/dept-construction.jpg', 'bi-building', 'Head of Department', 'construction@slgti.ac.lk', '021 492 7799', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, NULL, 6, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `department_slug_redirects`
--

DROP TABLE IF EXISTS `department_slug_redirects`;
CREATE TABLE IF NOT EXISTS `department_slug_redirects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_slug` (`old_slug`),
  KEY `fk_department_slug_redirect_department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `venue` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `registration_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_events_category` (`category_id`),
  KEY `idx_events_start` (`status`,`starts_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `category_id`, `title`, `slug`, `excerpt`, `body`, `venue`, `image`, `starts_at`, `ends_at`, `registration_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Campus Open Day 2026', 'campus-open-day-2026', 'Meet lecturers, walk through the workshops and ask about the next intake.', '<p>Prospective students and families are welcome. Bring certificates if you would like informal advice on a programme. The open day does not replace the formal application.</p>', 'Main auditorium and workshops', 'assets/images/campus-auditorium.jpg', '2026-12-05 09:00:00', '2026-12-05 15:00:00', NULL, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(2, 2, 'Inter-department Sports Meet', 'inter-department-sports-meet', 'A one-day sports meet for enrolled students.', '<p>Departments will field teams for track and team games. Student Affairs will publish the schedule two weeks before the meet.</p>', 'Campus grounds', 'assets/images/life-sports.jpg', '2026-11-20 08:30:00', '2026-11-20 16:30:00', NULL, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(3, 3, 'Industry Practice Briefing', 'industry-practice-briefing', 'A briefing for students preparing for industry exposure.', '<p>The briefing covers conduct, safety and the records students must keep during industry exposure.</p>', 'Auditorium', 'assets/images/life-lab.jpg', '2026-11-28 10:00:00', '2026-11-28 12:00:00', NULL, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

DROP TABLE IF EXISTS `event_categories`;
CREATE TABLE IF NOT EXISTS `event_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`id`, `name`, `slug`) VALUES
(1, 'Open Day', 'open-day'),
(2, 'Student Activity', 'student-activity'),
(3, 'Training', 'training');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `location` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `equipment` text COLLATE utf8mb4_unicode_ci,
  `availability_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'facilities',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `updated_at` datetime DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  PRIMARY KEY (`id`),
  KEY `idx_facilities_listing` (`status`,`availability_status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `title`, `slug`, `category`, `summary`, `description`, `location`, `capacity`, `equipment`, `availability_status`, `is_featured`, `display_location`, `image`, `image_alt`, `icon`, `sort_order`, `status`, `updated_at`, `seo_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `robots_index`, `robots_follow`) VALUES
(1, 'Workshops', 'facility-1', 'Campus facility', 'Occupation workshops where students repeat the tasks of the NVQ standard.', 'Occupation workshops where students repeat the tasks of the NVQ standard.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/about-workshop.jpg', 'Workshops', 'bi-tools', 1, 'published', '2026-11-14 14:26:11', 'Workshops | SLGTI', 'Occupation workshops where students repeat the tasks of the NVQ standard.', NULL, NULL, NULL, NULL, 'index', 'follow'),
(2, 'Laboratories', 'facility-2', 'Campus facility', 'Electrical, electronics, food and ICT laboratories for measurement and testing.', 'Electrical, electronics, food and ICT laboratories for measurement and testing.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/life-lab.jpg', 'Laboratories', 'bi-clipboard-data', 2, 'published', '2026-11-14 14:26:11', 'Laboratories | SLGTI', 'Electrical, electronics, food and ICT laboratories for measurement and testing.', NULL, NULL, NULL, NULL, 'index', 'follow'),
(3, 'Library', 'facility-3', 'Campus facility', 'A quiet collection for technical reference, assignments and examination preparation.', 'A quiet collection for technical reference, assignments and examination preparation.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/life-library.jpg', 'Library', 'bi-book', 3, 'published', '2026-11-14 14:26:11', 'Library | SLGTI', 'A quiet collection for technical reference, assignments and examination preparation.', NULL, NULL, NULL, NULL, 'index', 'follow'),
(4, 'Auditorium', 'facility-4', 'Campus facility', 'Briefings, guest lectures and institute events.', 'Briefings, guest lectures and institute events.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/campus-auditorium.jpg', 'Auditorium', 'bi-easel', 4, 'published', '2026-11-14 14:26:11', 'Auditorium | SLGTI', 'Briefings, guest lectures and institute events.', NULL, NULL, NULL, NULL, 'index', 'follow'),
(5, 'Hostels', 'facility-5', 'Campus facility', 'Accommodation for students who train away from home.', 'Accommodation for students who train away from home.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/life-hostel.jpg', 'Hostels', 'bi-house', 5, 'published', '2026-11-14 14:26:11', 'Hostels | SLGTI', 'Accommodation for students who train away from home.', NULL, NULL, NULL, NULL, 'index', 'follow'),
(6, 'Sports', 'facility-6', 'Campus facility', 'Grounds and student activities organised with Student Affairs.', 'Grounds and student activities organised with Student Affairs.', NULL, NULL, NULL, 'available', 0, 'facilities', 'assets/images/life-sports.jpg', 'Sports', 'bi-trophy', 6, 'published', '2026-11-14 14:26:11', 'Sports | SLGTI', 'Grounds and student activities organised with Student Affairs.', NULL, NULL, NULL, NULL, 'index', 'follow');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_faq_slug` (`slug`),
  KEY `idx_faq_listing` (`status`,`category`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `category`, `slug`, `answer`, `sort_order`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Who can apply to SLGTI?', 'General', 'faq-1', '<p>Young women and men from any district in Sri Lanka who meet the entry requirement of the selected NVQ programme.</p>', 1, 0, 'published', '2026-11-14 14:32:32', '2026-11-14 14:32:32'),
(2, 'Which qualifications do you offer?', 'General', 'faq-2', '<p>NVQ Level 4 certificate programmes, NVQ Level 5 diplomas and NVQ Level 6 higher national diploma study.</p>', 2, 0, 'published', '2026-11-14 14:32:32', '2026-11-14 14:32:32'),
(3, 'Is there student accommodation?', 'General', 'faq-3', '<p>Hostel places are available for enrolled students. Allocation is confirmed by Student Affairs after admission.</p>', 3, 0, 'published', '2026-11-14 14:32:32', '2026-11-14 14:32:32'),
(4, 'How do I know my application was received?', 'General', 'faq-4', '<p>The online form shows a reference number immediately. Quote that number in every email or call to the admissions office.</p>', 4, 0, 'published', '2026-11-14 14:32:32', '2026-11-14 14:32:32'),
(5, 'Where is the campus?', 'General', 'faq-5', '<p>Ariviyal Nagar, Kilinochchi 44000. The office is open Monday to Friday, 8:30 AM to 4:15 PM.</p>', 5, 0, 'published', '2026-11-14 14:32:32', '2026-11-14 14:32:32');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_id` int UNSIGNED DEFAULT NULL,
  `gallery_date` date DEFAULT NULL,
  `location` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photographer` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gallery',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `structured_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_galleries_listing` (`status`,`display_location`,`sort_order`),
  KEY `idx_galleries_event` (`event_id`),
  KEY `idx_galleries_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `slug`, `description`, `category`, `event_id`, `gallery_date`, `location`, `photographer`, `cover`, `is_featured`, `display_location`, `sort_order`, `status`, `seo_title`, `meta_description`, `focus_keyword`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `structured_data`, `created_at`, `updated_at`) VALUES
(1, 'Workshops', 'workshops', 'Practical training across the departments.', NULL, NULL, NULL, NULL, NULL, 'assets/images/gallery-1.jpg', 0, 'gallery', 1, 'published', 'Workshops | SLGTI', 'Practical training across the departments.', NULL, 'http://localhost/slgti/gallery/workshops', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-09 14:32:12', '2026-11-14 14:00:09'),
(2, 'Campus life', 'campus-life', 'Students, sports and institute gatherings.', NULL, NULL, NULL, NULL, NULL, 'assets/images/gallery-6.jpg', 0, 'gallery', 2, 'published', 'Campus life | SLGTI', 'Students, sports and institute gatherings.', NULL, 'http://localhost/slgti/gallery/campus-life', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-09 14:32:12', '2026-11-14 14:00:09'),
(3, 'Industry exposure', 'industry-exposure', 'Training beyond the campus.', NULL, NULL, NULL, NULL, NULL, 'assets/images/gallery-4.jpg', 0, 'gallery', 3, 'published', 'Industry exposure | SLGTI', 'Training beyond the campus.', NULL, 'http://localhost/slgti/gallery/industry-exposure', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-09 14:32:12', '2026-11-14 14:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` int UNSIGNED NOT NULL,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credits` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_images_listing` (`gallery_id`,`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `gallery_id`, `title`, `image`, `alt_text`, `credits`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mechanical workshop practice', 'assets/images/gallery-1.jpg', 'Mechanical workshop practice', NULL, 1, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22'),
(2, 1, 'Technical instruction', 'assets/images/gallery-2.jpg', 'Technical instruction', NULL, 2, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22'),
(3, 1, 'Engineering bench work', 'assets/images/gallery-4.jpg', 'Engineering bench work', NULL, 3, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22'),
(4, 2, 'Students in discussion', 'assets/images/gallery-6.jpg', 'Students in discussion', NULL, 1, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22'),
(5, 2, 'Campus planning session', 'assets/images/gallery-3.jpg', 'Campus planning session', NULL, 2, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22'),
(6, 3, 'Applied technology session', 'assets/images/gallery-5.jpg', 'Applied technology session', NULL, 1, 'published', '2026-11-14 13:54:22', '2026-11-14 13:54:22');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_slug_redirects`
--

DROP TABLE IF EXISTS `gallery_slug_redirects`;
CREATE TABLE IF NOT EXISTS `gallery_slug_redirects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_slug` (`old_slug`),
  KEY `fk_gallery_slug_redirect_gallery` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `attempted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_login_ip` (`ip_address`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int UNSIGNED NOT NULL DEFAULT '0',
  `uploaded_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_media_user` (`uploaded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `filename`, `original_name`, `path`, `mime`, `size`, `uploaded_by`, `created_at`) VALUES
(1, '0eeb35c8196f5013a8df8f202ccc5eda.png', 'IMG_0825.png', 'uploads/2026/11/0eeb35c8196f5013a8df8f202ccc5eda.png', 'image/png', 1785519, 1, '2026-11-14 14:55:16'),
(2, '62a3c6a884771ff4aa70b0e620d9b210.png', 'IMG_0825.png', 'uploads/2026/11/62a3c6a884771ff4aa70b0e620d9b210.png', 'image/png', 1785519, 1, '2026-11-14 14:55:36'),
(3, '7637552e582bb2451ef0f466038a28cf.png', 'logo_36f908c799a99ea27ea89c350f5ff6b0_2x.png', 'uploads/2026/11/7637552e582bb2451ef0f466038a28cf.png', 'image/png', 3910, 1, '2026-11-14 15:24:32'),
(4, 'fb6c109af09c09f3fabba12130688d98.png', 'logo.png', 'uploads/2026/11/fb6c109af09c09f3fabba12130688d98.png', 'image/png', 5972, 1, '2026-11-14 15:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `slug`) VALUES
(1, 'Header', 'header'),
(2, 'Footer', 'footer');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`),
  KEY `fk_menu_items_parent` (`parent_id`),
  KEY `idx_menu_order` (`menu_id`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `label`, `url`, `sort_order`, `status`) VALUES
(1, 1, NULL, 'Home', '/', 1, 'published'),
(2, 1, NULL, 'About Us', '/about', 2, 'published'),
(3, 1, 2, 'About SLGTI', '/about', 1, 'published'),
(4, 1, 2, 'Principal\'s Message', '/about#principal', 2, 'published'),
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

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED DEFAULT NULL,
  `author_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `meta_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_news_category` (`category_id`),
  KEY `fk_news_author` (`author_id`),
  KEY `idx_news_listing` (`status`,`published_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `body`, `image`, `is_featured`, `published_at`, `status`, `meta_title`, `meta_description`, `tags`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'Interview Eligibility List ? Admission 2026', 'interview-eligibility-list-admission-2026', 'The eligibility list for admission interviews is now published for shortlisted applicants.', '<p>Shortlisted applicants for the 2026 intake should check the eligibility list and bring their NIC, educational certificates and the application reference number to the interview.</p><p>The admissions office will not accept incomplete documents. Applicants who cannot attend must inform the institute before the interview date.</p>', 'assets/images/news-3.jpg', 1, '2026-10-20 09:00:00', 'published', 'Interview Eligibility List 2026', 'Admission interview eligibility list for SLGTI.', NULL, '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(2, 2, NULL, 'SLGTI Expo 2025 Successfully Concluded', 'slgti-expo-2025-successfully-concluded', 'Students, industry partners and schools joined the annual skills exhibition at Kilinochchi.', '<p>The SLGTI Expo presented work from every department. Visitors saw automobile diagnostics, electrical installations, food processing, ICT projects and construction practice.</p><p>The exhibition is part of the institute commitment to show technical training as a first-choice pathway.</p>', 'assets/images/news-2.jpg', 1, '2026-10-15 09:00:00', 'published', 'SLGTI Expo 2025', 'Report from the SLGTI skills exhibition.', NULL, '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(3, 2, NULL, 'Youth Skill Parade 2025 at SLGTI Kilinochchi', 'youth-skill-parade-2025', 'Students demonstrated occupational skills to schools and families from the Northern Province.', '<p>The Youth Skill Parade gave school students a direct view of workshop training. Department staff explained entry requirements and the difference between NVQ Levels 4, 5 and 6.</p>', 'assets/images/news-1.jpg', 0, '2026-09-18 09:00:00', 'published', 'Youth Skill Parade 2025', 'Skill parade at SLGTI Kilinochchi.', NULL, '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(4, 2, NULL, 'Kilinochchi Central College visits SLGTI', 'kilinochchi-central-college-visit', 'Advanced Level technology and arts students toured workshops and laboratories.', '<p>Students of Kilinochchi Central College visited the campus to see practical training in automobile, electrical, mechanical, food, ICT and construction departments.</p>', 'assets/images/about-workshop.jpg', 0, '2026-02-20 09:00:00', 'published', 'School visit to SLGTI', 'Kilinochchi Central College visit.', NULL, '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(5, 2, NULL, 'Prime Minister visits SLGTI', 'prime-minister-visits-slgti', 'Hon. Prime Minister Harini Amarasuriya visited the institute and met students and staff.', '<p>The visit recognised the role of SLGTI in technical education for youth in the Northern Province and the wider country. Students demonstrated workshop tasks during the tour.</p>', 'assets/images/hero-1.jpg', 1, '2026-02-16 09:00:00', 'published', 'Prime Minister visits SLGTI', 'Official visit to the Sri Lanka German Training Institute.', NULL, '2026-11-09 14:32:12', '2026-11-09 14:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

DROP TABLE IF EXISTS `news_categories`;
CREATE TABLE IF NOT EXISTS `news_categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `slug`) VALUES
(1, 'Announcement', 'announcement'),
(2, 'News', 'news'),
(3, 'Admission', 'admission'),
(4, 'Achievement', 'achievement');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `meta_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `structured_data` json DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_pages_listing` (`status`,`parent_id`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `category`, `excerpt`, `body`, `image`, `image_alt`, `parent_id`, `is_featured`, `sort_order`, `meta_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `focus_keyword`, `structured_data`, `status`, `created_at`, `updated_at`) VALUES
(1, 'About SLGTI', 'about-slgti', 'General', 'A state technical training institute in Kilinochchi, shaped by German cooperation and Sri Lankan industry.', '<p>Sri Lanka German Training Institute provides practice-oriented vocational training at NVQ Levels 4, 5 and 6. The institute was established to offer demand-oriented education for technical occupations and to support skilled employment for young women and men.</p><p>SLGTI stands for precise workshop habits, fair access and a direct relationship with employers. Classrooms, laboratories, workshops, hostels and an auditorium are arranged so that students can live and train on one campus.</p><h2 id=\"values\">How we train</h2><p>Every programme joins theory with repeated practice. Students are assessed on tasks they can perform, not only on what they can describe. Industry exposure is part of the pathway from the workshop to the workplace.</p>', NULL, 'About SLGTI', NULL, 0, 0, 'About SLGTI', 'About the Sri Lanka German Training Institute in Kilinochchi.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(2, 'Welcome from the Principal', 'principal-message', 'General', 'A welcome to students, families and industry partners.', '<p>SLGTI exists so that young people can learn a technical occupation to a standard that employers recognise. Our workshops are the centre of the campus. I invite students to bring discipline and curiosity, and I invite industry to keep telling us what the workplace now requires.</p>\r\n<p>We will continue to strengthen NVQ training, student welfare and the partnerships that make practical education credible.</p>', 'uploads/2026/11/0eeb35c8196f5013a8df8f202ccc5eda.png', 'Welcome from the Principal', NULL, 0, 0, 'Principal message', 'Welcome from the Principal of SLGTI.', 'http://localhost/slgti/page/principal-message', 'Principal message', 'Welcome from the Principal of SLGTI.', 'uploads/2026/11/62a3c6a884771ff4aa70b0e620d9b210.png', NULL, 'index', 'follow', '', NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:55:36'),
(3, 'Quality Management', 'quality-management', 'General', 'How training quality is reviewed.', '<p>Quality management at SLGTI covers curriculum delivery, assessment, workshop safety and student feedback. Departments review practical tasks against the NVQ standard and record corrective actions.</p><p>Students can raise academic concerns through Student Affairs. Serious safety issues should be reported to the department head the same day.</p>', NULL, 'Quality Management', NULL, 0, 0, 'Quality Management', 'Quality management at SLGTI.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(4, 'Green College', 'green-college', 'General', 'A campus that treats energy, water and materials with care.', '<p>The Green College practice asks departments to reduce waste, maintain equipment and teach students the environmental duties of a technician. Workshops separate scrap, switch off unused machines and treat chemicals and food waste according to the department procedure.</p>', NULL, 'Green College', NULL, 0, 0, 'Green College', 'Environmental practice at SLGTI.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(5, 'Academic Calendar', 'academic-calendar', 'General', 'The shape of the training year.', '<table><thead><tr><th>Period</th><th>Activity</th></tr></thead><tbody><tr><td>January</td><td>Orientation and workshop induction</td></tr><tr><td>February to October</td><td>Training, assessments and industry exposure</td></tr><tr><td>November</td><td>Final assessments and skill demonstrations</td></tr><tr><td>December</td><td>Open day and admissions advice</td></tr></tbody></table><p>Exact dates are confirmed by Academic Affairs for each intake.</p>', NULL, 'Academic Calendar', NULL, 0, 0, 'Academic calendar', 'Academic calendar for SLGTI.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(6, 'Privacy Policy', 'privacy-policy', 'General', 'How SLGTI handles personal information.', '<p>Application and contact forms collect only what the institute needs to respond. Records are stored in the institute database, accessed by authorised staff, and are not sold. You may ask the office to correct your contact details.</p><p>This website uses a session cookie so that forms and the administration panel can work securely. It is not used for advertising.</p>', NULL, 'Privacy Policy', NULL, 0, 0, 'Privacy Policy', 'Privacy policy of the SLGTI website.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(7, 'Terms of Use', 'terms-of-use', 'General', 'Conditions for using this website.', '<p>Content on this website is published for information about SLGTI programmes and services. Admission decisions are made by the institute, not by the website alone. Do not misuse forms, attempt to access the administration panel without authorisation, or copy workshop materials for commercial use.</p>', NULL, 'Terms of Use', NULL, 0, 0, 'Terms of Use', 'Terms of use for the SLGTI website.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35'),
(8, 'Accessibility', 'accessibility', 'General', 'Our commitment to an accessible website.', '<p>Pages use semantic headings, visible focus, labels on form fields and text that can be resized. If you find a barrier, email info@slgti.ac.lk and describe the page and the problem. We will correct what we can.</p>', NULL, 'Accessibility', NULL, 0, 0, 'Accessibility', 'Accessibility statement for the SLGTI website.', NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, NULL, 'published', '2026-11-09 14:32:12', '2026-11-14 14:29:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reset_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`) VALUES
(1, 'Manage content', 'content.manage'),
(2, 'View messages', 'messages.view'),
(3, 'Manage applications', 'applications.manage'),
(4, 'View applications', 'applications.view'),
(5, 'Manage media', 'media.manage'),
(6, 'Manage users', 'users.manage'),
(7, 'Manage settings', 'settings.manage'),
(8, 'View logs', 'logs.view'),
(9, 'Manage backups', 'backups.manage'),
(10, 'Manage news', 'news.manage'),
(11, 'Manage events', 'events.manage'),
(12, 'Manage departments', 'departments.manage'),
(13, 'Manage programmes', 'programmes.manage'),
(14, 'Manage gallery', 'gallery.manage'),
(15, 'Manage staff', 'staff.manage'),
(16, 'Manage pages', 'pages.manage'),
(17, 'Manage homepage slides', 'slides.manage');

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE IF NOT EXISTS `programmes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` int UNSIGNED NOT NULL,
  `level_id` int UNSIGNED NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_requirements` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `career_outcomes` mediumtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brochure_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_slug` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `structured_data` json DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `uq_programmes_seo_slug` (`seo_slug`),
  KEY `fk_programmes_department` (`department_id`),
  KEY `fk_programmes_level` (`level_id`),
  KEY `idx_programmes_status` (`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `department_id`, `level_id`, `title`, `slug`, `summary`, `description`, `duration`, `entry_requirements`, `career_outcomes`, `image`, `brochure_path`, `seo_title`, `meta_description`, `meta_keywords`, `seo_slug`, `focus_keyword`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `structured_data`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Automobile Technology', 'automobile-technology', 'Service, diagnosis and repair of light vehicles.', '<p>Students learn workshop safety, engines, brakes, steering and electrical systems. Assessment is based on practical tasks and the NVQ standard for the occupation.</p>', '12 months', '<p>GCE Ordinary Level with passes in relevant subjects, including mathematics or science where specified for the intake.</p>', '<p>Workshop technician, service advisor trainee, or progression to NVQ Level 5.</p>', 'assets/images/dept-auto.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 1, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(2, 1, 1, 'Agricultural Machinery', 'agricultural-machinery', 'Operation and maintenance of farm machinery.', '<p>Training covers engines, power transmission, field machinery and safe operation. Students practise maintenance routines used by farms and contractors.</p>', '12 months', '<p>GCE Ordinary Level. Interest in agriculture or machinery is expected.</p>', '<p>Machinery technician in farms, cooperatives and equipment dealers.</p>', 'assets/images/dept-auto.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 2, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(3, 2, 1, 'Electrical Technology', 'electrical-technology', 'Installation, testing and maintenance of electrical systems.', '<p>The certificate develops safe installation practice, circuit reading and fault finding for domestic and light industrial systems.</p>', '12 months', '<p>GCE Ordinary Level with a pass in mathematics or science.</p>', '<p>Electrical installer, maintenance assistant, or Level 5 progression.</p>', 'assets/images/dept-electrical.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 3, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(4, 2, 2, 'Electronic Technology', 'electronic-technology', 'Electronics, measurement and basic control systems.', '<p>Diploma study extends circuit theory into measurement, electronic repair and industrial control. Laboratory tasks are assessed against the NVQ standard.</p>', '18 months', '<p>NVQ Level 4 in a relevant electrical or electronic occupation, or an accepted equivalent.</p>', '<p>Electronics technician in industry, service centres and building systems.</p>', 'assets/images/dept-electrical.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 4, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(5, 3, 1, 'Mechanical Fitting and Machining', 'mechanical-fitting-machining', 'Fitting, measurement and basic machine-shop practice.', '<p>Students learn hand fitting, drilling, turning and the measurement habits required in a production workshop.</p>', '12 months', '<p>GCE Ordinary Level. Workshop aptitude is assessed at interview.</p>', '<p>Fitter, machinist trainee, or maintenance assistant.</p>', 'assets/images/dept-mechanical.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 5, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(6, 3, 2, 'Mechanical Technology', 'mechanical-technology-diploma', 'Maintenance and production support at technician level.', '<p>The diploma adds planning, technical drawing and maintenance management to practical machining and fabrication.</p>', '18 months', '<p>Relevant NVQ Level 4 or an accepted technician entry qualification.</p>', '<p>Mechanical technician in manufacturing and facilities maintenance.</p>', 'assets/images/dept-mechanical.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 6, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(7, 4, 1, 'Food Processing Technology', 'food-processing-technology', 'Processing, hygiene and basic quality checks.', '<p>Students practise food handling, processing steps and the records a quality-conscious plant expects.</p>', '12 months', '<p>GCE Ordinary Level, including science where required for the intake.</p>', '<p>Process operator or quality assistant in food manufacturing.</p>', 'assets/images/dept-food.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 7, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(8, 4, 2, 'Food Technology', 'food-technology-diploma', 'Quality, production and product safety at diploma level.', '<p>Diploma students connect processing practice with quality systems, testing and supervision of a production line.</p>', '18 months', '<p>NVQ Level 4 in food technology or an accepted equivalent.</p>', '<p>Production supervisor trainee or quality technician.</p>', 'assets/images/dept-food.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 8, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(9, 5, 1, 'Information and Communication Technology', 'ict-certificate', 'Computer systems, software tools and basic networks.', '<p>Students build, maintain and use computer systems, and practise the software tools used in technical offices.</p>', '12 months', '<p>GCE Ordinary Level with passes in relevant subjects.</p>', '<p>IT support assistant or progression to the diploma.</p>', 'assets/images/dept-ict.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 9, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(10, 5, 2, 'ICT Technician', 'ict-technician', 'Networks, systems support and workplace digital projects.', '<p>The diploma prepares technicians who can support networks, user systems and small digital projects.</p>', '18 months', '<p>NVQ Level 4 in ICT or an accepted equivalent.</p>', '<p>ICT technician in education, industry or service companies.</p>', 'assets/images/dept-ict.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 10, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(11, 6, 1, 'Construction Technology', 'construction-technology-certificate', 'Building trades, measurement and site safety.', '<p>Students learn setting out, basic construction tasks and the safety rules of a working site.</p>', '12 months', '<p>GCE Ordinary Level. Physical readiness for site work is discussed at interview.</p>', '<p>Site technician trainee or skilled worker in building trades.</p>', 'assets/images/dept-construction.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 11, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12'),
(12, 6, 3, 'Construction Technology Higher National Diploma', 'construction-hnd', 'Technical coordination for building and civil works.', '<p>Level 6 study develops the judgement needed to read project information, coordinate trades and check quality on site.</p>', '24 months', '<p>NVQ Level 5 in construction or an accepted advanced entry qualification.</p>', '<p>Technical officer or site supervisor trainee.</p>', 'assets/images/dept-construction.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow', NULL, 12, 'published', '2026-11-09 14:32:12', '2026-11-09 14:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `programme_levels`
--

DROP TABLE IF EXISTS `programme_levels`;
CREATE TABLE IF NOT EXISTS `programme_levels` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `learning_objectives` mediumtext COLLATE utf8mb4_unicode_ci,
  `entry_requirements` mediumtext COLLATE utf8mb4_unicode_ci,
  `progression_pathways` mediumtext COLLATE utf8mb4_unicode_ci,
  `career_opportunities` mediumtext COLLATE utf8mb4_unicode_ci,
  `duration` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `seo_slug` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `structured_data` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `uq_programme_levels_seo_slug` (`seo_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programme_levels`
--

INSERT INTO `programme_levels` (`id`, `code`, `name`, `slug`, `qualification`, `description`, `short_description`, `learning_objectives`, `entry_requirements`, `progression_pathways`, `career_opportunities`, `duration`, `image`, `icon`, `is_featured`, `status`, `seo_slug`, `seo_title`, `meta_description`, `focus_keyword`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `structured_data`, `created_at`, `updated_at`, `sort_order`) VALUES
(1, 'NVQ Level 4', 'Certificate', 'nvq-4', 'Certificate Level Technical Training', 'Craft-level training for school leavers who meet the subject requirements for the selected occupation.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'published', 'nvq-4', 'Certificate | SLGTI', 'Certificate qualification pathway at SLGTI.', NULL, 'http://localhost/slgti/programme-levels/nvq-4', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:39:32', '2026-11-14 13:40:50', 1),
(2, 'NVQ Level 5', 'Diploma', 'nvq-5', 'Advanced Technical Training', 'Technician-level study for students who have completed a relevant Level 4 qualification or equivalent.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'published', 'nvq-5', 'Diploma | SLGTI', 'Diploma qualification pathway at SLGTI.', NULL, 'http://localhost/slgti/programme-levels/nvq-5', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:39:32', '2026-11-14 13:40:50', 2),
(3, 'NVQ Level 6', 'Higher National Diploma', 'nvq-6', 'Higher National Diploma', 'Advanced technical education for students progressing from Level 5 or an accepted equivalent.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'published', 'nvq-6', 'Higher National Diploma | SLGTI', 'Higher National Diploma qualification pathway at SLGTI.', NULL, 'http://localhost/slgti/programme-levels/nvq-6', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:39:32', '2026-11-14 13:40:50', 3);

-- --------------------------------------------------------

--
-- Table structure for table `programme_level_slug_redirects`
--

DROP TABLE IF EXISTS `programme_level_slug_redirects`;
CREATE TABLE IF NOT EXISTS `programme_level_slug_redirects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_slug` (`old_slug`),
  KEY `fk_programme_level_slug_redirect_level` (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programme_requirements`
--

DROP TABLE IF EXISTS `programme_requirements`;
CREATE TABLE IF NOT EXISTS `programme_requirements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `programme_id` int UNSIGNED DEFAULT NULL,
  `requirement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int UNSIGNED DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `eligibility_criteria` mediumtext COLLATE utf8mb4_unicode_ci,
  `educational_qualifications` mediumtext COLLATE utf8mb4_unicode_ci,
  `required_documents` mediumtext COLLATE utf8mb4_unicode_ci,
  `application_instructions` mediumtext COLLATE utf8mb4_unicode_ci,
  `submission_method` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_deadline` datetime DEFAULT NULL,
  `contact_information` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  `structured_data` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_requirements_slug` (`slug`),
  KEY `fk_requirements_programme` (`programme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programme_requirements`
--

INSERT INTO `programme_requirements` (`id`, `programme_id`, `requirement`, `slug`, `category`, `department_id`, `short_description`, `description`, `eligibility_criteria`, `educational_qualifications`, `required_documents`, `application_instructions`, `submission_method`, `application_deadline`, `contact_information`, `image`, `document_path`, `is_featured`, `sort_order`, `status`, `seo_title`, `meta_description`, `focus_keyword`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`, `structured_data`, `created_at`, `updated_at`) VALUES
(1, 1, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(2, 2, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-2', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(3, 3, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-3', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(4, 4, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-4', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(5, 5, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-5', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(6, 6, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-6', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(7, 7, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-7', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(8, 8, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-8', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(9, 9, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-9', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(10, 10, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-10', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(11, 11, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-11', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00'),
(12, 12, 'G.C.E. Ordinary Level with passes in relevant subjects.', 'g-c-e-ordinary-level-with-passes-in-relevant-subjects-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 'published', 'G.C.E. Ordinary Level with passes in relevant subjects. | SLGTI', 'G.C.E. Ordinary Level with passes in relevant subjects.', NULL, 'http://localhost/slgti/requirements/g-c-e-ordinary-level-with-passes-in-relevant-subjects-12', NULL, NULL, NULL, NULL, 'index', 'follow', NULL, '2026-11-14 13:34:55', '2026-11-14 13:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `programme_slug_redirects`
--

DROP TABLE IF EXISTS `programme_slug_redirects`;
CREATE TABLE IF NOT EXISTS `programme_slug_redirects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `programme_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_slug` (`old_slug`),
  KEY `fk_programme_slug_redirect_programme` (`programme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

DROP TABLE IF EXISTS `quick_links`;
CREATE TABLE IF NOT EXISTS `quick_links` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General',
  `link_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'internal',
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#0B5CAB',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `display_style` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'card',
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'homepage',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_quick_links_display` (`status`,`display_location`,`sort_order`),
  KEY `idx_quick_links_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quick_links`
--

INSERT INTO `quick_links` (`id`, `title`, `subtitle`, `category`, `link_type`, `icon`, `color`, `url`, `target`, `display_style`, `display_location`, `is_featured`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Apply Now', 'Admission Information', 'Admissions', 'internal', 'bi-person-fill', '#2F80ED', '/apply', '_self', 'card', 'homepage_footer', 0, 1, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23'),
(2, 'Student Information System', 'Academic & Student Services', 'Student Services', 'external', 'bi-mortarboard-fill', '#27AE60', 'https://www.slgti.ac.lk/', '_blank', 'card', 'homepage_footer', 0, 2, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23'),
(3, 'E-Learning', 'Access Learning Materials', 'Student Services', 'external', 'bi-laptop', '#7C3AED', 'https://www.slgti.ac.lk/', '_blank', 'card', 'homepage_footer', 0, 3, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23'),
(4, 'News & Events', 'Latest Updates', 'General', 'internal', 'bi-calendar-event', '#EF233C', '/news', '_self', 'card', 'homepage_footer', 0, 4, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23'),
(5, 'Gallery', 'Photos & Videos', 'General', 'internal', 'bi-image', '#F2C14E', '/gallery', '_self', 'card', 'homepage_footer', 0, 5, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23'),
(6, 'Contact Us', 'Get in Touch', 'General', 'internal', 'bi-telephone-fill', '#1ABC9C', '/contact', '_self', 'card', 'homepage_footer', 0, 6, 'published', '2026-11-14 13:44:23', '2026-11-14 13:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `requirement_slug_redirects`
--

DROP TABLE IF EXISTS `requirement_slug_redirects`;
CREATE TABLE IF NOT EXISTS `requirement_slug_redirects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `requirement_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `old_slug` (`old_slug`),
  KEY `fk_requirement_slug_redirect_requirement` (`requirement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`) VALUES
(1, 'Super Administrator', 'super_admin'),
(2, 'Editor', 'editor'),
(3, 'Staff', 'staff'),
(4, 'Administrator', 'administrator'),
(5, 'Department Manager', 'department_manager'),
(6, 'Admissions Officer', 'admissions_officer'),
(7, 'News Editor', 'news_editor'),
(8, 'Gallery Manager', 'gallery_manager');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int UNSIGNED NOT NULL,
  `permission_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `fk_rp_permission` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(6, 2),
(1, 3),
(2, 3),
(6, 3),
(1, 4),
(3, 4),
(6, 4),
(1, 5),
(2, 5),
(4, 5),
(5, 5),
(7, 5),
(8, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(4, 10),
(7, 10),
(1, 11),
(4, 11),
(7, 11),
(1, 12),
(4, 12),
(5, 12),
(1, 13),
(4, 13),
(5, 13),
(1, 14),
(4, 14),
(5, 14),
(8, 14),
(1, 15),
(4, 15),
(5, 15),
(1, 16),
(4, 16),
(1, 17),
(4, 17);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_group` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `label`, `field_type`) VALUES
(1, 'site_name', 'Sri Lanka German Training Institute', 'general', 'Site name', 'text'),
(2, 'tagline', 'Skills for a brighter future', 'general', 'Tagline', 'text'),
(3, 'meta_description', 'Sri Lanka German Training Institute in Kilinochchi offers NVQ Level 4, 5 and 6 technical and vocational programmes.', 'seo', 'Default meta description', 'textarea'),
(4, 'phone', '021 492 7799', 'contact', 'Primary phone', 'text'),
(5, 'phone_alt', '021 206 0137', 'contact', 'Alternate phone', 'text'),
(6, 'fax', '021 206 0138', 'contact', 'Fax', 'text'),
(7, 'email', 'info@slgti.ac.lk', 'contact', 'Email', 'text'),
(8, 'address', 'Ariviyal Nagar, Kilinochchi 44000, Sri Lanka', 'contact', 'Address', 'textarea'),
(9, 'location_label', 'Kilinochchi, Sri Lanka', 'contact', 'Short location', 'text'),
(10, 'hours', 'Monday to Friday, 8:30 AM ? 4:15 PM', 'contact', 'Office hours', 'text'),
(11, 'footer_text', 'Providing high quality technical and vocational education and training to empower youth for a successful career.', 'general', 'Footer text', 'textarea'),
(12, 'facebook_url', 'https://www.facebook.com/', 'social', 'Facebook URL', 'text'),
(13, 'youtube_url', 'https://www.youtube.com/@SLGTI-MEDIA', 'social', 'YouTube URL', 'text'),
(14, 'linkedin_url', 'https://www.linkedin.com/', 'social', 'LinkedIn URL', 'text'),
(15, 'sis_url', 'https://www.slgti.ac.lk/', 'links', 'Student Information System URL', 'text'),
(16, 'elearning_url', 'https://www.slgti.ac.lk/', 'links', 'E-Learning URL', 'text'),
(17, 'apply_open', '1', 'admissions', 'Applications open', 'select'),
(18, 'stat_students', '1200', 'homepage', 'Statistic: students', 'text'),
(19, 'stat_programmes', '12', 'homepage', 'Statistic: programmes', 'text'),
(20, 'stat_departments', '6', 'homepage', 'Statistic: departments', 'text'),
(21, 'stat_partners', '40', 'homepage', 'Statistic: partners', 'text'),
(22, 'home_about_kicker', 'About SLGTI', 'homepage', 'About kicker', 'text'),
(23, 'home_about_title', 'Building Skills for a Better Tomorrow', 'homepage', 'About heading', 'text'),
(24, 'home_about_text', 'Sri Lanka German Training Institute (SLGTI) is a premier technical and vocational training institute established to provide industry-oriented training to youth in Sri Lanka. We are committed to developing skilled professionals through high-quality training, modern facilities and industry collaboration.', 'homepage', 'About paragraph', 'textarea'),
(25, 'home_about_image', 'assets/images/about-students.jpg', 'homepage', 'About photograph path', 'text'),
(26, 'home_about_button_label', 'Learn More About Us', 'homepage', 'About button label', 'text'),
(27, 'home_about_button_url', '/about', 'homepage', 'About button URL', 'text'),
(28, 'home_programmes_kicker', 'Our Programmes', 'homepage', 'Programmes kicker', 'text'),
(29, 'home_programmes_title', 'NVQ Programmes', 'homepage', 'Programmes heading', 'text'),
(30, 'home_programmes_text', 'Industry-oriented training programmes for a successful career.', 'homepage', 'Programmes introduction', 'text'),
(31, 'site_logo', 'uploads/2026/11/fb6c109af09c09f3fabba12130688d98.png', 'logo', 'Website logo', 'file'),
(32, 'site_logo_alt', 'Sri Lanka German Training Institute', 'logo', 'Logo alternative text', 'text'),
(33, 'site_logo_width', '220', 'logo', 'Logo display width', 'number'),
(34, 'footer_logo', '', 'footer', 'Footer logo', 'file'),
(35, 'footer_visible', '1', 'footer', 'Show footer', 'select'),
(36, 'footer_copyright', 'Sri Lanka German Training Institute (SLGTI), Kilinochchi. All rights reserved.', 'footer', 'Copyright text', 'text'),
(37, 'footer_website', 'https://www.slgti.ac.lk/', 'footer', 'Footer website URL', 'text'),
(38, 'site_logo_height', '52', 'logo', 'Logo maximum height', 'number'),
(39, 'favicon_16', 'uploads/favicons/f4d8552ca65d3c19fc2be0c0-16.png', 'favicon', 'Favicon 16px', 'file'),
(40, 'favicon_32', 'uploads/favicons/e54063179b46ad2acdd05e04-32.png', 'favicon', 'Favicon 32px', 'file'),
(41, 'favicon_48', 'uploads/favicons/29f096853f7e0017d739149d-48.png', 'favicon', 'Favicon 48px', 'file'),
(42, 'favicon_apple', 'uploads/favicons/c289b346439f0796b980ec61-180.png', 'favicon', 'Apple touch icon', 'file'),
(43, 'favicon_android', 'uploads/favicons/f9633115a23009cc4f650aaf-192.png', 'favicon', 'Android icon', 'file'),
(44, 'favicon_large', 'uploads/favicons/8553d9572e7a69a73494f3e8-512.png', 'favicon', 'Large favicon', 'file');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `eyebrow` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_highlight` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_text` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_alignment` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `text_position` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `overlay_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#0B5CAB',
  `overlay_opacity` tinyint UNSIGNED NOT NULL DEFAULT '35',
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'homepage',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sliders_schedule` (`status`,`display_location`,`starts_at`,`ends_at`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `eyebrow`, `title`, `subtitle`, `title_highlight`, `image`, `mobile_image`, `image_alt`, `button_text`, `button_url`, `secondary_text`, `secondary_url`, `text_alignment`, `text_position`, `overlay_color`, `overlay_opacity`, `display_location`, `sort_order`, `status`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(1, 'Skills for a brighter future', 'Sri Lanka ? German', 'Providing high quality technical and vocational education and training to empower youth for a successful career.', 'Training Institute', 'assets/images/hero-1.jpg', NULL, 'Sri Lanka ? German', 'Explore Programmes', '/programmes', 'Apply Now', '/apply', 'left', 'left', '#0B5CAB', 35, 'homepage', 1, 'published', NULL, NULL, '2026-11-14 13:48:08', '2026-11-14 13:48:08'),
(2, 'German technology', 'Meet your future', 'Step into workshops built for practical NVQ training, industry exposure and a recognised technical qualification.', 'at SLGTI', 'assets/images/hero-2.jpg', NULL, 'Meet your future', 'Explore Programmes', '/programmes', '', '', 'left', 'left', '#0B5CAB', 35, 'homepage', 2, 'published', NULL, NULL, '2026-11-14 13:48:08', '2026-11-14 13:48:08'),
(3, 'Practical training', 'Skills for a', 'Six departments. NVQ Levels 4, 5 and 6. A campus for young women and men preparing for technical careers.', 'brighter future', 'assets/images/hero-3.jpg', NULL, 'Skills for a', 'Explore Programmes', '/programmes', '', '', 'left', 'left', '#0B5CAB', 35, 'homepage', 3, 'published', NULL, NULL, '2026-11-14 13:48:08', '2026-11-14 13:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int UNSIGNED DEFAULT NULL,
  `bio` mediumtext COLLATE utf8mb4_unicode_ci,
  `qualifications` text COLLATE utf8mb4_unicode_ci,
  `expertise` text COLLATE utf8mb4_unicode_ci,
  `employment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `joining_date` date DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_location` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_management` tinyint(1) NOT NULL DEFAULT '0',
  `management_role` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'directory',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'noindex',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  PRIMARY KEY (`id`),
  KEY `fk_staff_department` (`department_id`),
  KEY `idx_staff_listing` (`status`,`department_id`,`sort_order`),
  KEY `idx_staff_management` (`is_management`,`status`,`display_location`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `slug`, `staff_id`, `position`, `category`, `section`, `department_id`, `bio`, `qualifications`, `expertise`, `employment_status`, `joining_date`, `email`, `phone`, `office_location`, `profile_url`, `image`, `image_alt`, `is_featured`, `is_management`, `management_role`, `display_location`, `sort_order`, `status`, `created_at`, `updated_at`, `seo_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `robots_index`, `robots_follow`) VALUES
(1, 'Principal', 'staff-1', 'SLGTI-0001', 'Principal', 'Staff', NULL, NULL, '<p>The Principal leads academic standards, student welfare and the institute relationship with industry and public partners.</p>', NULL, NULL, 'active', NULL, 'principal@slgti.ac.lk', NULL, NULL, NULL, NULL, 'Principal', 0, 1, 'Principal', 'both', 1, 'published', '2026-11-09 14:32:12', '2026-11-14 14:22:59', 'Principal | SLGTI', '<p>The Principal leads academic standards, student welfare and the institute relationship with industry and public partners.</p>', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(2, 'Director Academic Affairs', 'staff-2', 'SLGTI-0002', 'Academic Affairs', 'Staff', NULL, NULL, '<p>Academic Affairs coordinates departments, assessments and the NVQ training calendar.</p>', NULL, NULL, 'active', NULL, 'academic@slgti.ac.lk', NULL, NULL, NULL, NULL, 'Director Academic Affairs', 0, 1, 'Academic Affairs', 'both', 2, 'published', '2026-11-09 14:32:12', '2026-11-14 14:22:59', 'Director Academic Affairs | SLGTI', '<p>Academic Affairs coordinates departments, assessments and the NVQ training calendar.</p>', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(3, 'Director Student Affairs', 'staff-3', 'SLGTI-0003', 'Student Affairs', 'Staff', NULL, NULL, '<p>Student Affairs supports hostels, welfare, discipline and student activities.</p>', NULL, NULL, 'active', NULL, 'students@slgti.ac.lk', NULL, NULL, NULL, NULL, 'Director Student Affairs', 0, 1, 'Student Affairs', 'both', 3, 'published', '2026-11-09 14:32:12', '2026-11-14 14:22:59', 'Director Student Affairs | SLGTI', '<p>Student Affairs supports hostels, welfare, discipline and student activities.</p>', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(4, 'Head of ICT', 'staff-4', 'SLGTI-0004', 'Head of Department', 'Staff', NULL, 5, '<p>Leads the Information and Communication Technology department.</p>', NULL, NULL, 'active', NULL, 'ict@slgti.ac.lk', NULL, NULL, NULL, NULL, 'Head of ICT', 0, 1, 'Head of Department', 'both', 4, 'published', '2026-11-09 14:32:12', '2026-11-14 14:22:59', 'Head of ICT | SLGTI', '<p>Leads the Information and Communication Technology department.</p>', NULL, NULL, NULL, NULL, 'noindex', 'follow');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` int UNSIGNED DEFAULT NULL,
  `programme_id` int UNSIGNED DEFAULT NULL,
  `qualification` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduation_year` smallint UNSIGNED DEFAULT NULL,
  `organization` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` tinyint UNSIGNED DEFAULT NULL,
  `approval_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `display_location` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'homepage',
  `quote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'noindex',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  PRIMARY KEY (`id`),
  KEY `idx_testimonials_listing` (`status`,`approval_status`,`display_location`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `slug`, `role`, `designation`, `department_id`, `programme_id`, `qualification`, `graduation_year`, `organization`, `rating`, `approval_status`, `rejection_reason`, `is_featured`, `display_location`, `quote`, `profile_image`, `image_alt`, `sort_order`, `status`, `created_at`, `updated_at`, `seo_title`, `meta_description`, `canonical_url`, `og_title`, `og_description`, `og_image`, `robots_index`, `robots_follow`) VALUES
(1, 'T. Jayaruban', 'testimonial-1', 'Sewing Machine Technician Training', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, 0, 'both', 'With this sewing machine technician programme and the opportunity I received from MAS to work at their factory, I am confident my future will be prosperous.', NULL, NULL, 1, 'published', '2026-11-14 14:19:28', '2026-11-14 14:19:28', 'T. Jayaruban | SLGTI', 'With this sewing machine technician programme and the opportunity I received from MAS to work at their factory, I am confident my future will be prosperous.', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(2, 'T. Ilham', 'testimonial-2', 'Student, Sewing Machine Technician', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, 0, 'both', 'During the first three months we learned theory at SLGTI. Afterward we went to MAS and learned the machines, the needles and how air compression is used in a factory.', NULL, NULL, 2, 'published', '2026-11-14 14:19:28', '2026-11-14 14:19:28', 'T. Ilham | SLGTI', 'During the first three months we learned theory at SLGTI. Afterward we went to MAS and learned the machines, the needles and how air compression is used in a factory.', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(3, 'Industry partner', 'testimonial-3', 'Apparel sector', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, 0, 'both', 'SLGTI students arrive with workshop discipline. They still have to learn our factory, but they already know how to listen, measure and work safely.', NULL, NULL, 3, 'published', '2026-11-14 14:19:28', '2026-11-14 14:19:28', 'Industry partner | SLGTI', 'SLGTI students arrive with workshop discipline. They still have to learn our factory, but they already know how to listen, measure and work safely.', NULL, NULL, NULL, NULL, 'noindex', 'follow'),
(4, 'Graduate', 'testimonial-4', 'Electrical technology', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, 0, 'both', 'The hours on the installation bays mattered more than any single lesson. I left knowing how to test a circuit before I trusted it.', NULL, NULL, 4, 'published', '2026-11-14 14:19:28', '2026-11-14 14:19:28', 'Graduate | SLGTI', 'The hours on the installation bays mattered more than any single lesson. I left knowing how to test a circuit before I trusted it.', NULL, NULL, NULL, NULL, 'noindex', 'follow');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `department_id` int UNSIGNED DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `department_id`, `status`, `last_login_at`, `created_at`) VALUES
(1, 'SLGTI Administrator', 'admin@slgti.ac.lk', '$2y$12$soxuHZUO2wban8npyVC/7ebzJ.Una5TJvHU.Pq9BKaGjog5awJMd6', 1, NULL, 'active', '2026-11-14 12:57:33', '2026-11-09 14:32:12'),
(2, 'News Editor', 'news@slgti.ac.lk', '$2y$12$soxuHZUO2wban8npyVC/7ebzJ.Una5TJvHU.Pq9BKaGjog5awJMd6', 7, NULL, 'active', NULL, '2026-11-09 14:32:12'),
(3, 'ICT Department Manager', 'ict@slgti.ac.lk', '$2y$12$soxuHZUO2wban8npyVC/7ebzJ.Una5TJvHU.Pq9BKaGjog5awJMd6', 5, 5, 'active', NULL, '2026-11-09 14:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_user_roles_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(3, 5),
(2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'youtube',
  `video_id` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `embed_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_date` date DEFAULT NULL,
  `location` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credits` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `seo_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `focus_keyword` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_description` varchar(320) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `robots_index` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `robots_follow` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'follow',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_videos_slug` (`slug`),
  KEY `idx_videos_listing` (`status`,`sort_order`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `title`, `slug`, `description`, `video_url`, `provider`, `video_id`, `embed_url`, `thumbnail`, `duration`, `video_date`, `location`, `credits`, `is_featured`, `category`, `sort_order`, `status`, `created_at`, `updated_at`, `seo_title`, `meta_description`, `focus_keyword`, `canonical_url`, `og_title`, `og_description`, `og_image`, `twitter_image`, `robots_index`, `robots_follow`) VALUES
(1, 'SLGTI Media', 'video-1', 'Official video channel of the Sri Lanka German Training Institute.', 'https://www.youtube.com/@SLGTI-MEDIA', 'youtube', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Campus', 1, 'published', '2026-11-09 14:32:12', '2026-11-14 14:15:08', 'SLGTI Media | SLGTI', 'Official video channel of the Sri Lanka German Training Institute.', NULL, NULL, NULL, NULL, NULL, NULL, 'index', 'follow');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_applications_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `fk_documents_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_slug_redirects`
--
ALTER TABLE `department_slug_redirects`
  ADD CONSTRAINT `fk_department_slug_redirect_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `fk_gallery_images` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_slug_redirects`
--
ALTER TABLE `gallery_slug_redirects`
  ADD CONSTRAINT `fk_gallery_slug_redirect_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_menu_items_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `fk_programmes_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `fk_programmes_level` FOREIGN KEY (`level_id`) REFERENCES `programme_levels` (`id`);

--
-- Constraints for table `programme_level_slug_redirects`
--
ALTER TABLE `programme_level_slug_redirects`
  ADD CONSTRAINT `fk_programme_level_slug_redirect_level` FOREIGN KEY (`level_id`) REFERENCES `programme_levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_requirements`
--
ALTER TABLE `programme_requirements`
  ADD CONSTRAINT `fk_requirements_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_slug_redirects`
--
ALTER TABLE `programme_slug_redirects`
  ADD CONSTRAINT `fk_programme_slug_redirect_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requirement_slug_redirects`
--
ALTER TABLE `requirement_slug_redirects`
  ADD CONSTRAINT `fk_requirement_slug_redirect_requirement` FOREIGN KEY (`requirement_id`) REFERENCES `programme_requirements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_rp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
