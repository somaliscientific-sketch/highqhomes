-- HighQ Homes CMS v2 — roles, media library, page sections, blog
USE highqhomes;

-- ─── Roles (Super Admin, Admin, Editor, Viewer) ─────────────
DELETE FROM `roles`;
INSERT INTO `roles` (`name`,`label`,`permissions`) VALUES
('super_admin','Super Admin','["*"]'),
('admin','Admin','["content.view","content.manage","media.manage","messages.view","messages.manage","settings.manage","users.manage","seo.manage","menus.manage","sections.manage","blog.manage"]'),
('editor','Editor','["content.view","content.manage","media.manage","messages.view","sections.manage","blog.manage"]'),
('viewer','Viewer','["content.view","messages.view"]');

-- Expand user roles
ALTER TABLE `users` MODIFY `role` VARCHAR(40) NOT NULL DEFAULT 'editor';

UPDATE `users` SET `role` = 'super_admin' WHERE `role` = 'admin';

-- ICT staff account
INSERT INTO `users` (`name`,`email`,`password`,`role`,`is_active`) VALUES
('ICT Admin','info@highqhomes.com',
 '$2y$12$ZOKI03ktqVnMeU/JF2GKku4kR6peh5j1PzmDnt3SMhRhmacBFFNkG',
 'super_admin',1)
ON DUPLICATE KEY UPDATE
  `password` = VALUES(`password`),
  `role` = 'super_admin',
  `is_active` = 1;

