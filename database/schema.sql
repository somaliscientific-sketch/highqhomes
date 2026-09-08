-- HighQ Homes Database Schema — Pure PHP MVC
-- Import into phpMyAdmin or run with a MySQL client.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS highqhomes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE highqhomes;

-- ─── Users ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(120)    NOT NULL,
  `email`      VARCHAR(180)    NOT NULL UNIQUE,
  `password`   VARCHAR(255)    NOT NULL,
  `role`       VARCHAR(80)      NOT NULL DEFAULT 'editor',
  `avatar`     VARCHAR(400)    DEFAULT NULL,
  `is_active`  TINYINT(1)      NOT NULL DEFAULT 1,
  `last_login` DATETIME        DEFAULT NULL,
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Roles / Permissions ──────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(80)  NOT NULL UNIQUE,
  `label`       VARCHAR(120) NOT NULL,
  `permissions` JSON         DEFAULT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `roles` (`name`,`label`,`permissions`) VALUES
('super_admin','Super Administrator','["*"]'),
('admin','Administrator','["content.view","content.manage","media.manage","messages.view","messages.manage","settings.manage","menus.manage","seo.manage","users.manage","logs.view","security.manage","sections.manage"]'),
('editor','Content Editor','["content.view","content.manage","media.manage","messages.view","sections.manage"]'),
('viewer','Read Only','["content.view","messages.view"]');

-- Change this bootstrap password immediately after first login.
INSERT IGNORE INTO `users` (`name`,`email`,`password`,`role`,`is_active`) VALUES
('Super Admin','admin@highqhomes.net',
 '$2y$12$slF/wcCgxiiqLFSh6DMAMuaDVPjpRddmPhmeNVD3Cm9vEz3kEN55S',
 'super_admin',1);

-- ─── Settings ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `key`        VARCHAR(80)  NOT NULL PRIMARY KEY,
  `value`      TEXT         DEFAULT NULL,
  `type`       ENUM('text','textarea','color','boolean','image','number') NOT NULL DEFAULT 'text',
  `label`      VARCHAR(120) NOT NULL,
  `group_name` VARCHAR(60)  NOT NULL DEFAULT 'general',
  `sort_order` INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `settings` VALUES
