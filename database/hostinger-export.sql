-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: highqdb
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_name` varchar(120) DEFAULT NULL,
  `user_email` varchar(190) DEFAULT NULL,
  `action` varchar(40) NOT NULL,
  `module` varchar(60) NOT NULL DEFAULT 'system',
  `entity_type` varchar(60) DEFAULT NULL,
  `entity_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(300) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_user` (`user_id`),
  KEY `idx_module` (`module`),
  KEY `idx_action` (`action`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_logs`
--

LOCK TABLES `admin_logs` WRITE;
/*!40000 ALTER TABLE `admin_logs` DISABLE KEYS */;
INSERT INTO `admin_logs` VALUES (1,NULL,'System',NULL,'login_failed','auth',NULL,NULL,'Failed login attempt for support@pust.edu.so','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:24:03'),(2,3,'ICT Admin','info@highqhomes.net','login','auth','user',3,'Signed in successfully','::1',NULL,'2026-07-14 17:24:32'),(3,3,'ICT Admin','info@highqhomes.net','logout','auth','user',3,'Signed out','::1',NULL,'2026-07-14 17:24:33'),(4,3,'ICT Admin','info@highqhomes.net','login','auth','user',3,'Signed in successfully','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:25:11'),(5,3,'ICT Admin','info@highqhomes.net','login','auth','user',3,'Signed in successfully','::1',NULL,'2026-07-14 17:29:21'),(6,3,'ICT Admin','info@highqhomes.net','logout','auth','user',3,'Signed out','::1',NULL,'2026-07-14 17:29:21'),(7,3,'ICT Admin','info@highqhomes.net','login','auth','user',3,'Signed in successfully','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 17:29:32'),(8,3,'ICT Admin','info@highqhomes.net','logout','auth','user',3,'Signed out','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:30:16'),(9,3,'ICT Admin','info@highqhomes.net','login','auth','user',3,'Signed in successfully','::1',NULL,'2026-07-17 13:24:00'),(10,3,'ICT Admin','info@highqhomes.net','logout','auth','user',3,'Signed out','::1',NULL,'2026-07-17 13:24:01'),(11,NULL,'System',NULL,'login_failed','auth',NULL,NULL,'Failed login attempt for info@highqhomes.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-08 23:04:33'),(12,NULL,'System',NULL,'login_failed','auth',NULL,NULL,'Failed login attempt for info@highqhomes.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-08 23:05:02'),(13,NULL,'System',NULL,'login_failed','auth',NULL,NULL,'Failed login attempt for info@highqhomes.com','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-08 23:05:53'),(14,1,'ICT Admin','info@highqhomes.site','login','auth','user',1,'Signed in successfully','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.18.9 Chrome/144.0.7559.236 Electron/40.10.3 Safari/537.36','2026-09-08 23:08:37'),(15,1,'ICT Admin','info@highqhomes.site','login','auth','user',1,'Signed in successfully','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-08 23:17:24'),(16,1,'ICT Admin','info@highqhomes.site','login','auth','user',1,'Signed in successfully','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.19.13 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','2026-09-09 00:26:15'),(17,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',2,'Toggled section \"capabilities\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:42:29'),(18,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',1,'Toggled section \"hero_trust\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:43:06'),(19,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',1,'Toggled section \"hero_trust\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:43:27'),(20,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',2,'Toggled section \"capabilities\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:43:30'),(21,1,'ICT Admin','info@highqhomes.site','logout','auth','user',1,'Signed out to access login page','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.19.13 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','2026-09-09 00:56:53'),(22,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',63,'Toggled section \"projects\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:58:11'),(23,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',64,'Toggled section \"testimonials\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:59:09'),(24,1,'ICT Admin','info@highqhomes.site','toggle','sections','section',64,'Toggled section \"testimonials\"','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 00:59:41'),(25,1,'ICT Admin','info@highqhomes.site','update','sections','section',11,'Updated section \"cta\" on header','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','2026-09-09 01:06:24');
/*!40000 ALTER TABLE `admin_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(400) NOT NULL,
  `category` varchar(80) DEFAULT NULL,
  `alt_text` varchar(200) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (49,'Luxury Residential Facade','Exterior view of a premium multi-story residential build in Garowe.','https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80','residential','Luxury residential building exterior in Garowe',1,1,'2026-07-14 17:20:55'),(50,'Residential Living Space','Finished living area with premium interior detailing.','https://images.unsplash.com/photo-1600210492486-724fe41c17f7?w=1200&q=80','interior','Modern residential living room interior',1,2,'2026-07-14 17:20:55'),(51,'Urban Apartment Complex','Contemporary apartment development with clean architectural lines.','https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80','residential','Urban apartment building exterior',1,3,'2026-07-14 17:20:55'),(52,'Apartment Interior Finish','Open-plan apartment interior with quality fittings.','https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80','interior','Apartment interior with premium finishes',1,4,'2026-07-14 17:20:55'),(53,'Corporate Office Building','Commercial office structure with modern glass and cladding.','https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80','commercial','Corporate office building exterior',1,5,'2026-07-14 17:20:55'),(54,'Office Workspace','Commercial interior workspace with professional fit-out.','https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=1200&q=80','commercial','Corporate office interior workspace',1,6,'2026-07-14 17:20:55'),(55,'Community Mosque Exterior','Completed community mosque with refined architectural form.','https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80','mosque','Community mosque exterior in Puntland',1,7,'2026-07-14 17:20:55'),(56,'Mosque Prayer Hall','Interior view showing acoustic finishes and lighting.','https://images.unsplash.com/photo-1564760055775-d63ef17a55c4?w=1200&q=80','interior','Mosque prayer hall interior',1,8,'2026-07-14 17:20:55'),(57,'Structural Build Phase','Reinforced concrete structure during active construction.','https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80','construction','Building under construction with concrete structure',1,9,'2026-07-14 17:20:55'),(58,'Site Planning Overview','Aerial perspective of a planned development site.','https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80','construction','Construction site aerial planning view',1,10,'2026-07-14 17:20:55'),(59,'Exterior Textured Finish','Textured exterior wall coating applied on site.','https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1200&q=80','exterior','Textured exterior wall finish on building',1,11,'2026-07-14 17:20:55'),(60,'Modern Villa Exterior','Standalone villa with landscaped approach and premium facade.','https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80','exterior','Modern villa exterior with landscaping',1,12,'2026-07-14 17:20:55'),(61,'Kitchen Fit-Out','Custom kitchen installation with stone and cabinetry.','https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1200&q=80','interior','Premium kitchen interior fit-out',1,13,'2026-07-14 17:20:55'),(62,'Master Bedroom Finish','Bedroom suite with coordinated finishes and lighting.','https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200&q=80','interior','Master bedroom with premium interior design',1,14,'2026-07-14 17:20:55'),(63,'Landscape & Hardscape','Outdoor hardscape and planting around a residential compound.','https://images.unsplash.com/photo-1558904541-efa843a96f01?w=1200&q=80','landscape','Residential landscape and hardscape design',1,15,'2026-07-14 17:20:55'),(64,'Commercial Entrance','Grand entrance detailing for a commercial development.','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80','commercial','Commercial building entrance and lobby area',1,16,'2026-07-14 17:20:55'),(65,'Premium Bathroom Finish','Bathroom fit-out with tile, fixtures, and ventilation.','https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=1200&q=80','finishing','Premium bathroom finishing details',1,17,'2026-07-14 17:20:55'),(66,'Staircase & Joinery','Custom staircase and joinery craftsmanship on site.','https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&q=80','finishing','Custom staircase and wood joinery',1,18,'2026-07-14 17:20:55'),(67,'High-Rise Progress','Multi-floor residential tower during finishing phase.','https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80','residential','High-rise residential tower construction progress',1,19,'2026-07-14 17:20:55'),(68,'Team on Site','HighQ Homes site team during quality inspection.','https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1200&q=80','construction','Construction team reviewing work on site',1,20,'2026-07-14 17:20:55'),(69,'Handover Walkthrough','Final client walkthrough before project handover.','https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80','finishing','Project handover walkthrough with client',1,21,'2026-07-14 17:20:55'),(70,'Retail Frontage','Completed retail frontage with signage-ready facade.','https://images.unsplash.com/photo-1449844908441-8829872d2607?w=1200&q=80','commercial','Retail commercial building frontage',1,22,'2026-07-14 17:20:55'),(71,'Compound Gate & Wall','Security wall and entrance gate for a residential compound.','https://images.unsplash.com/photo-1605276374102-dee2a0ed2cd6?w=1200&q=80','exterior','Residential compound gate and boundary wall',1,23,'2026-07-14 17:20:55'),(72,'Ceiling & Lighting Detail','Recessed lighting and ceiling finish in a commercial space.','https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&q=80','finishing','Ceiling and lighting finishing detail',1,24,'2026-07-14 17:20:55');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(180) DEFAULT NULL,
  `original_name` varchar(220) DEFAULT NULL,
  `file_path` varchar(400) NOT NULL,
  `file_type` varchar(80) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` int(10) unsigned DEFAULT NULL,
  `alt_text` varchar(220) DEFAULT NULL,
  `caption` varchar(300) DEFAULT NULL,
  `folder` varchar(80) DEFAULT 'media',
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_usage`
--

DROP TABLE IF EXISTS `media_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_usage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `entity_type` varchar(60) NOT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  `field_name` varchar(80) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_media` (`media_id`),
  KEY `idx_entity` (`entity_type`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_usage`
--

LOCK TABLES `media_usage` WRITE;
/*!40000 ALTER TABLE `media_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(120) NOT NULL,
  `url` varchar(300) NOT NULL,
  `location` enum('primary','footer') NOT NULL DEFAULT 'primary',
  `parent_id` int(10) unsigned DEFAULT NULL,
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `location` (`location`,`is_published`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Home','/','primary',NULL,'_self',1,1,'2026-07-14 17:18:53'),(2,'About','/about','primary',NULL,'_self',1,2,'2026-07-14 17:18:53'),(3,'Services','/services','primary',NULL,'_self',1,3,'2026-07-14 17:18:53'),(4,'Projects','/projects','primary',NULL,'_self',1,4,'2026-07-14 17:18:53'),(5,'Paints','/paints','primary',NULL,'_self',1,5,'2026-07-14 17:18:53'),(6,'Gallery','/gallery','primary',NULL,'_self',1,6,'2026-07-14 17:18:53'),(7,'Contact','/contact','primary',NULL,'_self',1,7,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_starred` tinyint(1) NOT NULL DEFAULT 0,
  `reply` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_key` varchar(60) NOT NULL,
  `section_key` varchar(80) NOT NULL,
  `title` varchar(220) DEFAULT NULL,
  `subtitle` varchar(300) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `image_url` varchar(400) DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_page_section` (`page_key`,`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_sections`
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` VALUES (1,'home','hero_trust','Trust Indicators','','','[\"Licensed & Insured\", \"On-Time Delivery\", \"Premium Finishes\", \"Structured Quality Checks\", \"Transparent Pricing\", \"Dedicated Client Support\"]',NULL,1,1,'2026-09-09 00:43:27'),(2,'home','capabilities','What We Build','Spaces Crafted With Purpose','From family homes to commercial landmarks ? every project is planned, built, and finished to premium standards.',NULL,NULL,1,2,'2026-09-09 00:43:30'),(3,'home','why_us','Why Choose Us','The HighQ Homes Difference','Six principles that guide every project ? from first consultation to final handover.','[{\"icon\": \"bi-award\", \"title\": \"Exceptional Quality\", \"text\": \"Durable, safe, and aesthetically considered work that meets high standards.\"}, {\"icon\": \"bi-leaf\", \"title\": \"Sustainable Practices\", \"text\": \"Environmentally considerate materials and energy-efficient design principles.\"}, {\"icon\": \"bi-chat-square-heart\", \"title\": \"Customer Experience\", \"text\": \"Transparent communication, personalized service, and timely completion.\"}, {\"icon\": \"bi-people\", \"title\": \"Lasting Relationships\", \"text\": \"Trusted partnerships with clients, suppliers, and stakeholders.\"}]',NULL,1,3,'2026-09-08 22:40:02'),(4,'home','process','Our Process','From Vision to Handover','A structured, transparent path designed to reduce risk and deliver exceptional results.','[{\"num\": \"01\", \"title\": \"Consulting\", \"text\": \"High-quality ideas for planning and budgeting the project under consultation.\"}, {\"num\": \"02\", \"title\": \"Construction\", \"text\": \"Honest, transparent, and ethical conduct throughout every project interaction.\"}, {\"num\": \"03\", \"title\": \"Renovation\", \"text\": \"Innovative solutions and approaches to improve existing work.\"}]',NULL,1,4,'2026-09-08 22:40:02'),(5,'home','excellence','Built to Last','Construction Excellence You Can Measure','Every HighQ Homes project is managed with the same standard ? rigorous planning, accountable execution, and finishes that stand up to daily use and time.','[{\"icon\": \"bi-bricks\", \"title\": \"Quality Materials\", \"text\": \"We specify proven materials selected for strength, finish, and long-term performance in local conditions.\"}, {\"icon\": \"bi-hard-hat\", \"title\": \"Site Safety\", \"text\": \"Disciplined site practices, protective standards, and organized workflows on every active project.\"}, {\"icon\": \"bi-clipboard-data\", \"title\": \"Documented Delivery\", \"text\": \"Milestone reports, approvals, and handover documentation you can reference with confidence.\"}, {\"icon\": \"bi-house-check\", \"title\": \"After-Handover Care\", \"text\": \"Responsive support after completion so your space continues to perform as intended.\"}]',NULL,1,5,NULL),(6,'home','connect','Start With Confidence','Your Project Deserves a Builder You Can Trust','HighQ Homes combines disciplined project management, skilled craftsmanship, and transparent communication ? so you stay informed from the first conversation to final handover.','[{\"icon\": \"bi-file-earmark-check\", \"title\": \"Transparent Quotes\", \"text\": \"Clear scope and pricing before any work begins.\"}, {\"icon\": \"bi-camera-reels\", \"title\": \"Progress Visibility\", \"text\": \"Regular updates so you always know project status.\"}, {\"icon\": \"bi-shield-lock\", \"title\": \"Quality Control\", \"text\": \"Structured inspections at every critical build phase.\"}]',NULL,1,6,NULL),(7,'home','faq','Questions & Answers','Everything You Need to Know Before You Build','Clear answers to the questions clients ask most ? so you can plan your project with confidence.','[{\"q\": \"How do I get a project quote?\", \"a\": \"Share your site details, scope, and timeline via WhatsApp, phone, or our contact form. We respond with a structured consultation and transparent proposal.\", \"icon\": \"bi-calculator\"}, {\"q\": \"What types of projects do you handle?\", \"a\": \"HighQ Homes delivers residential homes, commercial spaces, renovations, premium finishing, and design-to-build planning for clients across Somalia.\", \"icon\": \"bi-buildings\"}, {\"q\": \"How long does a typical build take?\", \"a\": \"Timelines depend on scope, materials, and site conditions. After discovery, we provide a milestone schedule with clear dates and progress checkpoints.\", \"icon\": \"bi-calendar2-week\"}, {\"q\": \"Can I track progress during construction?\", \"a\": \"Yes. We provide regular site updates and milestone reviews so you stay informed at every major phase of the project.\", \"icon\": \"bi-camera-reels\"}, {\"q\": \"Do you manage finishing and interior details?\", \"a\": \"Absolutely. From structural work to paints and refined interior finishes, we offer integrated delivery for a complete, move-in-ready result.\", \"icon\": \"bi-brush\"}]',NULL,1,7,'2026-09-09 00:57:17'),(8,'about','values','Our Corporate Principles','Customer satisfaction and quality above everything else.','Our work is guided by customer commitment, quality, accountable delivery, and integrity.','[{\"icon\": \"bi-people\", \"title\": \"Customer Commitment\", \"text\": \"We build relationships that make a positive difference in our customers lives.\"}, {\"icon\": \"bi-award\", \"title\": \"Quality\", \"text\": \"We provide outstanding service and products that deliver premium value.\"}, {\"icon\": \"bi-calendar2-check\", \"title\": \"Accountable Delivery\", \"text\": \"We are personally accountable for delivering commitments on time and on budget.\"}, {\"icon\": \"bi-shield-check\", \"title\": \"Integrity\", \"text\": \"We uphold high standards of integrity in all our actions.\"}]',NULL,1,3,'2026-09-09 01:03:02'),(9,'about','process','How We Work','Simple steps, clear delivery','A structured path that keeps your project transparent, controlled, and on schedule.','[{\"num\": \"01\", \"title\": \"Consultation\", \"text\": \"We learn your goals, site conditions, and budget expectations.\"}, {\"num\": \"02\", \"title\": \"Planning\", \"text\": \"Drawings, materials, and a milestone schedule you can track.\"}, {\"num\": \"03\", \"title\": \"Construction\", \"text\": \"Disciplined site execution with quality checks at every phase.\"}, {\"num\": \"04\", \"title\": \"Handover\", \"text\": \"Final walkthrough, documentation, and after-care support.\"}]',NULL,1,4,'2026-07-14 17:20:42'),(10,'contact','hero','Contact Us','Let\'s Plan Your Next Project','Share your site details, drawings, or goals ? our team will respond with clear next steps and estimates.',NULL,NULL,1,1,'2026-09-08 22:40:02'),(11,'header','cta','Contact US','/contact','Hello HighQ Homes, I would like to discuss a construction project.','[]','',1,1,'2026-09-09 01:06:24'),(12,'footer','about','About HighQ Homes','Premium Construction','HighQ Homes delivers premium residential and commercial construction across Puntland with transparent delivery and lasting quality.',NULL,NULL,1,1,'2026-09-08 22:40:02'),(25,'about','hero','HighQ Homes','Registered construction company in Garowe since 2016','Founded by Bashir Mohamed and Yahye Ismail, HighQ Homes creates durable, modern residential and commercial properties for Puntland.','{\"chips\":[{\"icon\":\"bi-calendar2-check\",\"label\":\"Established 2016\"},{\"icon\":\"bi-geo-alt\",\"label\":\"Garowe, Puntland\"},{\"icon\":\"bi-award\",\"label\":\"Residential & commercial\"}],\"snapshot_title\":\"Company snapshot\",\"snapshot_link\":\"Read our story\",\"button_text\":\"View our work\",\"button_text_2\":\"Get a quote\"}',NULL,1,1,'2026-09-09 01:03:02'),(26,'about','history','Our History','Built in Garowe. Focused on quality.','HighQ Homes was established in 2016 in Garowe City, Puntland State of Somalia. The company was founded to create reputable, high-quality residential properties and has grown through meticulous planning, quality materials, skilled craftsmanship, sustainability, and modern design.','{\"facts\": [{\"label\": \"Established\", \"value\": \"2016\"}, {\"label\": \"Location\", \"value\": \"Garowe, Puntland\"}, {\"label\": \"Founders\", \"value\": \"Bashir Mohamed and Yahye Ismail\"}], \"timeline\": [{\"year\": \"2016\", \"text\": \"HighQ Homes was established in Garowe by Bashir Mohamed and Yahye Ismail.\"}, {\"year\": \"Today\", \"text\": \"We continue to deliver residential and commercial projects with quality, transparency, and sustainable design principles.\"}]}',NULL,1,2,'2026-07-17 13:23:41'),(29,'services','hero','Consulting, Construction and Renovation','Practical planning. Quality delivery. Lasting value.','HighQ Homes supports Garowe clients with clear project consulting, transparent construction, and thoughtful renovation services.',NULL,NULL,1,1,'2026-07-17 13:23:41'),(30,'services','intro','What We Deliver','One accountable team from planning to improvement.','We bring together practical advice, quality construction, and renovation solutions designed around your project needs.',NULL,NULL,1,2,'2026-07-17 13:23:41'),(31,'services','process','How We Work','From first meeting to final handover','A proven delivery path designed to reduce risk and keep your build on schedule.','[{\"num\": \"01\", \"title\": \"Consultation\", \"text\": \"We review your goals, site, budget, and timeline expectations.\"}, {\"num\": \"02\", \"title\": \"Design & Scope\", \"text\": \"Drawings, materials, and a transparent scope with milestone pricing.\"}, {\"num\": \"03\", \"title\": \"Build & QA\", \"text\": \"Site execution with structured quality checks and progress updates.\"}, {\"num\": \"04\", \"title\": \"Handover\", \"text\": \"Final walkthrough, documentation, and after-care support.\"}]',NULL,1,3,NULL),(32,'services','faq','Service Questions','Answers before you start','Common questions about how HighQ Homes plans, prices, and delivers each service.','[{\"q\": \"Do you offer design-only services?\", \"a\": \"Yes. We provide architecture, exterior, interior, and site planning as standalone or integrated packages.\", \"icon\": \"bi-rulers\"}, {\"q\": \"Can I combine multiple services in one project?\", \"a\": \"Absolutely. Most clients choose integrated delivery ? design, construction, and finishing under one team.\", \"icon\": \"bi-layers\"}, {\"q\": \"How are service quotes structured?\", \"a\": \"Quotes are milestone-based with clear scope, materials, and timeline checkpoints before work begins.\", \"icon\": \"bi-calculator\"}, {\"q\": \"Do you work on renovations and upgrades?\", \"a\": \"Yes. We handle new builds, renovations, premium finishing, and phased upgrade projects.\", \"icon\": \"bi-tools\"}]',NULL,1,4,NULL),(34,'header','topbar','Top Info Bar','Show announcement bar','','{\"show_topbar\": 1, \"phone\": \"+252 907 734 667\", \"hours\": \"Sat?Thu 8:00 AM ? 8:00 PM\", \"location\": \"Garowe, Puntland, Somalia\", \"quote_cta\": \"WhatsApp Consultation\"}',NULL,1,1,NULL),(35,'footer','cta','Start Your Build','Ready to create something exceptional?','Get a Quote','{\"button_text\": \"Get a Quote\", \"button_link\": \"/contact\"}',NULL,1,1,NULL),(38,'home','about_highlights','Who We Are','Built on Integrity. Delivered with Precision.','HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces.','[{\"icon\": \"bi-award\", \"title\": \"Proven Track Record\", \"text\": \"Years of successful residential and commercial delivery across Somalia.\"}, {\"icon\": \"bi-people\", \"title\": \"One Accountable Team\", \"text\": \"Design, construction, and finishing coordinated under one roof.\"}, {\"icon\": \"bi-graph-up-arrow\", \"title\": \"Value-Driven Builds\", \"text\": \"Durable materials and smart planning that protect your investment.\"}, {\"icon\": \"bi-hand-thumbs-up\", \"title\": \"Client-First Approach\", \"text\": \"Clear communication from first meeting through final handover.\"}]',NULL,1,3,NULL),(39,'home','stats','Achievements','Proof of Excellence','Real outcomes from real projects.',NULL,NULL,1,8,NULL),(47,'projects','hero','Portfolio','Projects Delivered With Confidence','Explore residential, commercial, and community builds ? each delivered with structured planning, quality control, and premium finishes.',NULL,'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80',1,1,NULL),(48,'projects','intro','Our Work','Built to Last. Designed to Impress.','Every project reflects our commitment to transparent delivery, disciplined craftsmanship, and spaces people trust for generations.',NULL,NULL,1,2,NULL),(49,'projects','pillars','Our Standards','Engineered for Excellence','Principles that distinguish our construction projects from ground break to handover.','[{\"icon\": \"bi-clipboard-data\", \"title\": \"Structured delivery\", \"text\": \"Milestone planning, progress updates, and quality checks at every phase.\"}, {\"icon\": \"bi-gem\", \"title\": \"Premium finishes\", \"text\": \"Materials and craftsmanship held to standards you can see and measure.\"}, {\"icon\": \"bi-shield-check\", \"title\": \"Transparent scope\", \"text\": \"Clear pricing, documented scope, and accountable communication.\"}, {\"icon\": \"bi-geo-alt\", \"title\": \"Local expertise\", \"text\": \"Deep experience building across Puntland ? from Garowe to Bosaso.\"}]',NULL,1,3,NULL),(50,'projects','approach','Our Approach','How We Build Your Vision','From concept design through final key handover, our phased delivery ensures zero surprises.','[{\"icon\": \"bi-search\", \"title\": \"Discovery\", \"text\": \"Site review, goals, and feasibility before design begins.\"}, {\"icon\": \"bi-rulers\", \"title\": \"Design & planning\", \"text\": \"Drawings, approvals, and a milestone schedule you can track.\"}, {\"icon\": \"bi-hammer\", \"title\": \"Build execution\", \"text\": \"Disciplined site work with structured QA inspections.\"}, {\"icon\": \"bi-key\", \"title\": \"Handover\", \"text\": \"Final walkthrough, documentation, and after-care support.\"}]',NULL,1,4,NULL),(51,'projects','cta','Start Your Project','Ready to Build Your Next Space?','Share your drawings or ideas with our engineering team for an accurate estimate and consultation.','{\"button_text\": \"Get a Quote\", \"button_link\": \"/contact\"}',NULL,1,5,NULL),(52,'gallery','hero','Our Work','Project Gallery','Photos from residential, commercial, and community projects across Puntland ? structure, finishes, and handover.',NULL,'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80',1,1,NULL),(53,'gallery','intro','Visual Portfolio','Craftsmanship in Every Frame','Browse real project imagery ? from structural milestones to final finishes ? and see the quality HighQ Homes delivers.',NULL,NULL,1,2,NULL),(54,'gallery','highlights','Visual Standards','What You Will See','A comprehensive look at our craftsmanship across all construction stages.','[{\"icon\": \"bi-buildings\", \"title\": \"Residential & commercial\", \"text\": \"Exterior and interior shots from homes, offices, and mixed-use builds.\"}, {\"icon\": \"bi-brush\", \"title\": \"Finishing details\", \"text\": \"Kitchens, bathrooms, joinery, and premium fit-out craftsmanship.\"}, {\"icon\": \"bi-hammer\", \"title\": \"On-site progress\", \"text\": \"Structure, cladding, and quality checks throughout delivery.\"}, {\"icon\": \"bi-tree\", \"title\": \"Landscape & exterior\", \"text\": \"Facades, compounds, hardscape, and outdoor spaces.\"}]',NULL,1,3,NULL),(55,'gallery','cta','Experience the Quality','Have a Vision for Your Build?','Let us bring your architectural and construction plans to life with certified precision.','{\"button_text\": \"Discuss Your Project\", \"button_link\": \"/contact\"}',NULL,1,4,NULL),(56,'paints','hero','Products','Premium Paints & Coatings','Professional-grade finishes for exterior walls, interiors, and textured surfaces ? supplied with expert guidance for lasting results.',NULL,'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1920&q=80',1,1,NULL),(57,'paints','intro','Finishing Excellence','Colors & Coatings That Endure','High-performance paints and architectural coatings engineered to withstand weather, sun, and wear.',NULL,NULL,1,2,NULL),(58,'paints','benefits','Why Choose HighQ Paints','Durability & Aesthetics Combined','Our curated coating solutions guarantee lasting vibrancy and protection.','[{\"icon\": \"bi-shield-check\", \"title\": \"Quality assured\", \"text\": \"Trusted brands selected for durability in Puntland\'s climate.\"}, {\"icon\": \"bi-person-workspace\", \"title\": \"Expert guidance\", \"text\": \"Product advice for exterior, interior, and textured finishes.\"}, {\"icon\": \"bi-truck\", \"title\": \"Project supply\", \"text\": \"Coordinated delivery for residential and commercial builds.\"}, {\"icon\": \"bi-brush\", \"title\": \"Application support\", \"text\": \"Surface prep, coverage, and professional application tips.\"}]',NULL,1,3,NULL),(59,'paints','cta','Color Your Space','Need Paint Advice or Bulk Supply?','Speak with our paint specialists to find the perfect shades and protective coatings for your project.','{\"button_text\": \"Enquire Now\", \"button_link\": \"/contact\"}',NULL,1,4,NULL),(60,'contact','process','Inquiry Process','How We Handle Your Request','A streamlined onboarding experience from initial contact to proposal.','[{\"num\": \"01\", \"title\": \"Review\", \"text\": \"We assess project type, location, and scope.\"}, {\"num\": \"02\", \"title\": \"Clarify\", \"text\": \"We follow up for drawings or key site details.\"}, {\"num\": \"03\", \"title\": \"Plan\", \"text\": \"You receive the next step for design or estimate.\"}]',NULL,1,2,NULL),(62,'home','hero','Trusted Builder','Since 2016','Hero slides are edited in Homepage Hero. Use this section to show or hide the homepage banner and the live badge text.','[{\"num\":\"8\",\"suffix\":\"\",\"label\":\"Projects\",\"icon\":\"bi-buildings\"},{\"num\":\"3\",\"suffix\":\"\",\"label\":\"Completed\",\"icon\":\"bi-check2-circle\"},{\"num\":\"10\",\"suffix\":\"\",\"label\":\"Years\",\"icon\":\"bi-award\"}]',NULL,1,0,NULL),(63,'home','projects','Portfolio','Signature Work That Defines Our Standard','Explore featured builds and recent completions ? each project reflects our commitment to quality, clarity, and premium finish.','{\"cta_primary\":\"Full Portfolio\",\"cta_secondary\":\"View Gallery\",\"recent_kicker\":\"Recent Sites\",\"recent_title\":\"Fresh Completions & Active Builds\",\"band_title\":\"Ready to start your next build?\",\"band_text\":\"Share your vision and receive a structured consultation from our team.\"}',NULL,0,4,'2026-09-09 00:58:11'),(64,'home','testimonials','Testimonials','What Our Clients Say','Trusted by owners, developers, and community partners across every project type.','{\"chips\":[\"Client-rated excellence\",\"Verified project delivery\",\"Residential & commercial\"]}',NULL,1,10,'2026-09-09 00:59:41'),(65,'home','cta','Ready When You Are','Let\'s Build Something Exceptional','Share your vision with our team for confident planning from groundbreaking to handover.','{\"button_text\":\"WhatsApp Us\",\"button_text_2\":\"Contact Us\"}','https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80',1,12,'2026-09-09 00:57:17'),(66,'about','timeline','Timeline','Milestones that shaped HighQ Homes','Key moments from founding to the work we deliver today.','[{\"year\":\"2016\",\"text\":\"HighQ Homes was established in Garowe by Bashir Mohamed and Yahye Ismail.\"},{\"year\":\"Today\",\"text\":\"We continue to deliver residential and commercial projects with quality, transparency, and sustainable design principles.\"}]',NULL,1,2,NULL),(67,'about','stats','Highlights','Company snapshot','Proof points from our work across Puntland.','[{\"num\":\"10\",\"suffix\":\"\",\"label\":\"Years Operating\",\"icon\":\"bi-award\"},{\"num\":\"8\",\"suffix\":\"\",\"label\":\"Profiled Projects\",\"icon\":\"bi-buildings\"},{\"num\":\"3\",\"suffix\":\"\",\"label\":\"Completed Projects\",\"icon\":\"bi-check2-circle\"},{\"num\":\"5\",\"suffix\":\"\",\"label\":\"Current Projects\",\"icon\":\"bi-building-gear\"}]',NULL,1,3,'2026-09-09 01:03:02'),(68,'about','mission','Purpose & Direction','Mission & Vision','What we stand for and where we are going.','[{\"icon\":\"bi-bullseye\",\"eyebrow\":\"Purpose\",\"title\":\"Our mission\",\"text\":\"Deliver exceptional construction with premium materials, skilled craftsmanship, and unwavering integrity.\"},{\"icon\":\"bi-compass\",\"eyebrow\":\"Direction\",\"title\":\"Our vision\",\"text\":\"Lead East Africa in sustainable, innovative construction that transforms communities.\"}]',NULL,1,4,NULL),(69,'about','team','Leadership','The team behind every build','Experienced leaders guiding strategy, quality, and client care on every project.',NULL,NULL,1,7,NULL),(70,'about','testimonials','Client voices','Trusted by owners & developers','What clients say about working with HighQ Homes from first meeting to handover.',NULL,NULL,1,8,NULL),(71,'about','cta','Start your project','Ready to build with HighQ Homes?','Share your vision ? we\'ll guide you from planning to handover with clarity, quality, and care.','{\"button_text\":\"Get a quote\",\"button_text_2\":\"Contact us\"}',NULL,1,9,NULL),(72,'services','cta','Ready to start','Request a structured quote','Tell us about your site and we will respond with clear next steps.',NULL,NULL,1,4,NULL),(73,'contact','cta','Prefer WhatsApp','Talk to us now','',NULL,NULL,1,2,NULL),(74,'services','pillars','Delivery pillars','','','[{\"icon\":\"bi-diagram-3\",\"title\":\"Integrated delivery\",\"text\":\"Design, construction, and finishing coordinated under one accountable team.\"},{\"icon\":\"bi-clipboard-check\",\"title\":\"Clear milestones\",\"text\":\"Structured scope, progress updates, and quality inspections at every phase.\"},{\"icon\":\"bi-gem\",\"title\":\"Premium standards\",\"text\":\"Materials and workmanship selected for durability in local conditions.\"},{\"icon\":\"bi-headset\",\"title\":\"Responsive support\",\"text\":\"Direct communication from consultation through handover and after-care.\"}]',NULL,1,2,NULL),(75,'services','catalog','Service catalog','Everything your project needs','Architecture, design, planning, and finishing ? delivered with professional oversight from first sketch to final handover.',NULL,NULL,1,3,NULL),(76,'services','scope','Project scope','Built for residential & commercial clients','Whether you need a single design discipline or full design-build delivery, we scale our team and timeline to match your project.','{\"cta_label\":\"View our work\",\"checklist\":[\"New builds with integrated architecture and construction\",\"Renovations, extensions, and premium finishing upgrades\",\"Site planning and landscape design for optimal land use\",\"Bespoke interior and furniture solutions\"],\"cards\":[{\"icon\":\"bi-building\",\"title\":\"Residential builds\",\"text\":\"Homes, villas, and gated communities with full design-build support.\"},{\"icon\":\"bi-shop\",\"title\":\"Commercial projects\",\"text\":\"Offices, retail, and mixed-use spaces built to operational requirements.\"},{\"icon\":\"bi-brush\",\"title\":\"Finishing & upgrades\",\"text\":\"Interior fit-outs, exterior refreshes, and phased renovation work.\"}]}',NULL,1,4,NULL),(77,'projects','catalog','Project portfolio','Explore our builds','Filter by category or status to find projects similar to yours.',NULL,NULL,1,3,NULL),(78,'gallery','catalog','Photo collection','Explore by category','Filter images by project type ? click any photo to view full size.',NULL,NULL,1,3,NULL),(79,'paints','catalog','Product catalog','Browse our range','Filter by category or brand to find the right coating for your project.',NULL,NULL,1,3,NULL),(80,'paints','guide','Professional guidance','Choosing the right coating','The right product depends on surface type, exposure, and finish expectations. Our team helps you select coatings that perform in local conditions.','{\"checklist\":[\"Exterior walls ? weather-resistant, UV-stable, breathable coatings\",\"Textured finishes ? hide imperfections with durable cementitious systems\",\"Interior spaces ? washable, low-odour finishes for living and commercial areas\",\"Surface preparation ? priming and substrate guidance before application\"],\"cards\":[{\"icon\":\"bi-droplet-half\",\"title\":\"Coverage & yield\",\"text\":\"Calculate m? per unit with our team before ordering.\"},{\"icon\":\"bi-sun\",\"title\":\"Climate suitability\",\"text\":\"Products selected for heat, dust, and seasonal rain.\"},{\"icon\":\"bi-tools\",\"title\":\"Application method\",\"text\":\"Trowel, roller, or spray ? we advise the best approach.\"}]}',NULL,1,4,NULL),(81,'contact','form','Project inquiry','Send us a message','Tell us about your build, renovation, or design scope. Your message goes directly to our team inbox.','{\"submit_label\":\"Send message\"}',NULL,1,1,NULL),(82,'contact','map','Visit us','Our office','',NULL,NULL,1,3,NULL);
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` varchar(400) DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `featured_image` varchar(400) DEFAULT NULL,
  `template` varchar(80) NOT NULL DEFAULT 'default',
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(400) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Privacy Policy','privacy-policy','How HighQ Homes handles website inquiries and client information.','<p>HighQ Homes respects your privacy. Information submitted through our contact forms is used only to respond to your inquiry and manage client communication.</p>',NULL,'default',NULL,NULL,1,1,'2026-07-14 17:18:53','2026-07-14 17:18:53',NULL),(2,'Terms of Service','terms','Website terms for HighQ Homes visitors and clients.','<p>By using this website, you agree to use the information provided here responsibly. Project details, pricing and timelines are confirmed through direct consultation.</p>',NULL,'default',NULL,NULL,1,2,'2026-07-14 17:18:53','2026-07-14 17:18:53',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paints`
--

DROP TABLE IF EXISTS `paints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `brand` varchar(80) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `short_description` varchar(400) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `specifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
  `featured_image` varchar(400) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `price` varchar(60) DEFAULT NULL,
  `unit` varchar(60) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paints`
--

LOCK TABLES `paints` WRITE;
/*!40000 ALTER TABLE `paints` DISABLE KEYS */;
INSERT INTO `paints` VALUES (1,'Saveto Exterior Textured Paint','saveto-exterior-textured-paint','Saveto','Exterior Textured','Premium cementitious textured exterior coating for durable, weather-resistant finishes.','Saveto Exterior Textured Paint is a high-performance cementitious coating designed for exterior walls. It provides exceptional durability, weather resistance, and aesthetic appeal with a distinctive textured finish.','[\"Weather resistant\",\"UV stable\",\"Crack bridging\",\"Easy to apply\",\"Hides imperfections\",\"Long lasting 10+ years\",\"Anti-fungal & algae resistant\",\"Breathable coating\"]','{\"Pack Size\":\"50 kg sack\",\"Coverage\":\"18-20 m? per bag\",\"Thickness\":\"1.5-3mm\",\"Application\":\"Steel trowel\",\"Dry Time\":\"2-4 hours\",\"Full Cure\":\"7 days\",\"Colors\":\"White & custom\",\"Dilution\":\"Water only\"}','https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=800&q=80',NULL,NULL,NULL,1,1,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `paints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `category` enum('residential','commercial','industrial','mosque','infrastructure','other') NOT NULL DEFAULT 'residential',
  `status` enum('completed','in_progress','planned') NOT NULL DEFAULT 'completed',
  `location` varchar(150) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `project_area` varchar(80) DEFAULT NULL,
  `project_year` smallint(6) DEFAULT NULL,
  `short_description` varchar(400) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(400) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'Zone Grand','zone-grand','residential','completed','Garowe, Puntland',NULL,'169 m2 ? G+1',NULL,'A G+1 residential building with 169 square metres and a spacious 6 m high-ceiling living room.','<p>Zone Grand is a G+1 residential building of 169 square metres. Both floors were planned around a spacious living room with a 6 m high ceiling, with interior design support integrated into the home.</p>','https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80','[\"https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80\",\"https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80\",\"https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80\",\"https://images.unsplash.com/photo-1613977257363-707ba9348227?w=1200&q=80\",\"https://images.unsplash.com/photo-1600210492486-724fe41c17f7?w=1200&q=80\"]',1,1,1,'2026-07-14 17:19:36'),(2,'Twin Houses','twin-houses','residential','completed','Garowe, Puntland',NULL,'200 m2 each',NULL,'A newly finished residential complex of two 200 square metre homes.','<p>Twin Houses is a newly finished residential complex with two 200 square metre buildings. Each home was delivered to an agreed project budget with a focus on practical family living.</p>','https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',NULL,1,1,2,'2026-07-14 17:19:36'),(3,'NODO Building','nodo-building','commercial','completed','Near Kalis Cafeteria, Garowe, Puntland','NODO NGO','240 m2',NULL,'A 240 square metre building for housing and office accommodation for NODO NGO.','<p>The NODO Building is a 240 square metre completed building designed for both housing and office accommodation for NODO NGO, located near Kalis Cafeteria in Garowe.</p>','https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80',NULL,1,1,3,'2026-07-14 17:19:36'),(4,'Residential House','residential-house','residential','in_progress','Garowe, Puntland',NULL,'250 m2 ? Two storeys',2024,'A 250 square metre two-storey residential house, under construction since late June 2024.','<p>A 250 square metre, two-storey residential house currently under construction. Work began in late June 2024.</p>','https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80',NULL,1,1,4,'2026-07-14 17:19:36'),(5,'Arc Side','arc-side','commercial','in_progress','Near the Arc, opposite Grand Hotel, Garowe, Puntland',NULL,NULL,NULL,'A mixed-use building with ground-floor shops and upper residential apartments.','<p>Arc Side is a current commercial and residential apartment building with ground-floor shopping stores and upper residential apartments, located near the Arc opposite Grand Hotel.</p>','https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',NULL,1,1,5,'2026-07-14 17:19:36'),(6,'Residential Building','spegethi-residential-building','residential','in_progress','Spegethi Street, Garowe, Puntland',NULL,'180 m2',NULL,'A 180 square metre residential building with a large compound and integrated two-car parking garage.','<p>This current 180 square metre residential building near Spegethi Street includes a large compound and a built-in two-car parking garage.</p>','https://images.unsplash.com/photo-1505843513577-22bb7d21e455?w=800&q=80',NULL,1,1,6,'2026-07-14 17:19:36'),(7,'Bossaso Chicken Building','bossaso-chicken-building','commercial','in_progress','Garowe, Puntland','Bossaso Chicken','260 m2 ? Four storeys',NULL,'A 260 square metre four-storey mixed-use building with an elevator planned for the west wing.','<p>Bossaso Chicken is a current 260 square metre, four-storey building designed for commercial and residential use. The west wing includes an elevator.</p>','https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',NULL,1,1,7,'2026-07-14 17:19:36'),(8,'Grand Hotel Adjacent Building','grand-hotel-adjacent-building','commercial','in_progress','Adjacent to Grand Hotel, Garowe, Puntland',NULL,'420 m2',NULL,'A current 420 square metre commercial building adjacent to Grand Hotel.','<p>A current 420 square metre commercial building adjacent to Grand Hotel. The project is at ground-floor stage.</p>',NULL,NULL,1,1,8,'2026-07-17 13:23:41');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `label` varchar(120) NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (9,'super_admin','Super Administrator','[\"*\"]','2026-07-14 17:19:45'),(10,'admin','Administrator','[\"content.view\", \"content.manage\", \"media.manage\", \"messages.view\", \"messages.manage\", \"settings.manage\", \"users.manage\", \"seo.manage\", \"menus.manage\", \"sections.manage\",  \"logs.view\", \"security.manage\"]','2026-07-14 17:19:45'),(11,'editor','Content Editor','[\"content.view\",\"content.manage\",\"media.manage\",\"messages.view\",\"sections.manage\"]','2026-07-14 17:19:45'),(12,'viewer','Read Only','[\"content.view\",\"messages.view\"]','2026-07-14 17:19:45');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo`
--

DROP TABLE IF EXISTS `seo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_slug` varchar(120) NOT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(400) DEFAULT NULL,
  `meta_keywords` varchar(300) DEFAULT NULL,
  `og_title` varchar(200) DEFAULT NULL,
  `og_description` varchar(400) DEFAULT NULL,
  `og_image` varchar(400) DEFAULT NULL,
  `schema_markup` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_slug` (`page_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo`
--

LOCK TABLES `seo` WRITE;
/*!40000 ALTER TABLE `seo` DISABLE KEYS */;
INSERT INTO `seo` VALUES (1,'home','HighQ Homes | Construction Company in Garowe, Puntland','HighQ Homes is a registered construction company established in Garowe in 2016. We deliver quality residential and commercial construction, consulting, and renovation.','HighQ Homes, Garowe construction, Puntland construction, residential building, commercial building, renovation, construction consulting',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(2,'about','About HighQ Homes | Established in Garowe Since 2016','Learn about HighQ Homes, founded in Garowe by Bashir Mohamed and Yahye Ismail to deliver durable, modern, and sustainable properties.','HighQ Homes history, Bashir Mohamed, Yahye Ismail, Garowe builders, Puntland construction company',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(3,'services','Construction Services in Garowe | HighQ Homes','HighQ Homes offers project consulting, quality construction, and renovation services for residential and commercial properties in Garowe.','Garowe construction services, project consulting, renovation, HighQ Homes',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(4,'projects','HighQ Homes Projects | Garowe, Puntland','Explore HighQ Homes completed and current residential and commercial projects in Garowe, Puntland.','HighQ Homes projects, Garowe buildings, Puntland construction portfolio',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(5,'paints','Paints & Building Materials ? HighQ Homes','Quality paints and building materials including Saveto exterior textured paint for superior construction finishes.','building materials, paint products, Saveto paint',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(6,'gallery','Photo Gallery ? HighQ Homes','View photos of our completed construction projects and architectural work across Puntland.','construction gallery, building photos, project gallery',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54'),(7,'contact','Contact HighQ Homes | Garowe Construction Company','Contact HighQ Homes in Garowe, Puntland for project consulting, construction, and renovation.','contact HighQ Homes, Garowe construction company, Puntland builders',NULL,NULL,NULL,NULL,'2026-07-14 17:18:54');
/*!40000 ALTER TABLE `seo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `short_description` varchar(300) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(60) NOT NULL DEFAULT 'bi-building',
  `image` varchar(400) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Project Consulting','project-consulting','High-quality ideas for project planning and budgeting before work begins.','<p>We support clients with practical planning, clear budgeting, and considered project guidance from the first consultation.</p>','bi-chat-square-text',NULL,1,1,1,'2026-07-14 17:18:53'),(2,'Construction','construction','Residential and commercial construction delivered with honesty, transparency, and ethical conduct.','<p>HighQ Homes delivers carefully planned construction using quality materials, skilled craftsmanship, and clear communication throughout delivery.</p>','bi-buildings',NULL,1,1,2,'2026-07-14 17:18:53'),(3,'Renovation','renovation','Innovative solutions for improving and renewing existing spaces.','<p>We assess existing buildings and deliver practical renovation work that improves function, quality, and long-term value.</p>','bi-tools',NULL,1,1,3,'2026-07-14 17:18:53'),(4,'Site Planning','site-planning','Strategic site assessment and master planning for optimal land use.',NULL,'bi-geo-alt',NULL,0,0,4,'2026-07-14 17:18:53'),(5,'Interior Design','interior-design','Elegant and functional interior spaces tailored to your lifestyle.',NULL,'bi-lamp',NULL,0,0,5,'2026-07-14 17:18:53'),(6,'Furniture Design','furniture-design','Bespoke furniture pieces crafted to complement our architectural designs.',NULL,'bi-box-seam',NULL,0,0,6,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `key` varchar(80) NOT NULL,
  `value` text DEFAULT NULL,
  `type` enum('text','textarea','color','boolean','image','number') NOT NULL DEFAULT 'text',
  `label` varchar(120) NOT NULL,
  `group_name` varchar(60) NOT NULL DEFAULT 'general',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('about_text','HighQ Homes is a registered construction company based in Garowe City, Puntland, Somalia. Established in 2016 by Bashir Mohamed and Yahye Ismail, we create high-quality residential and commercial properties through meticulous planning, quality materials, skilled craftsmanship, sustainable practices, and modern design.','textarea','About Text','general',3),('accent_color','#E88B09','color','Accent Color','identity',8),('address','Garowe City, Puntland State of Somalia','text','Street Address','contact',4),('contact_cta_icon','bi-person-lines-fill','text','Header CTA Icon Class','contact',9),('contact_cta_label','Contact Us','text','Header CTA Label','contact',8),('contact_cta_message','Hello HighQ Homes, I would like to discuss a construction project.','textarea','WhatsApp CTA Message','contact',10),('email','HighQhomes@outlook.com','text','Email Address','contact',3),('facebook','https://facebook.com/highqhomes','text','Facebook URL','social',1),('favicon','','image','Favicon','identity',6),('footer_logo','','image','Footer Logo Mark','identity',5),('google_analytics','','text','Google Analytics ID','seo',3),('hero_video','','text','Hero Video URL','homepage',6),('home_about_enabled','1','boolean','Show About Section','homepage',7),('home_about_image','','image','Homepage About Image','homepage',12),('home_cta_enabled','1','boolean','Show CTA Section','homepage',11),('home_cta_image','','image','Homepage CTA Image','homepage',13),('home_cta_text','Discuss your Garowe project with HighQ Homes for clear planning, practical budgeting, and quality delivery.','textarea','Homepage CTA Text','homepage',15),('home_cta_title','Plan Your Project With HighQ Homes','text','Homepage CTA Title','homepage',14),('home_projects_enabled','1','boolean','Show Projects Section','homepage',9),('home_services_enabled','1','boolean','Show Services Section','homepage',8),('home_testimonials_enabled','1','boolean','Show Testimonials Section','homepage',10),('hours','Sat?Fri 8AM?9PM','text','Working Hours','contact',5),('instagram','','text','Instagram URL','social',2),('legal_name','','text','Legal / Copyright Name','identity',3),('linkedin','','text','LinkedIn URL','social',4),('logo','','image','Header Logo Mark','identity',4),('maintenance_mode','0','boolean','Maintenance mode','security',5),('map_embed','','textarea','Google Maps Embed URL','contact',7),('meta_description','HighQ Homes delivers world-class residential and commercial construction projects in Puntland, Somalia.','textarea','Default Meta Description','seo',2),('meta_title','HighQ Homes ? Premium Construction in Puntland','text','Default Meta Title','seo',1),('mission','To be the premier provider of innovative and sustainable housing solutions in Puntland State, contributing to the development and modernization of Garowe City through homes that serve residents and enhance the community.','textarea','Mission Statement','general',4),('phone','+252 907 734 667','text','Primary Phone','contact',1),('phone_2','+252 907 900 101','text','Secondary Phone','contact',2),('primary_color','#021D45','color','Primary Color','identity',7),('security_audit_retention_days','90','number','Keep audit logs (days)','security',4),('security_lockout_minutes','15','number','Lockout duration (minutes)','security',2),('security_max_attempts','5','number','Max login attempts','security',1),('security_session_hours','2','number','Session idle timeout (hours)','security',3),('site_name','HighQ Homes','text','Site Name','identity',1),('stat_awards','2016','text','Awards Count','homepage',4),('stat_clients','3','text','Clients Count','homepage',2),('stat_projects','8','text','Projects Count','homepage',1),('stat_satisfaction','5','text','Client Satisfaction %','homepage',5),('stat_years','10','text','Years Experience','homepage',3),('tagline','Innovative and sustainable housing solutions for Puntland.','text','Tagline','identity',2),('twitter','','text','Twitter/X URL','social',3),('vision','Deliver exceptional quality through durable, safe, and aesthetically considered homes; sustainable materials and energy-efficient design; transparent communication; and lasting relationships built on trust, respect, and accountability.','textarea','Vision Statement','general',5),('whatsapp','+252907734667','text','WhatsApp Number','contact',6),('youtube','','text','YouTube URL','social',5);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `button_text` varchar(80) DEFAULT NULL,
  `button_link` varchar(200) DEFAULT NULL,
  `button_text_2` varchar(80) DEFAULT NULL,
  `button_link_2` varchar(200) DEFAULT NULL,
  `image` varchar(400) DEFAULT NULL,
  `overlay_opacity` decimal(3,2) NOT NULL DEFAULT 0.60,
  `text_align` enum('left','center','right') NOT NULL DEFAULT 'center',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (1,'Innovative Homes Built for Garowe','Residential and Commercial Construction','HighQ Homes combines meticulous planning, quality materials, skilled craftsmanship, and transparent delivery for durable spaces that serve people and communities.','Contact Us','/contact','Explore Projects','/projects','https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80',0.60,'center',1,1,'2026-07-14 17:18:53'),(2,'Quality That Lasts','Established in Garowe Since 2016','We build residential and commercial properties with an accountable approach to quality, sustainability, customer experience, and delivery.','Our Services','/services','Our Story','/about','https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&q=80',0.60,'center',1,2,'2026-07-14 17:18:53'),(3,'Built With Integrity','Consulting, Construction and Renovation','From project planning and budgeting to construction and renovation, HighQ Homes delivers thoughtful work with honesty, transparency, and care.','View Projects','/projects','Contact Us','/contact','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&q=80',0.60,'center',1,3,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `position` varchar(120) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(400) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `linkedin_url` varchar(300) DEFAULT NULL,
  `twitter_url` varchar(300) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (1,'Bashir Mohamed','Co-Founder','Co-founder of HighQ Homes, established in Garowe in 2016 to deliver high-quality residential properties with integrity, skilled craftsmanship, and modern design.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-07-14 17:18:53'),(2,'Yahye Ismail','Co-Founder','Co-founder of HighQ Homes, helping guide the company toward sustainable housing solutions, trusted client relationships, and quality project delivery.',NULL,NULL,NULL,NULL,NULL,1,2,'2026-07-14 17:18:53'),(3,'Guled Muscid','Creative Director','Award-winning architect with a passion for blending modern design with cultural heritage.',NULL,NULL,NULL,NULL,NULL,0,3,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(120) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `company` varchar(120) DEFAULT NULL,
  `image` varchar(400) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Fitah Gutalee','Business Owner',NULL,NULL,'HighQ Homes delivered our commercial building beyond expectations. Professional team, on-time delivery, and exceptional quality.',5,1,1,'2026-07-14 17:18:53'),(2,'Abdisamad Islan','Homeowner',NULL,NULL,'Building our family home with HighQ Homes was a wonderful experience. They listened to our needs and delivered a beautiful, functional home.',5,1,1,'2026-07-14 17:18:53'),(3,'Mohamed Nuur','Project Director',NULL,NULL,'We hired HighQ Homes for our community mosque project. Their attention to Islamic architectural principles combined with modern construction techniques was outstanding.',5,0,1,'2026-07-14 17:18:53');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(80) NOT NULL DEFAULT 'editor',
  `avatar` varchar(400) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ICT Admin','info@highqhomes.site','$2y$12$oyVBzp5rcoD4VGxkoqvxJuwFNL9FBsQxLFPic/1exxP6xDgqlsKkS','super_admin',NULL,1,'2026-09-08 23:26:15','2026-07-14 17:18:53'),(3,'ICT Admin','info@highqhomes.net','$2y$12$Kky.BLZEDlTzj4umSRnzE.R1eEeH.q/AeA1.dwf.IyAEEgJ3Ek9FS','super_admin',NULL,1,'2026-07-17 09:54:00','2026-07-14 17:19:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'highqdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-09  8:49:48
