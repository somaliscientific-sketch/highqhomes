USE highqhomes;

INSERT INTO `page_sections` (`page_key`,`section_key`,`title`,`subtitle`,`content`,`data`,`image_url`,`sort_order`) VALUES
('services','hero','End-to-End Construction Services','Built with clarity, quality, and accountability','From architecture and planning to construction, finishing, and handover — HighQ Homes delivers integrated services under one accountable team.','["Licensed & Insured","Transparent Quotes","Premium Finishes","On-Time Delivery"]','https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1920&q=80',1),
('services','intro','What We Deliver','One team. Every phase.','Each service is managed with structured milestones, quality inspections, and clear communication — so you always know where your project stands.',NULL,NULL,2),
('services','process','How We Work','From first meeting to final handover','A proven delivery path designed to reduce risk and keep your build on schedule.',JSON_ARRAY(JSON_OBJECT('num','01','title','Consultation','text','We review your goals, site, budget, and timeline expectations.'),JSON_OBJECT('num','02','title','Design & Scope','text','Drawings, materials, and a transparent scope with milestone pricing.'),JSON_OBJECT('num','03','title','Build & QA','text','Site execution with structured quality checks and progress updates.'),JSON_OBJECT('num','04','title','Handover','text','Final walkthrough, documentation, and after-care support.')),NULL,3),
('services','faq','Service Questions','Answers before you start','Common questions about how HighQ Homes plans, prices, and delivers each service.',JSON_ARRAY(JSON_OBJECT('q','Do you offer design-only services?','a','Yes. We provide architecture, exterior, interior, and site planning as standalone or integrated packages.','icon','bi-rulers'),JSON_OBJECT('q','Can I combine multiple services in one project?','a','Absolutely. Most clients choose integrated delivery — design, construction, and finishing under one team.','icon','bi-layers'),JSON_OBJECT('q','How are service quotes structured?','a','Quotes are milestone-based with clear scope, materials, and timeline checkpoints before work begins.','icon','bi-calculator'),JSON_OBJECT('q','Do you work on renovations and upgrades?','a','Yes. We handle new builds, renovations, premium finishing, and phased upgrade projects.','icon','bi-tools')),NULL,4)
ON DUPLICATE KEY UPDATE
  `title` = VALUES(`title`),
  `subtitle` = VALUES(`subtitle`),
  `content` = VALUES(`content`),
  `data` = VALUES(`data`),
  `image_url` = VALUES(`image_url`);