-- ─── Media library enhancements ─────────────────────────────
ALTER TABLE `media`
  ADD COLUMN `caption` VARCHAR(300) DEFAULT NULL AFTER `alt_text`,
  ADD COLUMN `original_name` VARCHAR(220) DEFAULT NULL AFTER `title`,
  ADD COLUMN `mime_type` VARCHAR(100) DEFAULT NULL AFTER `file_type`,
  ADD COLUMN `uploaded_by` INT UNSIGNED DEFAULT NULL AFTER `folder`,
  ADD COLUMN `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

UPDATE `media` SET `mime_type` = `file_type` WHERE `mime_type` IS NULL;

CREATE TABLE IF NOT EXISTS `media_usage` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `media_id`    INT UNSIGNED NOT NULL,
  `entity_type` VARCHAR(60)  NOT NULL,
  `entity_id`   INT UNSIGNED NOT NULL,
  `field_name`  VARCHAR(80)  DEFAULT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_media` (`media_id`),
  INDEX `idx_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Page sections (Home, About, Contact, Header, Footer) ───
CREATE TABLE IF NOT EXISTS `page_sections` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_key`     VARCHAR(60)  NOT NULL,
  `section_key`  VARCHAR(80)  NOT NULL,
  `title`        VARCHAR(220) DEFAULT NULL,
  `subtitle`     VARCHAR(300) DEFAULT NULL,
  `content`      TEXT         DEFAULT NULL,
  `data`         JSON         DEFAULT NULL,
  `image_url`    VARCHAR(400) DEFAULT NULL,
  `is_enabled`   TINYINT(1)   NOT NULL DEFAULT 1,
  `sort_order`   INT          NOT NULL DEFAULT 0,
  `updated_at`   DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_page_section` (`page_key`, `section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── Blog ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`            VARCHAR(220) NOT NULL,
  `slug`             VARCHAR(240) NOT NULL UNIQUE,
  `excerpt`          VARCHAR(400) DEFAULT NULL,
  `content`          MEDIUMTEXT   DEFAULT NULL,
  `featured_image`   VARCHAR(400) DEFAULT NULL,
  `author_id`        INT UNSIGNED DEFAULT NULL,
  `meta_title`       VARCHAR(200) DEFAULT NULL,
  `meta_description` VARCHAR(400) DEFAULT NULL,
  `is_published`     TINYINT(1)   NOT NULL DEFAULT 0,
  `published_at`     DATETIME     DEFAULT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Homepage section seeds
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`sort_order`) VALUES
('home','hero_trust','','','',JSON_ARRAY('Licensed & Insured','On-Time Delivery','Premium Finishes','Structured Quality Checks','Transparent Pricing','Dedicated Client Support'),1),
('home','capabilities','Our Capabilities','What We Build','End-to-end construction capabilities for residential, commercial, and premium finishing projects.',NULL,2),
('home','why_us','Why Choose Us','Built on Trust & Craft','',JSON_ARRAY(JSON_OBJECT('icon','bi-shield-check','title','Quality Assured','text','Structured inspections at every phase — materials, workmanship, and finish.'),JSON_OBJECT('icon','bi-calendar2-check','title','Clear Timelines','text','Milestones, budgets, and progress communicated with full transparency.'),JSON_OBJECT('icon','bi-gem','title','Premium Finishes','text','Durable materials and refined details built to last for decades.'),JSON_OBJECT('icon','bi-clipboard2-check','title','Documented Process','text','Every stage organized with clear approvals and practical documentation.'),JSON_OBJECT('icon','bi-buildings','title','Integrated Delivery','text','Architecture, construction, and finishing under one accountable team.'),JSON_OBJECT('icon','bi-headset','title','Client Support','text','Responsive guidance before, during, and after project handover.')),3),
('home','process','Our Process','From Vision to Handover','A structured, transparent path designed to reduce risk and deliver exceptional results.',JSON_ARRAY(JSON_OBJECT('num','01','title','Discovery','text','We understand your goals, site, budget, and finish expectations.'),JSON_OBJECT('num','02','title','Design & Plan','text','Drawings, materials, cost planning, and a clear project schedule.'),JSON_OBJECT('num','03','title','Build & Control','text','Site execution with structured quality checks and progress updates.'),JSON_OBJECT('num','04','title','Handover','text','Final review, documentation, and delivery of a ready-to-use space.')),4),
('home','excellence','Built to Last','Construction Excellence You Can Measure','Every HighQ Homes project is managed with the same standard — rigorous planning, accountable execution, and finishes that stand up to daily use and time.',JSON_ARRAY(JSON_OBJECT('icon','bi-bricks','title','Quality Materials','text','We specify proven materials selected for strength, finish, and long-term performance in local conditions.'),JSON_OBJECT('icon','bi-hard-hat','title','Site Safety','text','Disciplined site practices, protective standards, and organized workflows on every active project.'),JSON_OBJECT('icon','bi-clipboard-data','title','Documented Delivery','text','Milestone reports, approvals, and handover documentation you can reference with confidence.'),JSON_OBJECT('icon','bi-house-check','title','After-Handover Care','text','Responsive support after completion so your space continues to perform as intended.')),5),
('home','connect','Start With Confidence','Your Project Deserves a Builder You Can Trust','HighQ Homes combines disciplined project management, skilled craftsmanship, and transparent communication — so you stay informed from the first conversation to final handover.',JSON_ARRAY(JSON_OBJECT('icon','bi-file-earmark-check','title','Transparent Quotes','text','Clear scope and pricing before any work begins.'),JSON_OBJECT('icon','bi-camera-reels','title','Progress Visibility','text','Regular updates so you always know project status.'),JSON_OBJECT('icon','bi-shield-lock','title','Quality Control','text','Structured inspections at every critical build phase.')),6),
('home','faq','Questions & Answers','Everything You Need to Know Before You Build','Clear answers to the questions clients ask most — so you can plan your project with confidence.',JSON_ARRAY(JSON_OBJECT('q','How do I get a project quote?','a','Share your site details, scope, and timeline via WhatsApp, phone, or our contact form. We respond with a structured consultation and transparent proposal.','icon','bi-calculator'),JSON_OBJECT('q','What types of projects do you handle?','a','HighQ Homes delivers residential homes, commercial spaces, renovations, premium finishing, and design-to-build planning for clients across Somalia.','icon','bi-buildings'),JSON_OBJECT('q','How long does a typical build take?','a','Timelines depend on scope, materials, and site conditions. After discovery, we provide a milestone schedule with clear dates and progress checkpoints.','icon','bi-calendar2-week'),JSON_OBJECT('q','Can I track progress during construction?','a','Yes. We provide regular site updates and milestone reviews so you stay informed at every major phase of the project.','icon','bi-camera-reels'),JSON_OBJECT('q','Do you manage finishing and interior details?','a','Absolutely. From structural work to paints and refined interior finishes, we offer integrated delivery for a complete, move-in-ready result.','icon','bi-brush')),7),
('about','values','Our Values','What Guides Every Project','',JSON_ARRAY(JSON_OBJECT('icon','bi-shield-check','title','Integrity First','text','Honest timelines, transparent pricing, and accountable communication at every stage.'),JSON_OBJECT('icon','bi-award','title','Quality Without Compromise','text','Premium materials, skilled trades, and structured inspections on every build.'),JSON_OBJECT('icon','bi-people','title','People-Centered Delivery','text','We listen first, then design and build spaces that serve real families and businesses.'),JSON_OBJECT('icon','bi-lightbulb','title','Innovation & Craft','text','Modern methods paired with thoughtful design rooted in local context.')),1),
('about','process','How We Work','Our Delivery Process','',JSON_ARRAY(JSON_OBJECT('num','01','title','Consultation','text','We learn your goals, site conditions, and budget expectations.'),JSON_OBJECT('num','02','title','Planning','text','Drawings, materials, and a milestone schedule you can track.'),JSON_OBJECT('num','03','title','Construction','text','Disciplined site execution with quality checks at every phase.'),JSON_OBJECT('num','04','title','Handover','text','Final walkthrough, documentation, and after-care support.')),2),
('contact','hero','Contact Us','Start Your Project Today','Reach HighQ Homes for quotes, consultations, and project planning. We respond quickly via phone, WhatsApp, or email.',NULL,1),
('header','cta','Contact Us','','Hello HighQ Homes, I would like to discuss a construction project.',NULL,1),
('footer','about','','','HighQ Homes delivers premium residential and commercial construction across Puntland with transparent delivery and lasting quality.',NULL,1)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = VALUES(`data`);

INSERT IGNORE INTO `seo` (`page_slug`,`meta_title`,`meta_description`) VALUES
('blog','Blog & Insights — HighQ Homes','Construction insights, project updates, and building tips from the HighQ Homes team.');

INSERT IGNORE INTO `menus` (`label`,`url`,`location`,`sort_order`,`is_published`) VALUES
('Blog','/blog','primary',8,1);