('site_name','HighQ Homes','text','Site Name','identity',1),
('tagline','Broad Vision · Honest Service · Great Value','text','Tagline','identity',2),
('legal_name','','text','Legal / Copyright Name','identity',3),
('about_text','HighQ Homes is a premier construction company based in Puntland, Somalia, delivering world-class residential and commercial projects with broad vision, honest service, and great value since 2020.','textarea','About Text','general',3),
('mission','To deliver exceptional construction and architectural services that exceed client expectations, using premium materials and innovative techniques while upholding the highest standards of integrity.','textarea','Mission Statement','general',4),
('vision','To be the leading construction company in East Africa, recognized for transforming communities through sustainable, innovative, and world-class solutions.','textarea','Vision Statement','general',5),
('logo','','image','Header Logo Mark','identity',4),
('footer_logo','','image','Footer Logo Mark','identity',5),
('favicon','','image','Favicon','identity',6),
('primary_color','#021D45','color','Primary Color','identity',7),
('accent_color','#E88B09','color','Accent/Gold Color','identity',8),
('phone','+252 907 734 667','text','Primary Phone','contact',1),
('phone_2','+252 907 952 679','text','Secondary Phone','contact',2),
('email','info@highqhomes.net','text','Email Address','contact',3),
('address','3CCC, Garowe, Puntland, Somalia','text','Street Address','contact',4),
('hours','Sat–Fri 8AM–9PM','text','Working Hours','contact',5),
('whatsapp','+252907734667','text','WhatsApp Number','contact',6),
('map_embed','','textarea','Google Maps Embed URL','contact',7),
('contact_cta_label','Contact Us','text','Header CTA Label','contact',8),
('contact_cta_icon','bi-person-lines-fill','text','Header CTA Icon Class','contact',9),
('contact_cta_message','Hello HighQ Homes, I would like to discuss a construction project.','textarea','WhatsApp CTA Message','contact',10),
('facebook','https://facebook.com/highqhomes','text','Facebook URL','social',1),
('instagram','','text','Instagram URL','social',2),
('twitter','','text','Twitter/X URL','social',3),
('linkedin','','text','LinkedIn URL','social',4),
('youtube','','text','YouTube URL','social',5),
('stat_projects','150','text','Projects Count','homepage',1),
('stat_clients','200','text','Clients Count','homepage',2),
('stat_years','4','text','Years Experience','homepage',3),
('stat_awards','12','text','Awards Count','homepage',4),
('stat_satisfaction','98','text','Client Satisfaction %','homepage',5),
('hero_video','','text','Hero Video URL','homepage',6),
('home_about_enabled','1','boolean','Show About Section','homepage',7),
('home_services_enabled','1','boolean','Show Services Section','homepage',8),
('home_projects_enabled','1','boolean','Show Projects Section','homepage',9),
('home_testimonials_enabled','1','boolean','Show Testimonials Section','homepage',10),
('home_cta_enabled','1','boolean','Show CTA Section','homepage',11),
('home_about_image','','image','Homepage About Image','homepage',12),
('home_cta_image','','image','Homepage CTA Image','homepage',13),
('home_cta_title','Ready to Build Your Dream Project?','text','Homepage CTA Title','homepage',14),
('home_cta_text','Contact us today for a free consultation and let our expert team bring your vision to life.','textarea','Homepage CTA Text','homepage',15),
('meta_title','HighQ Homes — Premium Construction in Puntland','text','Default Meta Title','seo',1),
('meta_description','HighQ Homes delivers world-class residential and commercial construction projects in Puntland, Somalia.','textarea','Default Meta Description','seo',2),
('google_analytics','','text','Google Analytics ID','seo',3),
('security_max_attempts','5','number','Max login attempts','security',1),
('security_lockout_minutes','15','number','Lockout duration (minutes)','security',2),
('security_session_hours','2','number','Session idle timeout (hours)','security',3),
('security_audit_retention_days','90','number','Keep audit logs (days)','security',4),
('maintenance_mode','0','boolean','Maintenance mode','security',5);

