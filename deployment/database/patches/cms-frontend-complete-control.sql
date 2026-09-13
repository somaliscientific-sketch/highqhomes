-- HighQ Homes: Full Front-End CMS Control Database Patch
USE highqhomes;

-- Ensure page_sections unique key and table
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

-- ─── 1. Header & Footer Sections ─────────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('header','topbar','Top Info Bar','Show announcement bar','',JSON_OBJECT('show_topbar',1,'phone','+252 907 734 667','hours','Sat–Thu 8:00 AM – 8:00 PM','location','Garowe, Puntland, Somalia','quote_cta','WhatsApp Consultation'),NULL,1,1),
('header','cta','Get a Free Quote','/contact','Hello HighQ Homes, I would like to discuss a construction project.',NULL,NULL,1,2),
('footer','cta','Start Your Build','Ready to create something exceptional?','Get a Quote',JSON_OBJECT('button_text','Get a Quote','button_link','/contact'),NULL,1,1),
('footer','about','About HighQ Homes','Premium Construction','HighQ Homes delivers premium residential and commercial construction across Puntland with transparent delivery and lasting quality.',NULL,NULL,1,2)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = IFNULL(`data`, VALUES(`data`));

-- ─── 2. Homepage Sections Enrichment ──────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('home','hero_trust','Trust Indicators','','',JSON_ARRAY('Licensed & Insured','On-Time Delivery','Premium Finishes','Structured Quality Checks','Transparent Pricing','Dedicated Client Support'),NULL,1,1),
('home','capabilities','What We Build','Spaces Crafted With Purpose','From family homes to commercial landmarks — every project is planned, built, and finished to premium standards.',JSON_ARRAY(
  JSON_OBJECT('title','Residential Builds','text','Comfortable, durable homes designed for families and long-term living.','icon','bi-house-heart','img','https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80','href','/projects','mod','feature'),
  JSON_OBJECT('title','Commercial Spaces','text','Offices and business environments built for performance and presence.','icon','bi-building','img','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=900&q=80','href','/projects','mod',''),
  JSON_OBJECT('title','Premium Finishing','text','Refined interiors, paints, and detail work that elevate every space.','icon','bi-brush','img','https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=900&q=80','href','/paints','mod',''),
  JSON_OBJECT('title','Design & Planning','text','Concept-to-blueprint support with clear scope, budget, and timelines.','icon','bi-rulers','img','https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1200&q=80','href','/contact','mod','wide')
),NULL,1,2),
('home','about_highlights','Who We Are','Built on Integrity. Delivered with Precision.','HighQ Homes is a full-service construction partner turning ambitious plans into durable, beautifully finished spaces.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-award','title','Proven Track Record','text','Years of successful residential and commercial delivery across Somalia.'),
  JSON_OBJECT('icon','bi-people','title','One Accountable Team','text','Design, construction, and finishing coordinated under one roof.'),
  JSON_OBJECT('icon','bi-graph-up-arrow','title','Value-Driven Builds','text','Durable materials and smart planning that protect your investment.'),
  JSON_OBJECT('icon','bi-hand-thumbs-up','title','Client-First Approach','text','Clear communication from first meeting through final handover.')
),NULL,1,3),
('home','why_us','Why Choose Us','The HighQ Homes Difference','Six principles that guide every project — from first consultation to final handover.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-shield-check','title','Quality Assured','text','Structured inspections at every phase — materials, workmanship, and finish.'),
  JSON_OBJECT('icon','bi-calendar2-check','title','Clear Timelines','text','Milestones, budgets, and progress communicated with full transparency.'),
  JSON_OBJECT('icon','bi-gem','title','Premium Finishes','text','Durable materials and refined details built to last for decades.'),
  JSON_OBJECT('icon','bi-clipboard2-check','title','Documented Process','text','Every stage organized with clear approvals and practical documentation.'),
  JSON_OBJECT('icon','bi-buildings','title','Integrated Delivery','text','Architecture, construction, and finishing under one accountable team.'),
  JSON_OBJECT('icon','bi-headset','title','Client Support','text','Responsive guidance before, during, and after project handover.')
),NULL,1,4),
('home','process','Our Process','From Vision to Handover','A structured, transparent path designed to reduce risk and deliver exceptional results.',JSON_ARRAY(
  JSON_OBJECT('num','01','title','Discovery','text','We understand your goals, site, budget, and finish expectations.'),
  JSON_OBJECT('num','02','title','Design & Plan','text','Drawings, materials, cost planning, and a clear project schedule.'),
  JSON_OBJECT('num','03','title','Build & Control','text','Site execution with structured quality checks and progress updates.'),
  JSON_OBJECT('num','04','title','Handover','text','Final review, documentation, and delivery of a ready-to-use space.')
),NULL,1,5),
('home','excellence','Built to Last','Construction Excellence You Can Measure','Every HighQ Homes project is managed with the same standard — rigorous planning, accountable execution, and finishes that stand up to daily use and time.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-bricks','title','Quality Materials','text','We specify proven materials selected for strength, finish, and long-term performance in local conditions.'),
  JSON_OBJECT('icon','bi-hard-hat','title','Site Safety','text','Disciplined site practices, protective standards, and organized workflows on every active project.'),
  JSON_OBJECT('icon','bi-clipboard-data','title','Documented Delivery','text','Milestone reports, approvals, and handover documentation you can reference with confidence.'),
  JSON_OBJECT('icon','bi-house-check','title','After-Handover Care','text','Responsive support after completion so your space continues to perform as intended.')
),NULL,1,6),
('home','connect','Start With Confidence','Your Project Deserves a Builder You Can Trust','HighQ Homes combines disciplined project management, skilled craftsmanship, and transparent communication — so you stay informed from the first conversation to final handover.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-file-earmark-check','title','Transparent Quotes','text','Clear scope and pricing before any work begins.'),
  JSON_OBJECT('icon','bi-camera-reels','title','Progress Visibility','text','Regular updates so you always know project status.'),
  JSON_OBJECT('icon','bi-shield-lock','title','Quality Control','text','Structured inspections at every critical build phase.')
),NULL,1,7),
('home','stats','Achievements','Proof of Excellence','Real outcomes from real projects.',NULL,NULL,1,8),
('home','faq','Questions & Answers','Everything You Need to Know Before You Build','Clear answers to the questions clients ask most — so you can plan your project with confidence.',JSON_ARRAY(
  JSON_OBJECT('q','How do I get a project quote?','a','Share your site details, scope, and timeline via WhatsApp, phone, or our contact form. We respond with a structured consultation and transparent proposal.','icon','bi-calculator'),
  JSON_OBJECT('q','What types of projects do you handle?','a','HighQ Homes delivers residential homes, commercial spaces, renovations, premium finishing, and design-to-build planning for clients across Somalia.','icon','bi-buildings'),
  JSON_OBJECT('q','How long does a typical build take?','a','Timelines depend on scope, materials, and site conditions. After discovery, we provide a milestone schedule with clear dates and progress checkpoints.','icon','bi-calendar2-week'),
  JSON_OBJECT('q','Can I track progress during construction?','a','Yes. We provide regular site updates and milestone reviews so you stay informed at every major phase of the project.','icon','bi-camera-reels'),
  JSON_OBJECT('q','Do you manage finishing and interior details?','a','Absolutely. From structural work to paints and refined interior finishes, we offer integrated delivery for a complete, move-in-ready result.','icon','bi-brush')
),NULL,1,9)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`);

-- ─── 3. Projects Page Sections ────────────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('projects','hero','Portfolio','Projects Delivered With Confidence','Explore residential, commercial, and community builds — each delivered with structured planning, quality control, and premium finishes.',NULL,'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1920&q=80',1,1),
('projects','intro','Our Work','Built to Last. Designed to Impress.','Every project reflects our commitment to transparent delivery, disciplined craftsmanship, and spaces people trust for generations.',NULL,NULL,1,2),
('projects','pillars','Our Standards','Engineered for Excellence','Principles that distinguish our construction projects from ground break to handover.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-clipboard-data','title','Structured delivery','text','Milestone planning, progress updates, and quality checks at every phase.'),
  JSON_OBJECT('icon','bi-gem','title','Premium finishes','text','Materials and craftsmanship held to standards you can see and measure.'),
  JSON_OBJECT('icon','bi-shield-check','title','Transparent scope','text','Clear pricing, documented scope, and accountable communication.'),
  JSON_OBJECT('icon','bi-geo-alt','title','Local expertise','text','Deep experience building across Puntland — from Garowe to Bosaso.')
),NULL,1,3),
('projects','approach','Our Approach','How We Build Your Vision','From concept design through final key handover, our phased delivery ensures zero surprises.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-search','title','Discovery','text','Site review, goals, and feasibility before design begins.'),
  JSON_OBJECT('icon','bi-rulers','title','Design & planning','text','Drawings, approvals, and a milestone schedule you can track.'),
  JSON_OBJECT('icon','bi-hammer','title','Build execution','text','Disciplined site work with structured QA inspections.'),
  JSON_OBJECT('icon','bi-key','title','Handover','text','Final walkthrough, documentation, and after-care support.')
),NULL,1,4),
('projects','cta','Start Your Project','Ready to Build Your Next Space?','Share your drawings or ideas with our engineering team for an accurate estimate and consultation.',JSON_OBJECT('button_text','Get a Quote','button_link','/contact'),NULL,1,5)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = IFNULL(`data`, VALUES(`data`));

-- ─── 4. Gallery Page Sections ─────────────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('gallery','hero','Our Work','Project Gallery','Photos from residential, commercial, and community projects across Puntland — structure, finishes, and handover.',NULL,'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80',1,1),
('gallery','intro','Visual Portfolio','Craftsmanship in Every Frame','Browse real project imagery — from structural milestones to final finishes — and see the quality HighQ Homes delivers.',NULL,NULL,1,2),
('gallery','highlights','Visual Standards','What You Will See','A comprehensive look at our craftsmanship across all construction stages.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-buildings','title','Residential & commercial','text','Exterior and interior shots from homes, offices, and mixed-use builds.'),
  JSON_OBJECT('icon','bi-brush','title','Finishing details','text','Kitchens, bathrooms, joinery, and premium fit-out craftsmanship.'),
  JSON_OBJECT('icon','bi-hammer','title','On-site progress','text','Structure, cladding, and quality checks throughout delivery.'),
  JSON_OBJECT('icon','bi-tree','title','Landscape & exterior','text','Facades, compounds, hardscape, and outdoor spaces.')
),NULL,1,3),
('gallery','cta','Experience the Quality','Have a Vision for Your Build?','Let us bring your architectural and construction plans to life with certified precision.',JSON_OBJECT('button_text','Discuss Your Project','button_link','/contact'),NULL,1,4)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = IFNULL(`data`, VALUES(`data`));

-- ─── 5. Paints Page Sections ──────────────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('paints','hero','Products','Premium Paints & Coatings','Professional-grade finishes for exterior walls, interiors, and textured surfaces — supplied with expert guidance for lasting results.',NULL,'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1920&q=80',1,1),
('paints','intro','Finishing Excellence','Colors & Coatings That Endure','High-performance paints and architectural coatings engineered to withstand weather, sun, and wear.',NULL,NULL,1,2),
('paints','benefits','Why Choose HighQ Paints','Durability & Aesthetics Combined','Our curated coating solutions guarantee lasting vibrancy and protection.',JSON_ARRAY(
  JSON_OBJECT('icon','bi-shield-check','title','Quality assured','text','Trusted brands selected for durability in Puntland\'s climate.'),
  JSON_OBJECT('icon','bi-person-workspace','title','Expert guidance','text','Product advice for exterior, interior, and textured finishes.'),
  JSON_OBJECT('icon','bi-truck','title','Project supply','text','Coordinated delivery for residential and commercial builds.'),
  JSON_OBJECT('icon','bi-brush','title','Application support','text','Surface prep, coverage, and professional application tips.')
),NULL,1,3),
('paints','cta','Color Your Space','Need Paint Advice or Bulk Supply?','Speak with our paint specialists to find the perfect shades and protective coatings for your project.',JSON_OBJECT('button_text','Enquire Now','button_link','/contact'),NULL,1,4)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = IFNULL(`data`, VALUES(`data`));

-- ─── 6. Contact Page Sections ─────────────────────────────────
INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`is_enabled`,`sort_order`) VALUES
('contact','hero','Contact Us','Let\'s Plan Your Next Project','Share your site details, drawings, or goals — our team will respond with clear next steps and estimates.',NULL,'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1920&q=80',1,1),
('contact','process','Inquiry Process','How We Handle Your Request','A streamlined onboarding experience from initial contact to proposal.',JSON_ARRAY(
  JSON_OBJECT('num','01','title','Review','text','We assess project type, location, and scope.'),
  JSON_OBJECT('num','02','title','Clarify','text','We follow up for drawings or key site details.'),
  JSON_OBJECT('num','03','title','Plan','text','You receive the next step for design or estimate.')
),NULL,1,2)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = IFNULL(`data`, VALUES(`data`));
