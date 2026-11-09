-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: webslgti
-- ------------------------------------------------------
-- Server version	8.4.7

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `action` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_logs_user` (`user_id`),
  KEY `idx_logs_created` (`created_at`),
  CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,NULL,'contact.created','Contact message from parent.test@example.com','::1','2026-11-06 14:48:19'),(2,1,'auth.login','Signed in','::1','2026-11-06 14:48:30'),(3,NULL,'application.created','SLGTI-2026-B4C39E for Automobile Technology','::1','2026-11-06 14:49:37'),(4,2,'auth.login','Signed in','::1','2026-11-06 15:31:49'),(5,2,'auth.logout','Signed out','::1','2026-11-06 15:31:49'),(6,3,'auth.login','Signed in','::1','2026-11-06 15:31:49'),(7,2,'auth.login','Signed in','::1','2026-11-06 15:31:58'),(8,2,'auth.logout','Signed out','::1','2026-11-06 15:31:58'),(9,3,'auth.login','Signed in','::1','2026-11-06 15:31:59'),(10,3,'auth.logout','Signed out','::1','2026-11-06 15:31:59'),(11,1,'auth.login','Signed in','::1','2026-11-06 15:31:59'),(12,1,'applications.exported','Exported 8 applications','::1','2026-11-06 15:31:59'),(13,1,'application.created','SLGTI-2026-899CEE for Automobile Technology','::1','2026-11-06 15:31:59'),(14,1,'gallery-images.created','2 images uploaded','::1','2026-11-06 15:33:33'),(15,1,'auth.login','Signed in','::1','2026-11-06 15:39:18'),(16,1,'auth.login','Signed in','::1','2026-11-06 15:45:21'),(17,1,'auth.login','Signed in','::1','2026-11-09 10:00:07');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_documents`
--