-- ─── Admin activity logs ───────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT UNSIGNED DEFAULT NULL,
  `user_name`   VARCHAR(120) DEFAULT NULL,
  `user_email`  VARCHAR(190) DEFAULT NULL,
  `action`      VARCHAR(40)  NOT NULL,
  `module`      VARCHAR(60)  NOT NULL DEFAULT 'system',
  `entity_type` VARCHAR(60)  DEFAULT NULL,
  `entity_id`   INT UNSIGNED DEFAULT NULL,
  `description` VARCHAR(500) DEFAULT NULL,
  `ip_address`  VARCHAR(45)  DEFAULT NULL,
  `user_agent`  VARCHAR(300) DEFAULT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_created` (`created_at`),
  INDEX `idx_user` (`user_id`),
  INDEX `idx_module` (`module`),
  INDEX `idx_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Menus ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `menus` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `label`        VARCHAR(120) NOT NULL,
  `url`          VARCHAR(300) NOT NULL,
  `location`     ENUM('primary','footer') NOT NULL DEFAULT 'primary',
  `parent_id`    INT UNSIGNED DEFAULT NULL,
  `target`       ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`   INT          NOT NULL DEFAULT 0,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (`location`, `is_published`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `menus` (`id`,`label`,`url`,`location`,`sort_order`,`is_published`) VALUES
(1,'Home','/','primary',1,1),
(2,'About','/about','primary',2,1),
(3,'Services','/services','primary',3,1),
(4,'Projects','/projects','primary',4,1),
(5,'Paints','/paints','primary',5,1),
(6,'Gallery','/gallery','primary',6,1),
(7,'Contact','/contact','primary',7,1);

-- ─── Pages ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `pages` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`            VARCHAR(180) NOT NULL,
  `slug`             VARCHAR(200) NOT NULL UNIQUE,
  `excerpt`          VARCHAR(400) DEFAULT NULL,
  `content`          MEDIUMTEXT   DEFAULT NULL,
  `featured_image`   VARCHAR(400) DEFAULT NULL,
  `template`         VARCHAR(80)  NOT NULL DEFAULT 'default',
  `meta_title`       VARCHAR(200) DEFAULT NULL,
  `meta_description` VARCHAR(400) DEFAULT NULL,
  `is_published`     TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`       INT          NOT NULL DEFAULT 0,
  `published_at`     DATETIME     DEFAULT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `pages` (`id`,`title`,`slug`,`excerpt`,`content`,`template`,`is_published`,`sort_order`,`published_at`) VALUES
(1,'Privacy Policy','privacy-policy','How HighQ Homes handles website inquiries and client information.','<p>HighQ Homes respects your privacy. Information submitted through our contact forms is used only to respond to your inquiry and manage client communication.</p>','default',1,1,NOW()),
(2,'Terms of Service','terms','Website terms for HighQ Homes visitors and clients.','<p>By using this website, you agree to use the information provided here responsibly. Project details, pricing and timelines are confirmed through direct consultation.</p>','default',1,2,NOW());

-- ─── Media Library ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `media` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(180) DEFAULT NULL,
  `file_path`   VARCHAR(400) NOT NULL,
  `file_type`   VARCHAR(80)  NOT NULL,
  `file_size`   INT UNSIGNED DEFAULT NULL,
  `alt_text`    VARCHAR(220) DEFAULT NULL,
  `folder`      VARCHAR(80)  DEFAULT 'media',
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Sliders ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `sliders` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`           VARCHAR(200)  NOT NULL,
  `subtitle`        VARCHAR(200)  DEFAULT NULL,
  `description`     TEXT          DEFAULT NULL,
  `button_text`     VARCHAR(80)   DEFAULT NULL,
  `button_link`     VARCHAR(200)  DEFAULT NULL,
  `button_text_2`   VARCHAR(80)   DEFAULT NULL,
  `button_link_2`   VARCHAR(200)  DEFAULT NULL,
  `image`           VARCHAR(400)  DEFAULT NULL,
  `overlay_opacity` DECIMAL(3,2)  NOT NULL DEFAULT 0.60,
  `text_align`      ENUM('left','center','right') NOT NULL DEFAULT 'center',
  `is_published`    TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`      INT           NOT NULL DEFAULT 0,
  `created_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `sliders` (`id`,`title`,`subtitle`,`description`,`button_text`,`button_link`,`button_text_2`,`button_link_2`,`image`,`overlay_opacity`,`is_published`,`sort_order`) VALUES
(1,'Broad Vision Honest Service Great Value','Premium Construction & Architecture','Our goal then and now is to provide quality, on-time projects with reliable construction, thoughtful design, and premium finishes.','Get Free Quote','https://wa.me/252907734667?text=Hello%20HighQ%20Homes,%20I%20would%20like%20a%20construction%20quote','View Projects','/projects','https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80',0.60,1,1),
(2,'Turning Visions Into Structures','Architecture · Design · Construction','From concept to completion, HighQ Homes brings your architectural vision to life with innovation and excellence.','Our Services','/services','View Projects','/projects','https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&q=80',0.60,1,2),
(3,'Excellence in Every Detail','Luxury Homes & Commercial Spaces','Broad Vision. Honest Service. Great Value. Building Puntlands finest developments since 2020.','View Projects','/projects','About Us','/about','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&q=80',0.60,1,3);

-- ─── Services ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `services` (
  `id`                INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`             VARCHAR(150)  NOT NULL,
  `slug`              VARCHAR(160)  NOT NULL UNIQUE,
  `short_description` VARCHAR(300)  DEFAULT NULL,
  `description`       TEXT          DEFAULT NULL,
  `icon`              VARCHAR(60)   NOT NULL DEFAULT 'bi-building',
  `image`             VARCHAR(400)  DEFAULT NULL,
  `is_featured`       TINYINT(1)    NOT NULL DEFAULT 0,
  `is_published`      TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`        INT           NOT NULL DEFAULT 0,
  `created_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `services` VALUES
(1,'Architecture','architecture','Innovative architectural designs that blend form, function and local context.',NULL,'bi-buildings',NULL,1,1,1,NOW()),
(2,'Exterior Design','exterior-design','Stunning facade and exterior solutions that make buildings stand out.',NULL,'bi-house-door',NULL,1,1,2,NOW()),
(3,'Landscape Design','landscape-design','Beautiful outdoor spaces designed to complement every structure.',NULL,'bi-tree',NULL,1,1,3,NOW()),
(4,'Site Planning','site-planning','Strategic site assessment and master planning for optimal land use.',NULL,'bi-geo-alt',NULL,0,1,4,NOW()),
(5,'Interior Design','interior-design','Elegant and functional interior spaces tailored to your lifestyle.',NULL,'bi-lamp',NULL,1,1,5,NOW()),
(6,'Furniture Design','furniture-design','Bespoke furniture pieces crafted to complement our architectural designs.',NULL,'bi-box-seam',NULL,0,1,6,NOW());

-- ─── Projects ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `projects` (
  `id`                INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`             VARCHAR(200)  NOT NULL,
  `slug`              VARCHAR(220)  NOT NULL UNIQUE,
  `category`          ENUM('residential','commercial','industrial','mosque','infrastructure','other') NOT NULL DEFAULT 'residential',
  `status`            ENUM('completed','in_progress','planned') NOT NULL DEFAULT 'completed',
  `location`          VARCHAR(150)  DEFAULT NULL,
  `client_name`       VARCHAR(150)  DEFAULT NULL,
  `project_area`      VARCHAR(80)   DEFAULT NULL,
  `project_year`      SMALLINT      DEFAULT NULL,
  `short_description` VARCHAR(400)  DEFAULT NULL,
  `description`       TEXT          DEFAULT NULL,
  `featured_image`    VARCHAR(400)  DEFAULT NULL,
  `gallery_images`    JSON          DEFAULT NULL,
  `is_featured`       TINYINT(1)    NOT NULL DEFAULT 0,
  `is_published`      TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`        INT           NOT NULL DEFAULT 0,
  `created_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `projects` (`id`,`title`,`slug`,`category`,`status`,`location`,`client_name`,`project_year`,`project_area`,`short_description`,`description`,`featured_image`,`gallery_images`,`is_featured`,`is_published`,`sort_order`) VALUES
(1,'Luxury Residential Building','luxury-residential-building','residential','completed','Garowe, Puntland','Private Client',2023,'4,800 m² · 5 Floors','A premium multi-story residential building with modern architecture and luxury finishes.','<p>HighQ Homes delivered a premium multi-story residential building in Garowe, combining contemporary architecture with luxury finishes from structure to handover.</p><p>The scope included reinforced concrete works, curated façade treatments, premium interior fit-out, and coordinated MEP installation — all managed under a transparent milestone schedule with structured quality inspections.</p><p>Every level was finished to a high standard: durable materials, refined detailing, and spaces designed for long-term comfort and value.</p>','https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80','["https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80","https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80","https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80","https://images.unsplash.com/photo-1613977257363-707ba9348227?w=1200&q=80","https://images.unsplash.com/photo-1600210492486-724fe41c17f7?w=1200&q=80"]',1,1,1),
(2,'Community Mosque','community-mosque','mosque','completed','Garowe, Puntland','Community Trust',2022,NULL,'A beautifully designed community mosque accommodating 500+ worshippers.',NULL,'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80',NULL,1,1,2),
(3,'Urban Apartments','urban-apartments','residential','completed','Bosaso, Puntland','Real Estate Developer',2023,NULL,'Modern urban apartment complex with 24 units and premium amenities.',NULL,'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80',NULL,1,1,3),
(4,'Corporate Offices','corporate-offices','commercial','completed','Garowe, Puntland','Corporate Client',2022,NULL,'Contemporary corporate office building with open-plan layouts.',NULL,'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80',NULL,1,1,4),
(5,'Commercial Complex','commercial-complex','commercial','completed','Garowe, Puntland','Investment Group',2021,NULL,'Mixed-use commercial complex housing retail and office spaces.',NULL,'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',NULL,0,1,5),
(6,'2-Floor Family Residence','2-floor-family-residence','residential','completed','Garowe, Puntland','Family Client',2023,NULL,'Elegant two-floor family residence with traditional Somali design elements.',NULL,'https://images.unsplash.com/photo-1505843513577-22bb7d21e455?w=800&q=80',NULL,0,1,6),
(7,'Family Housing Estate','family-housing-estate','residential','in_progress','Garowe, Puntland','Housing Authority',2024,NULL,'An affordable family housing estate development currently under construction.',NULL,'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',NULL,1,1,7);

-- ─── Gallery ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `gallery` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`        VARCHAR(150) DEFAULT NULL,
  `description`  TEXT         DEFAULT NULL,
  `image`        VARCHAR(400) NOT NULL,
  `category`     VARCHAR(80)  DEFAULT NULL,
  `alt_text`     VARCHAR(200) DEFAULT NULL,
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`   INT          NOT NULL DEFAULT 0,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `gallery` (`title`, `description`, `image`, `category`, `alt_text`, `is_published`, `sort_order`, `created_at`) VALUES
('Luxury Residential Facade', 'Exterior view of a premium multi-story residential build in Garowe.', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80', 'residential', 'Luxury residential building exterior in Garowe', 1, 1, NOW()),
('Residential Living Space', 'Finished living area with premium interior detailing.', 'https://images.unsplash.com/photo-1600210492486-724fe41c17f7?w=1200&q=80', 'interior', 'Modern residential living room interior', 1, 2, NOW()),
('Urban Apartment Complex', 'Contemporary apartment development with clean architectural lines.', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80', 'residential', 'Urban apartment building exterior', 1, 3, NOW()),
('Apartment Interior Finish', 'Open-plan apartment interior with quality fittings.', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80', 'interior', 'Apartment interior with premium finishes', 1, 4, NOW()),
('Corporate Office Building', 'Commercial office structure with modern glass and cladding.', 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', 'commercial', 'Corporate office building exterior', 1, 5, NOW()),
('Office Workspace', 'Commercial interior workspace with professional fit-out.', 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=1200&q=80', 'commercial', 'Corporate office interior workspace', 1, 6, NOW()),
('Community Mosque Exterior', 'Completed community mosque with refined architectural form.', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80', 'mosque', 'Community mosque exterior in Puntland', 1, 7, NOW()),
('Mosque Prayer Hall', 'Interior view showing acoustic finishes and lighting.', 'https://images.unsplash.com/photo-1564760055775-d63ef17a55c4?w=1200&q=80', 'interior', 'Mosque prayer hall interior', 1, 8, NOW()),
('Structural Build Phase', 'Reinforced concrete structure during active construction.', 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80', 'construction', 'Building under construction with concrete structure', 1, 9, NOW()),
('Site Planning Overview', 'Aerial perspective of a planned development site.', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80', 'construction', 'Construction site aerial planning view', 1, 10, NOW()),
('Exterior Textured Finish', 'Textured exterior wall coating applied on site.', 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1200&q=80', 'exterior', 'Textured exterior wall finish on building', 1, 11, NOW()),
('Modern Villa Exterior', 'Standalone villa with landscaped approach and premium facade.', 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80', 'exterior', 'Modern villa exterior with landscaping', 1, 12, NOW()),
('Kitchen Fit-Out', 'Custom kitchen installation with stone and cabinetry.', 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1200&q=80', 'interior', 'Premium kitchen interior fit-out', 1, 13, NOW()),
('Master Bedroom Finish', 'Bedroom suite with coordinated finishes and lighting.', 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200&q=80', 'interior', 'Master bedroom with premium interior design', 1, 14, NOW()),
('Landscape & Hardscape', 'Outdoor hardscape and planting around a residential compound.', 'https://images.unsplash.com/photo-1558904541-efa843a96f01?w=1200&q=80', 'landscape', 'Residential landscape and hardscape design', 1, 15, NOW()),
('Commercial Entrance', 'Grand entrance detailing for a commercial development.', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80', 'commercial', 'Commercial building entrance and lobby area', 1, 16, NOW()),
('Premium Bathroom Finish', 'Bathroom fit-out with tile, fixtures, and ventilation.', 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=1200&q=80', 'finishing', 'Premium bathroom finishing details', 1, 17, NOW()),
('Staircase & Joinery', 'Custom staircase and joinery craftsmanship on site.', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&q=80', 'finishing', 'Custom staircase and wood joinery', 1, 18, NOW()),
('High-Rise Progress', 'Multi-floor residential tower during finishing phase.', 'https://images.unsplash.com/photo-1613977257363-707ba9348227?w=1200&q=80', 'residential', 'High-rise residential tower construction progress', 1, 19, NOW()),
('Team on Site', 'HighQ Homes site team during quality inspection.', 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1200&q=80', 'construction', 'Construction team reviewing work on site', 1, 20, NOW()),
('Handover Walkthrough', 'Final client walkthrough before project handover.', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80', 'finishing', 'Project handover walkthrough with client', 1, 21, NOW()),
('Retail Frontage', 'Completed retail frontage with signage-ready facade.', 'https://images.unsplash.com/photo-1449844908441-8829872d2607?w=1200&q=80', 'commercial', 'Retail commercial building frontage', 1, 22, NOW()),
('Compound Gate & Wall', 'Security wall and entrance gate for a residential compound.', 'https://images.unsplash.com/photo-1605276374102-dee2a0ed2cd6?w=1200&q=80', 'exterior', 'Residential compound gate and boundary wall', 1, 23, NOW()),
('Ceiling & Lighting Detail', 'Recessed lighting and ceiling finish in a commercial space.', 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&q=80', 'finishing', 'Ceiling and lighting finishing detail', 1, 24, NOW());

-- ─── Team ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `team` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`         VARCHAR(120) NOT NULL,
  `position`     VARCHAR(120) NOT NULL,
  `bio`          TEXT         DEFAULT NULL,
  `image`        VARCHAR(400) DEFAULT NULL,
  `email`        VARCHAR(180) DEFAULT NULL,
  `phone`        VARCHAR(30)  DEFAULT NULL,
  `linkedin_url` VARCHAR(300) DEFAULT NULL,
  `twitter_url`  VARCHAR(300) DEFAULT NULL,
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`   INT          NOT NULL DEFAULT 0,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `team` (`id`,`name`,`position`,`bio`,`is_published`,`sort_order`) VALUES
(1,'Bashiir Mohamed','VP / CEO','Visionary leader with 10+ years in construction and real estate development across East Africa.',1,1),
(2,'Asad Mohamud','Strategy Manager / Marketing','Expert in business strategy and marketing, driving HighQ Homes growth and brand presence.',1,2),
(3,'Guled Muscid','Creative Director','Award-winning architect with a passion for blending modern design with cultural heritage.',1,3);

-- ─── Testimonials ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `client_name`  VARCHAR(120) NOT NULL,
  `position`     VARCHAR(100) DEFAULT NULL,
  `company`      VARCHAR(120) DEFAULT NULL,
  `image`        VARCHAR(400) DEFAULT NULL,
  `content`      TEXT         NOT NULL,
  `rating`       TINYINT      NOT NULL DEFAULT 5,
  `is_featured`  TINYINT(1)   NOT NULL DEFAULT 0,
  `is_published` TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `testimonials` (`id`,`client_name`,`position`,`content`,`rating`,`is_featured`,`is_published`) VALUES
(1,'Fitah Gutalee','Business Owner','HighQ Homes delivered our commercial building beyond expectations. Professional team, on-time delivery, and exceptional quality.',5,1,1),
(2,'Abdisamad Islan','Homeowner','Building our family home with HighQ Homes was a wonderful experience. They listened to our needs and delivered a beautiful, functional home.',5,1,1),
(3,'Mohamed Nuur','Project Director','We hired HighQ Homes for our community mosque project. Their attention to Islamic architectural principles combined with modern construction techniques was outstanding.',5,0,1);

-- ─── Paints ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `paints` (
  `id`                INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`              VARCHAR(150)  NOT NULL,
  `slug`              VARCHAR(170)  NOT NULL UNIQUE,
  `brand`             VARCHAR(80)   DEFAULT NULL,
  `category`          VARCHAR(80)   DEFAULT NULL,
  `short_description` VARCHAR(400)  DEFAULT NULL,
  `description`       TEXT          DEFAULT NULL,
  `features`          JSON          DEFAULT NULL,
  `specifications`    JSON          DEFAULT NULL,
  `featured_image`    VARCHAR(400)  DEFAULT NULL,
  `gallery_images`    JSON          DEFAULT NULL,
  `price`             VARCHAR(60)   DEFAULT NULL,
  `unit`              VARCHAR(60)   DEFAULT NULL,
  `is_published`      TINYINT(1)    NOT NULL DEFAULT 1,
  `sort_order`        INT           NOT NULL DEFAULT 0,
  `created_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `paints` (`id`,`name`,`slug`,`brand`,`category`,`short_description`,`description`,`features`,`specifications`,`featured_image`,`is_published`,`sort_order`) VALUES
(1,'Saveto Exterior Textured Paint','saveto-exterior-textured-paint','Saveto','Exterior Textured',
'Premium cementitious textured exterior coating for durable, weather-resistant finishes.',
'Saveto Exterior Textured Paint is a high-performance cementitious coating designed for exterior walls. It provides exceptional durability, weather resistance, and aesthetic appeal with a distinctive textured finish.',
'["Weather resistant","UV stable","Crack bridging","Easy to apply","Hides imperfections","Long lasting 10+ years","Anti-fungal & algae resistant","Breathable coating"]',
'{"Pack Size":"50 kg sack","Coverage":"18-20 m² per bag","Thickness":"1.5-3mm","Application":"Steel trowel","Dry Time":"2-4 hours","Full Cure":"7 days","Colors":"White & custom","Dilution":"Water only"}',
'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=800&q=80',1,1);

-- ─── Messages ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `messages` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`         VARCHAR(120) NOT NULL,
  `email`        VARCHAR(180) NOT NULL,
  `phone`        VARCHAR(30)  DEFAULT NULL,
  `subject`      VARCHAR(200) DEFAULT NULL,
  `message`      TEXT         NOT NULL,
  `is_read`      TINYINT(1)   NOT NULL DEFAULT 0,
  `is_starred`   TINYINT(1)   NOT NULL DEFAULT 0,
  `reply`        TEXT         DEFAULT NULL,
  `ip_address`   VARCHAR(45)  DEFAULT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── SEO ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `seo` (
  `id`               INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_slug`        VARCHAR(120)  NOT NULL UNIQUE,
  `meta_title`       VARCHAR(200)  DEFAULT NULL,
  `meta_description` VARCHAR(400)  DEFAULT NULL,
  `meta_keywords`    VARCHAR(300)  DEFAULT NULL,
  `og_title`         VARCHAR(200)  DEFAULT NULL,
  `og_description`   VARCHAR(400)  DEFAULT NULL,
  `og_image`         VARCHAR(400)  DEFAULT NULL,
  `schema_markup`    TEXT          DEFAULT NULL,
  `created_at`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `seo` (`page_slug`,`meta_title`,`meta_description`,`meta_keywords`) VALUES
('home','HighQ Homes — Premium Construction in Puntland, Somalia','Leading construction company in Puntland delivering world-class residential and commercial projects. Broad Vision, Honest Service, Great Value.','construction, architecture, building, Puntland, Somalia, HighQ Homes'),
('about','About HighQ Homes — Our Story & Team','Learn about HighQ Homes, our mission, vision, and the expert team behind Puntlands finest construction company.','about highq homes, construction team, Puntland architects'),
('services','Construction & Design Services — HighQ Homes','Comprehensive construction and architectural services including architecture, exterior design, interior design and more.','construction services, architecture services, interior design, Puntland'),
('projects','Our Projects Portfolio — HighQ Homes','Browse our portfolio of completed and in-progress construction projects across Puntland, Somalia.','construction projects, building portfolio, Puntland construction'),
('paints','Paints & Building Materials — HighQ Homes','Quality paints and building materials including Saveto exterior textured paint for superior construction finishes.','building materials, paint products, Saveto paint'),
('gallery','Photo Gallery — HighQ Homes','View photos of our completed construction projects and architectural work across Puntland.','construction gallery, building photos, project gallery'),
('contact','Contact HighQ Homes — Project Consultation','Contact HighQ Homes for construction, architecture, interiors, paints, and project consultation.','contact highq homes, construction consultation, Puntland construction');

SET FOREIGN_KEY_CHECKS = 1;