DROP TABLE IF EXISTS `application_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `application_documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_documents_application` (`application_id`),
  CONSTRAINT `fk_documents_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_documents`
--

LOCK TABLES `application_documents` WRITE;
/*!40000 ALTER TABLE `application_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `applications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nic` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `programme_id` int unsigned DEFAULT NULL,
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
  KEY `idx_applications_status` (`status`,`created_at`),
  CONSTRAINT `fk_applications_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (1,'SLGTI-2026-A1B2C3','N. Krishanthi','200145678901','krishanthi.example@slgti.ac.lk','0771234567','Female','2001-04-12','Kilinochchi','Kilinochchi',1,'GCE O/L','O/L with Mathematics and Science','','pending',NULL,NULL,'2026-06-12 10:00:00'),(2,'SLGTI-2026-D4E5F6','S. Arun','199912345678','arun.example@slgti.ac.lk','0772345678','Male','1999-11-02','Jaffna','Jaffna',3,'GCE A/L','A/L Technology stream','','reviewing',NULL,NULL,'2026-07-03 10:00:00'),(3,'SLGTI-2026-G7H8I9','F. Ameera','200256789012','ameera.example@slgti.ac.lk','0773456789','Female','2002-08-21','Vavuniya','Vavuniya',7,'GCE O/L','O/L 6 passes','','accepted',NULL,NULL,'2026-08-19 10:00:00'),(4,'SLGTI-2026-J1K2L3','R. Dilshan','200078901234','dilshan.example@slgti.ac.lk','0774567890','Male','2000-01-30','Mannar','Mannar',5,'GCE O/L','O/L Mathematics B','','pending',NULL,NULL,'2026-09-02 10:00:00'),(5,'SLGTI-2026-M4N5O6','K. Tharshi','200367890123','tharshi.example@slgti.ac.lk','0775678901','Female','2003-03-18','Mullaitivu','Mullaitivu',9,'GCE O/L','O/L with ICT','','pending',NULL,NULL,'2026-10-11 10:00:00'),(6,'SLGTI-2026-P7Q8R9','P. Rajan','199834567890','rajan.example@slgti.ac.lk','0776789012','Male','1998-07-07','Kilinochchi','Kilinochchi',11,'NVQ Level 4','NVQ 4 Construction','','reviewing',NULL,NULL,'2026-10-28 10:00:00'),(7,'SLGTI-2026-S1T2U3','M. Inoshi','200412345678','inoshi.example@slgti.ac.lk','0777890123','Female','2004-12-01','Colombo','Colombo',4,'NVQ Level 4','NVQ 4 Electrical','','pending',NULL,NULL,'2026-11-02 10:00:00'),(8,'SLGTI-2026-B4C39E','Test Applicant','200012345678','applicant.test@example.com','0771234987','Female','2000-01-15','Kilinochchi town','Kilinochchi',1,'GCE O/L','O/L Maths and Science','Ready to train','pending',NULL,'::1','2026-11-06 14:49:37');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'School teacher','teacher.example@school.lk','0212223344','School visit request','We would like to bring Advanced Level students to see the workshops.','new','pending',NULL,'2026-11-01 09:00:00'),(2,'Parent','parent.example@mail.lk','0770001122','Hostel question','Is hostel accommodation available for a student from Mannar?','read','pending',NULL,'2026-10-22 09:00:00'),(3,'Test Parent','parent.test@example.com','0771112233','Hostel','Is a hostel place available for January','new','pending','::1','2026-11-06 14:48:19');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_departments_status` (`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Automotive & Agricultural Technology','automotive-agricultural-technology','Vehicle systems, farm machinery and practical diagnostics for the transport and agriculture sectors.','<p>The department trains technicians who can service modern vehicles and agricultural machinery. Students spend most of their time in workshops, moving from measurement and safety to diagnosis, repair and preventive maintenance.</p><p>Training follows the National Vocational Qualification and is arranged so that classroom theory is tested on real equipment.</p>','<ul><li>Automobile workshop</li><li>Farm machinery bay</li><li>Engine and electrical diagnostics benches</li></ul>','assets/images/dept-auto.jpg','bi-truck','Head of Department','auto@slgti.ac.lk','021 492 7799',1,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(2,'Electrical & Electronic Technology','electrical-electronic-technology','Power, installation, electronics and control systems for industry and building services.','<p>Students learn safe electrical installation, measurement, electronics and the control systems used in workshops and industry. The programme builds the discipline required for live systems and the judgement required for fault finding.</p>','<ul><li>Electrical installation bays</li><li>Electronics laboratory</li><li>Measurement and control benches</li></ul>','assets/images/dept-electrical.jpg','bi-lightning-charge','Head of Department','electrical@slgti.ac.lk','021 492 7799',2,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(3,'Mechanical Technology','mechanical-technology','Fitting, machining, welding and maintenance for production and service industries.','<p>Mechanical technology at SLGTI is taught through fitting, machining, fabrication and maintenance tasks. Students learn to read drawings, hold tolerances and work safely with machine tools.</p>','<ul><li>Machine shop</li><li>Fitting and fabrication bays</li><li>Metrology section</li></ul>','assets/images/dept-mechanical.jpg','bi-gear','Head of Department','mechanical@slgti.ac.lk','021 492 7799',3,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(4,'Food Technology','food-technology','Food processing, hygiene and quality control for Sri Lankan food industries.','<p>The department prepares technicians for processing, packaging and quality control. Hygiene, measurement and product safety are taught as daily workshop practice, not as a separate subject.</p>','<ul><li>Food processing laboratory</li><li>Quality testing benches</li><li>Hygiene training area</li></ul>','assets/images/dept-food.jpg','bi-cup-hot','Head of Department','food@slgti.ac.lk','021 492 7799',4,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(5,'Information & Communication Technology','information-communication-technology','Computing, networks and digital systems that support technical workplaces.','<p>ICT training covers computer systems, networks, software tools and the digital skills technical workplaces now expect. Students practise on laboratory equipment and project work.</p>','<ul><li>Computer laboratories</li><li>Network practice racks</li><li>Project studio</li></ul>','assets/images/dept-ict.jpg','bi-pc-display','Head of Department','ict@slgti.ac.lk','021 492 7799',5,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(6,'Construction Technology','construction-technology','Building trades, site practice and the technical skills used on civil works.','<p>Construction students learn setting out, building services and site practice. The department connects drawing, measurement and safe work on site.</p>','<ul><li>Construction yard</li><li>Materials laboratory</li><li>Drawing studio</li></ul>','assets/images/dept-construction.jpg','bi-building','Head of Department','construction@slgti.ac.lk','021 492 7799',6,'published','2026-11-06 14:47:20','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_categories`
--

DROP TABLE IF EXISTS `event_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_categories`
--

LOCK TABLES `event_categories` WRITE;
/*!40000 ALTER TABLE `event_categories` DISABLE KEYS */;
INSERT INTO `event_categories` VALUES (1,'Open Day','open-day'),(2,'Student Activity','student-activity'),(3,'Training','training');
/*!40000 ALTER TABLE `event_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
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
  KEY `idx_events_start` (`status`,`starts_at`),
  CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,1,'Campus Open Day 2026','campus-open-day-2026','Meet lecturers, walk through the workshops and ask about the next intake.','<p>Prospective students and families are welcome. Bring certificates if you would like informal advice on a programme. The open day does not replace the formal application.</p>','Main auditorium and workshops','assets/images/campus-auditorium.jpg','2026-12-05 09:00:00','2026-12-05 15:00:00',NULL,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(2,2,'Inter-department Sports Meet','inter-department-sports-meet','A one-day sports meet for enrolled students.','<p>Departments will field teams for track and team games. Student Affairs will publish the schedule two weeks before the meet.</p>','Campus grounds','assets/images/life-sports.jpg','2026-11-20 08:30:00','2026-11-20 16:30:00',NULL,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(3,3,'Industry Practice Briefing','industry-practice-briefing','A briefing for students preparing for industry exposure.','<p>The briefing covers conduct, safety and the records students must keep during industry exposure.</p>','Auditorium','assets/images/life-lab.jpg','2026-11-28 10:00:00','2026-11-28 12:00:00',NULL,'published','2026-11-06 14:47:20','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (1,'Workshops','Occupation workshops where students repeat the tasks of the NVQ standard.','assets/images/about-workshop.jpg','bi-tools',1,'published'),(2,'Laboratories','Electrical, electronics, food and ICT laboratories for measurement and testing.','assets/images/life-lab.jpg','bi-clipboard-data',2,'published'),(3,'Library','A quiet collection for technical reference, assignments and examination preparation.','assets/images/life-library.jpg','bi-book',3,'published'),(4,'Auditorium','Briefings, guest lectures and institute events.','assets/images/campus-auditorium.jpg','bi-easel',4,'published'),(5,'Hostels','Accommodation for students who train away from home.','assets/images/life-hostel.jpg','bi-house',5,'published'),(6,'Sports','Grounds and student activities organised with Student Affairs.','assets/images/life-sports.jpg','bi-trophy',6,'published');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'Who can apply to SLGTI?','<p>Young women and men from any district in Sri Lanka who meet the entry requirement of the selected NVQ programme.</p>',1,'published'),(2,'Which qualifications do you offer?','<p>NVQ Level 4 certificate programmes, NVQ Level 5 diplomas and NVQ Level 6 higher national diploma study.</p>',2,'published'),(3,'Is there student accommodation?','<p>Hostel places are available for enrolled students. Allocation is confirmed by Student Affairs after admission.</p>',3,'published'),(4,'How do I know my application was received?','<p>The online form shows a reference number immediately. Quote that number in every email or call to the admissions office.</p>',4,'published'),(5,'Where is the campus?','<p>Ariviyal Nagar, Kilinochchi 44000. The office is open Monday to Friday, 8:30 AM to 4:15 PM.</p>',5,'published');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Workshops','workshops','Practical training across the departments.','assets/images/gallery-1.jpg',1,'published','2026-11-06 14:47:20'),(2,'Campus life','campus-life','Students, sports and institute gatherings.','assets/images/gallery-6.jpg',2,'published','2026-11-06 14:47:20'),(3,'Industry exposure','industry-exposure','Training beyond the campus.','assets/images/gallery-4.jpg',3,'published','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `gallery_id` int unsigned NOT NULL,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`),
  KEY `fk_gallery_images` (`gallery_id`),
  CONSTRAINT `fk_gallery_images` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,1,'Mechanical workshop practice','assets/images/gallery-1.jpg',1,'published'),(2,1,'Technical instruction','assets/images/gallery-2.jpg',2,'published'),(3,1,'Engineering bench work','assets/images/gallery-4.jpg',3,'published'),(4,2,'Students in discussion','assets/images/gallery-6.jpg',1,'published'),(5,2,'Campus planning session','assets/images/gallery-3.jpg',2,'published'),(6,3,'Applied technology session','assets/images/gallery-5.jpg',1,'published');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `attempted_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_login_ip` (`ip_address`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int unsigned NOT NULL DEFAULT '0',
  `uploaded_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_media_user` (`uploaded_by`),
  CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`),
  KEY `fk_menu_items_parent` (`parent_id`),
  KEY `idx_menu_order` (`menu_id`,`sort_order`),
  CONSTRAINT `fk_menu_items_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_menu_items_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,NULL,'Home','/',1,'published'),(2,1,NULL,'About Us','/about',2,'published'),(3,1,2,'About SLGTI','/about',1,'published'),(4,1,2,'Principal\'s Message','/about#principal',2,'published'),(5,1,2,'The Management','/about#management',3,'published'),(6,1,2,'Facilities','/about#facilities',4,'published'),(7,1,2,'Quality Management','/page/quality-management',5,'published'),(8,1,2,'Green College','/page/green-college',6,'published'),(9,1,NULL,'Programmes','/programmes',3,'published'),(10,1,9,'NVQ Level 4','/programmes?level=nvq-4',1,'published'),(11,1,9,'NVQ Level 5','/programmes?level=nvq-5',2,'published'),(12,1,9,'NVQ Level 6','/programmes?level=nvq-6',3,'published'),(13,1,NULL,'Departments','/departments',4,'published'),(14,1,13,'Automotive & Agriculture','/departments/automotive-agricultural-technology',1,'published'),(15,1,13,'Electrical & Electronic','/departments/electrical-electronic-technology',2,'published'),(16,1,13,'Mechanical','/departments/mechanical-technology',3,'published'),(17,1,13,'Food Technology','/departments/food-technology',4,'published'),(18,1,13,'ICT','/departments/information-communication-technology',5,'published'),(19,1,13,'Construction','/departments/construction-technology',6,'published'),(20,1,NULL,'Admissions','/admissions',5,'published'),(21,1,20,'How to Apply','/admissions',1,'published'),(22,1,20,'Apply Now','/apply',2,'published'),(23,1,20,'Announcements','/news?category=admission',3,'published'),(24,1,NULL,'Students','/students',6,'published'),(25,1,NULL,'News & Events','/news',7,'published'),(26,1,NULL,'Contact Us','/contact',8,'published'),(30,2,NULL,'Home','/',1,'published'),(31,2,NULL,'About Us','/about',2,'published'),(32,2,NULL,'Programmes','/programmes',3,'published'),(33,2,NULL,'Admissions','/admissions',4,'published'),(34,2,NULL,'Students','/students',5,'published'),(35,2,NULL,'News','/news',6,'published'),(36,2,NULL,'Gallery','/gallery',7,'published'),(37,2,NULL,'Contact','/contact',8,'published');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Header','header'),(2,'Footer','footer');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
  `author_id` int unsigned DEFAULT NULL,
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
  KEY `idx_news_listing` (`status`,`published_at`),
  CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_news_category` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,3,NULL,'Interview Eligibility List – Admission 2026','interview-eligibility-list-admission-2026','The eligibility list for admission interviews is now published for shortlisted applicants.','<p>Shortlisted applicants for the 2026 intake should check the eligibility list and bring their NIC, educational certificates and the application reference number to the interview.</p><p>The admissions office will not accept incomplete documents. Applicants who cannot attend must inform the institute before the interview date.</p>','assets/images/news-3.jpg',1,'2026-10-20 09:00:00','published','Interview Eligibility List 2026','Admission interview eligibility list for SLGTI.',NULL,'2026-11-06 14:47:20','2026-11-06 14:47:20'),(2,2,NULL,'SLGTI Expo 2025 Successfully Concluded','slgti-expo-2025-successfully-concluded','Students, industry partners and schools joined the annual skills exhibition at Kilinochchi.','<p>The SLGTI Expo presented work from every department. Visitors saw automobile diagnostics, electrical installations, food processing, ICT projects and construction practice.</p><p>The exhibition is part of the institute commitment to show technical training as a first-choice pathway.</p>','assets/images/news-2.jpg',1,'2026-10-15 09:00:00','published','SLGTI Expo 2025','Report from the SLGTI skills exhibition.',NULL,'2026-11-06 14:47:20','2026-11-06 14:47:20'),(3,2,NULL,'Youth Skill Parade 2025 at SLGTI Kilinochchi','youth-skill-parade-2025','Students demonstrated occupational skills to schools and families from the Northern Province.','<p>The Youth Skill Parade gave school students a direct view of workshop training. Department staff explained entry requirements and the difference between NVQ Levels 4, 5 and 6.</p>','assets/images/news-1.jpg',0,'2026-09-18 09:00:00','published','Youth Skill Parade 2025','Skill parade at SLGTI Kilinochchi.',NULL,'2026-11-06 14:47:20','2026-11-06 14:47:20'),(4,2,NULL,'Kilinochchi Central College visits SLGTI','kilinochchi-central-college-visit','Advanced Level technology and arts students toured workshops and laboratories.','<p>Students of Kilinochchi Central College visited the campus to see practical training in automobile, electrical, mechanical, food, ICT and construction departments.</p>','assets/images/about-workshop.jpg',0,'2026-02-20 09:00:00','published','School visit to SLGTI','Kilinochchi Central College visit.',NULL,'2026-11-06 14:47:20','2026-11-06 14:47:20'),(5,2,NULL,'Prime Minister visits SLGTI','prime-minister-visits-slgti','Hon. Prime Minister Harini Amarasuriya visited the institute and met students and staff.','<p>The visit recognised the role of SLGTI in technical education for youth in the Northern Province and the wider country. Students demonstrated workshop tasks during the tour.</p>','assets/images/hero-1.jpg',1,'2026-02-16 09:00:00','published','Prime Minister visits SLGTI','Official visit to the Sri Lanka German Training Institute.',NULL,'2026-11-06 14:47:20','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_categories`
--

DROP TABLE IF EXISTS `news_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_categories`
--

LOCK TABLES `news_categories` WRITE;
/*!40000 ALTER TABLE `news_categories` DISABLE KEYS */;
INSERT INTO `news_categories` VALUES (1,'Announcement','announcement'),(2,'News','news'),(3,'Admission','admission'),(4,'Achievement','achievement');
/*!40000 ALTER TABLE `news_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'About SLGTI','about-slgti','A state technical training institute in Kilinochchi, shaped by German cooperation and Sri Lankan industry.','<p>Sri Lanka German Training Institute provides practice-oriented vocational training at NVQ Levels 4, 5 and 6. The institute was established to offer demand-oriented education for technical occupations and to support skilled employment for young women and men.</p><p>SLGTI stands for precise workshop habits, fair access and a direct relationship with employers. Classrooms, laboratories, workshops, hostels and an auditorium are arranged so that students can live and train on one campus.</p><h2 id=\"values\">How we train</h2><p>Every programme joins theory with repeated practice. Students are assessed on tasks they can perform, not only on what they can describe. Industry exposure is part of the pathway from the workshop to the workplace.</p>','About SLGTI','About the Sri Lanka German Training Institute in Kilinochchi.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(2,'Welcome from the Principal','principal-message','A welcome to students, families and industry partners.','<p>SLGTI exists so that young people can learn a technical occupation to a standard that employers recognise. Our workshops are the centre of the campus. I invite students to bring discipline and curiosity, and I invite industry to keep telling us what the workplace now requires.</p><p>We will continue to strengthen NVQ training, student welfare and the partnerships that make practical education credible.</p>','Principal message','Welcome from the Principal of SLGTI.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(3,'Quality Management','quality-management','How training quality is reviewed.','<p>Quality management at SLGTI covers curriculum delivery, assessment, workshop safety and student feedback. Departments review practical tasks against the NVQ standard and record corrective actions.</p><p>Students can raise academic concerns through Student Affairs. Serious safety issues should be reported to the department head the same day.</p>','Quality Management','Quality management at SLGTI.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(4,'Green College','green-college','A campus that treats energy, water and materials with care.','<p>The Green College practice asks departments to reduce waste, maintain equipment and teach students the environmental duties of a technician. Workshops separate scrap, switch off unused machines and treat chemicals and food waste according to the department procedure.</p>','Green College','Environmental practice at SLGTI.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(5,'Academic Calendar','academic-calendar','The shape of the training year.','<table><thead><tr><th>Period</th><th>Activity</th></tr></thead><tbody><tr><td>January</td><td>Orientation and workshop induction</td></tr><tr><td>February to October</td><td>Training, assessments and industry exposure</td></tr><tr><td>November</td><td>Final assessments and skill demonstrations</td></tr><tr><td>December</td><td>Open day and admissions advice</td></tr></tbody></table><p>Exact dates are confirmed by Academic Affairs for each intake.</p>','Academic calendar','Academic calendar for SLGTI.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(6,'Privacy Policy','privacy-policy','How SLGTI handles personal information.','<p>Application and contact forms collect only what the institute needs to respond. Records are stored in the institute database, accessed by authorised staff, and are not sold. You may ask the office to correct your contact details.</p><p>This website uses a session cookie so that forms and the administration panel can work securely. It is not used for advertising.</p>','Privacy Policy','Privacy policy of the SLGTI website.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(7,'Terms of Use','terms-of-use','Conditions for using this website.','<p>Content on this website is published for information about SLGTI programmes and services. Admission decisions are made by the institute, not by the website alone. Do not misuse forms, attempt to access the administration panel without authorisation, or copy workshop materials for commercial use.</p>','Terms of Use','Terms of use for the SLGTI website.','published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(8,'Accessibility','accessibility','Our commitment to an accessible website.','<p>Pages use semantic headings, visible focus, labels on form fields and text that can be resized. If you find a barrier, email info@slgti.ac.lk and describe the page and the problem. We will correct what we can.</p>','Accessibility','Accessibility statement for the SLGTI website.','published','2026-11-06 14:47:20','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reset_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Manage content','content.manage'),(2,'View messages','messages.view'),(3,'Manage applications','applications.manage'),(4,'View applications','applications.view'),(5,'Manage media','media.manage'),(6,'Manage users','users.manage'),(7,'Manage settings','settings.manage'),(8,'View logs','logs.view'),(9,'Manage backups','backups.manage'),(10,'Manage news','news.manage'),(11,'Manage events','events.manage'),(12,'Manage departments','departments.manage'),(13,'Manage programmes','programmes.manage'),(14,'Manage gallery','gallery.manage'),(15,'Manage staff','staff.manage'),(16,'Manage pages','pages.manage'),(17,'Manage homepage slides','slides.manage');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_levels`
--

DROP TABLE IF EXISTS `programme_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_levels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qualification` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_levels`
--

LOCK TABLES `programme_levels` WRITE;
/*!40000 ALTER TABLE `programme_levels` DISABLE KEYS */;
INSERT INTO `programme_levels` VALUES (1,'NVQ Level 4','Certificate','nvq-4','Certificate Level Technical Training','Craft-level training for school leavers who meet the subject requirements for the selected occupation.',1),(2,'NVQ Level 5','Diploma','nvq-5','Advanced Technical Training','Technician-level study for students who have completed a relevant Level 4 qualification or equivalent.',2),(3,'NVQ Level 6','Higher National Diploma','nvq-6','Higher National Diploma','Advanced technical education for students progressing from Level 5 or an accepted equivalent.',3);
/*!40000 ALTER TABLE `programme_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_requirements`
--

DROP TABLE IF EXISTS `programme_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_requirements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `programme_id` int unsigned NOT NULL,
  `requirement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_requirements_programme` (`programme_id`),
  CONSTRAINT `fk_requirements_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_requirements`
--

LOCK TABLES `programme_requirements` WRITE;
/*!40000 ALTER TABLE `programme_requirements` DISABLE KEYS */;
INSERT INTO `programme_requirements` VALUES (1,1,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(2,2,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(3,3,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(4,4,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(5,5,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(6,6,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(7,7,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(8,8,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(9,9,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(10,10,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(11,11,'G.C.E. Ordinary Level with passes in relevant subjects.',1),(12,12,'G.C.E. Ordinary Level with passes in relevant subjects.',1);
/*!40000 ALTER TABLE `programme_requirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programmes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `department_id` int unsigned NOT NULL,
  `level_id` int unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_requirements` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `career_outcomes` mediumtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_programmes_department` (`department_id`),
  KEY `fk_programmes_level` (`level_id`),
  KEY `idx_programmes_status` (`status`,`sort_order`),
  CONSTRAINT `fk_programmes_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `fk_programmes_level` FOREIGN KEY (`level_id`) REFERENCES `programme_levels` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programmes`
--

LOCK TABLES `programmes` WRITE;
/*!40000 ALTER TABLE `programmes` DISABLE KEYS */;
INSERT INTO `programmes` VALUES (1,1,1,'Automobile Technology','automobile-technology','Service, diagnosis and repair of light vehicles.','<p>Students learn workshop safety, engines, brakes, steering and electrical systems. Assessment is based on practical tasks and the NVQ standard for the occupation.</p>','12 months','<p>GCE Ordinary Level with passes in relevant subjects, including mathematics or science where specified for the intake.</p>','<p>Workshop technician, service advisor trainee, or progression to NVQ Level 5.</p>','assets/images/dept-auto.jpg',1,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(2,1,1,'Agricultural Machinery','agricultural-machinery','Operation and maintenance of farm machinery.','<p>Training covers engines, power transmission, field machinery and safe operation. Students practise maintenance routines used by farms and contractors.</p>','12 months','<p>GCE Ordinary Level. Interest in agriculture or machinery is expected.</p>','<p>Machinery technician in farms, cooperatives and equipment dealers.</p>','assets/images/dept-auto.jpg',2,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(3,2,1,'Electrical Technology','electrical-technology','Installation, testing and maintenance of electrical systems.','<p>The certificate develops safe installation practice, circuit reading and fault finding for domestic and light industrial systems.</p>','12 months','<p>GCE Ordinary Level with a pass in mathematics or science.</p>','<p>Electrical installer, maintenance assistant, or Level 5 progression.</p>','assets/images/dept-electrical.jpg',3,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(4,2,2,'Electronic Technology','electronic-technology','Electronics, measurement and basic control systems.','<p>Diploma study extends circuit theory into measurement, electronic repair and industrial control. Laboratory tasks are assessed against the NVQ standard.</p>','18 months','<p>NVQ Level 4 in a relevant electrical or electronic occupation, or an accepted equivalent.</p>','<p>Electronics technician in industry, service centres and building systems.</p>','assets/images/dept-electrical.jpg',4,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(5,3,1,'Mechanical Fitting and Machining','mechanical-fitting-machining','Fitting, measurement and basic machine-shop practice.','<p>Students learn hand fitting, drilling, turning and the measurement habits required in a production workshop.</p>','12 months','<p>GCE Ordinary Level. Workshop aptitude is assessed at interview.</p>','<p>Fitter, machinist trainee, or maintenance assistant.</p>','assets/images/dept-mechanical.jpg',5,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(6,3,2,'Mechanical Technology','mechanical-technology-diploma','Maintenance and production support at technician level.','<p>The diploma adds planning, technical drawing and maintenance management to practical machining and fabrication.</p>','18 months','<p>Relevant NVQ Level 4 or an accepted technician entry qualification.</p>','<p>Mechanical technician in manufacturing and facilities maintenance.</p>','assets/images/dept-mechanical.jpg',6,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(7,4,1,'Food Processing Technology','food-processing-technology','Processing, hygiene and basic quality checks.','<p>Students practise food handling, processing steps and the records a quality-conscious plant expects.</p>','12 months','<p>GCE Ordinary Level, including science where required for the intake.</p>','<p>Process operator or quality assistant in food manufacturing.</p>','assets/images/dept-food.jpg',7,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(8,4,2,'Food Technology','food-technology-diploma','Quality, production and product safety at diploma level.','<p>Diploma students connect processing practice with quality systems, testing and supervision of a production line.</p>','18 months','<p>NVQ Level 4 in food technology or an accepted equivalent.</p>','<p>Production supervisor trainee or quality technician.</p>','assets/images/dept-food.jpg',8,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(9,5,1,'Information and Communication Technology','ict-certificate','Computer systems, software tools and basic networks.','<p>Students build, maintain and use computer systems, and practise the software tools used in technical offices.</p>','12 months','<p>GCE Ordinary Level with passes in relevant subjects.</p>','<p>IT support assistant or progression to the diploma.</p>','assets/images/dept-ict.jpg',9,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(10,5,2,'ICT Technician','ict-technician','Networks, systems support and workplace digital projects.','<p>The diploma prepares technicians who can support networks, user systems and small digital projects.</p>','18 months','<p>NVQ Level 4 in ICT or an accepted equivalent.</p>','<p>ICT technician in education, industry or service companies.</p>','assets/images/dept-ict.jpg',10,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(11,6,1,'Construction Technology','construction-technology-certificate','Building trades, measurement and site safety.','<p>Students learn setting out, basic construction tasks and the safety rules of a working site.</p>','12 months','<p>GCE Ordinary Level. Physical readiness for site work is discussed at interview.</p>','<p>Site technician trainee or skilled worker in building trades.</p>','assets/images/dept-construction.jpg',11,'published','2026-11-06 14:47:20','2026-11-06 14:47:20'),(12,6,3,'Construction Technology Higher National Diploma','construction-hnd','Technical coordination for building and civil works.','<p>Level 6 study develops the judgement needed to read project information, coordinate trades and check quality on site.</p>','24 months','<p>NVQ Level 5 in construction or an accepted advanced entry qualification.</p>','<p>Technical officer or site supervisor trainee.</p>','assets/images/dept-construction.jpg',12,'published','2026-11-06 14:47:20','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `programmes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quick_links`
--

DROP TABLE IF EXISTS `quick_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quick_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#0B5CAB',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quick_links`
--

LOCK TABLES `quick_links` WRITE;
/*!40000 ALTER TABLE `quick_links` DISABLE KEYS */;
INSERT INTO `quick_links` VALUES (1,'Apply Now','Admission Information','bi-person-fill','#2F80ED','/apply',1,'published'),(2,'Student Information System','Academic & Student Services','bi-mortarboard-fill','#27AE60','https://www.slgti.ac.lk/',2,'published'),(3,'E-Learning','Access Learning Materials','bi-laptop','#7C3AED','https://www.slgti.ac.lk/',3,'published'),(4,'News & Events','Latest Updates','bi-calendar-event','#EF233C','/news',4,'published'),(5,'Gallery','Photos & Videos','bi-image','#F2C14E','/gallery',5,'published'),(6,'Contact Us','Get in Touch','bi-telephone-fill','#1ABC9C','/contact',6,'published');
/*!40000 ALTER TABLE `quick_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `role_id` int unsigned NOT NULL,
  `permission_id` int unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `fk_rp_permission` (`permission_id`),
  CONSTRAINT `fk_rp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(2,1),(4,1),(1,2),(2,2),(3,2),(4,2),(6,2),(1,3),(2,3),(6,3),(1,4),(3,4),(6,4),(1,5),(2,5),(4,5),(5,5),(7,5),(8,5),(1,6),(1,7),(1,8),(1,9),(1,10),(4,10),(7,10),(1,11),(4,11),(7,11),(1,12),(4,12),(5,12),(1,13),(4,13),(5,13),(1,14),(4,14),(5,14),(8,14),(1,15),(4,15),(5,15),(1,16),(4,16),(1,17),(4,17);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Administrator','super_admin'),(2,'Editor','editor'),(3,'Staff','staff'),(4,'Administrator','administrator'),(5,'Department Manager','department_manager'),(6,'Admissions Officer','admissions_officer'),(7,'News Editor','news_editor'),(8,'Gallery Manager','gallery_manager');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_group` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Sri Lanka German Training Institute','general','Site name','text'),(2,'tagline','Skills for a brighter future','general','Tagline','text'),(3,'meta_description','Sri Lanka German Training Institute in Kilinochchi offers NVQ Level 4, 5 and 6 technical and vocational programmes.','seo','Default meta description','textarea'),(4,'phone','021 492 7799','contact','Primary phone','text'),(5,'phone_alt','021 206 0137','contact','Alternate phone','text'),(6,'fax','021 206 0138','contact','Fax','text'),(7,'email','info@slgti.ac.lk','contact','Email','text'),(8,'address','Ariviyal Nagar, Kilinochchi 44000, Sri Lanka','contact','Address','textarea'),(9,'location_label','Kilinochchi, Sri Lanka','contact','Short location','text'),(10,'hours','Monday to Friday, 8:30 AM – 4:15 PM','contact','Office hours','text'),(11,'footer_text','Providing high quality technical and vocational education and training to empower youth for a successful career.','general','Footer text','textarea'),(12,'facebook_url','https://www.facebook.com/','social','Facebook URL','text'),(13,'youtube_url','https://www.youtube.com/@SLGTI-MEDIA','social','YouTube URL','text'),(14,'linkedin_url','https://www.linkedin.com/','social','LinkedIn URL','text'),(15,'sis_url','https://www.slgti.ac.lk/','links','Student Information System URL','text'),(16,'elearning_url','https://www.slgti.ac.lk/','links','E-Learning URL','text'),(17,'apply_open','1','admissions','Applications open','select'),(18,'stat_students','1200','homepage','Statistic: students','text'),(19,'stat_programmes','12','homepage','Statistic: programmes','text'),(20,'stat_departments','6','homepage','Statistic: departments','text'),(21,'stat_partners','40','homepage','Statistic: partners','text'),(22,'home_about_kicker','About SLGTI','homepage','About kicker','text'),(23,'home_about_title','Building Skills for a Better Tomorrow','homepage','About heading','text'),(24,'home_about_text','Sri Lanka German Training Institute (SLGTI) is a premier technical and vocational training institute established to provide industry-oriented training to youth in Sri Lanka. We are committed to developing skilled professionals through high-quality training, modern facilities and industry collaboration.','homepage','About paragraph','textarea'),(25,'home_about_image','assets/images/about-students.jpg','homepage','About photograph path','text'),(26,'home_about_button_label','Learn More About Us','homepage','About button label','text'),(27,'home_about_button_url','/about','homepage','About button URL','text'),(28,'home_programmes_kicker','Our Programmes','homepage','Programmes kicker','text'),(29,'home_programmes_title','NVQ Programmes','homepage','Programmes heading','text'),(30,'home_programmes_text','Industry-oriented training programmes for a successful career.','homepage','Programmes introduction','text');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sliders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `eyebrow` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_highlight` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_text` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Skills for a brighter future','Sri Lanka – German','Providing high quality technical and vocational education and training to empower youth for a successful career.','Training Institute','assets/images/hero-1.jpg','Explore Programmes','/programmes','Apply Now','/apply',1,'published'),(2,'German technology','Meet your future','Step into workshops built for practical NVQ training, industry exposure and a recognised technical qualification.','at SLGTI','assets/images/hero-2.jpg','Explore Programmes','/programmes','Apply Now','/apply',2,'published'),(3,'Practical training','Skills for a','Six departments. NVQ Levels 4, 5 and 6. A campus for young women and men preparing for technical careers.','brighter future','assets/images/hero-3.jpg','Explore Programmes','/programmes','Apply Now','/apply',3,'published');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int unsigned DEFAULT NULL,
  `bio` mediumtext COLLATE utf8mb4_unicode_ci,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_staff_department` (`department_id`),
  CONSTRAINT `fk_staff_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'Principal','Principal',NULL,'<p>The Principal leads academic standards, student welfare and the institute relationship with industry and public partners.</p>','principal@slgti.ac.lk',NULL,NULL,1,'published','2026-11-06 14:47:20'),(2,'Director Academic Affairs','Academic Affairs',NULL,'<p>Academic Affairs coordinates departments, assessments and the NVQ training calendar.</p>','academic@slgti.ac.lk',NULL,NULL,2,'published','2026-11-06 14:47:20'),(3,'Director Student Affairs','Student Affairs',NULL,'<p>Student Affairs supports hostels, welfare, discipline and student activities.</p>','students@slgti.ac.lk',NULL,NULL,3,'published','2026-11-06 14:47:20'),(4,'Head of ICT','Head of Department',5,'<p>Leads the Information and Communication Technology department.</p>','ict@slgti.ac.lk',NULL,NULL,4,'published','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'T. Jayaruban','Sewing Machine Technician Training','With this sewing machine technician programme and the opportunity I received from MAS to work at their factory, I am confident my future will be prosperous.',1,'published'),(2,'T. Ilham','Student, Sewing Machine Technician','During the first three months we learned theory at SLGTI. Afterward we went to MAS and learned the machines, the needles and how air compression is used in a factory.',2,'published'),(3,'Industry partner','Apparel sector','SLGTI students arrive with workshop discipline. They still have to learn our factory, but they already know how to listen, measure and work safely.',3,'published'),(4,'Graduate','Electrical technology','The hours on the installation bays mattered more than any single lesson. I left knowing how to test a circuit before I trusted it.',4,'published');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_id` int unsigned NOT NULL,
  `role_id` int unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,1),(3,5),(2,7);
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int unsigned NOT NULL,
  `department_id` int unsigned DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'SLGTI Administrator','admin@slgti.ac.lk','$2y$10$zCb9prSU1RgoF1cV8Em4juTM6GyzIoiqTkQYldx46ZneFh39J5n9q',1,NULL,'active','2026-11-09 10:00:07','2026-11-06 14:47:20'),(2,'News Editor','news@slgti.ac.lk','$2y$10$zCb9prSU1RgoF1cV8Em4juTM6GyzIoiqTkQYldx46ZneFh39J5n9q',7,NULL,'active','2026-11-06 15:31:58','2026-11-06 15:30:48'),(3,'ICT Department Manager','ict@slgti.ac.lk','$2y$10$zCb9prSU1RgoF1cV8Em4juTM6GyzIoiqTkQYldx46ZneFh39J5n9q',5,5,'active','2026-11-06 15:31:59','2026-11-06 15:30:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'SLGTI Media','Official video channel of the Sri Lanka German Training Institute.','https://www.youtube.com/@SLGTI-MEDIA','Campus',1,'published','2026-11-06 14:47:20');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-09 11:07:59
