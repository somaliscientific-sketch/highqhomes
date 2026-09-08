-- HighQ Homes company profile update, sourced from the supplied company brochure.
-- Apply after schema.sql. This is safe to re-run.

USE `highqhomes`;

-- Company identity and verified contact details.
UPDATE `settings` SET `value` = 'HighQ Homes is a registered construction company based in Garowe City, Puntland, Somalia. Established in 2016 by Bashir Mohamed and Yahye Ismail, we create high-quality residential and commercial properties through meticulous planning, quality materials, skilled craftsmanship, sustainable practices, and modern design.' WHERE `key` = 'about_text';
UPDATE `settings` SET `value` = 'Garowe City, Puntland State of Somalia' WHERE `key` = 'address';
UPDATE `settings` SET `value` = 'HighQhomes@outlook.com' WHERE `key` = 'email';
UPDATE `settings` SET `value` = '+252 907 734 667' WHERE `key` = 'phone';
UPDATE `settings` SET `value` = '+252 907 900 101' WHERE `key` = 'phone_2';
UPDATE `settings` SET `value` = '+252907734667' WHERE `key` = 'whatsapp';
UPDATE `settings` SET `value` = 'Innovative and sustainable housing solutions for Puntland.' WHERE `key` = 'tagline';
UPDATE `settings` SET `value` = 'To be the premier provider of innovative and sustainable housing solutions in Puntland State, contributing to the development and modernization of Garowe City through homes that serve residents and enhance the community.' WHERE `key` = 'mission';
UPDATE `settings` SET `value` = 'Deliver exceptional quality through durable, safe, and aesthetically considered homes; sustainable materials and energy-efficient design; transparent communication; and lasting relationships built on trust, respect, and accountability.' WHERE `key` = 'vision';
UPDATE `settings` SET `value` = '8' WHERE `key` = 'stat_projects';
UPDATE `settings` SET `value` = '3' WHERE `key` = 'stat_clients';
UPDATE `settings` SET `value` = '5' WHERE `key` = 'stat_satisfaction';
UPDATE `settings` SET `value` = '2016' WHERE `key` = 'stat_awards';
UPDATE `settings` SET `value` = '10' WHERE `key` = 'stat_years';
UPDATE `settings` SET `value` = 'Discuss your Garowe project with HighQ Homes for clear planning, practical budgeting, and quality delivery.' WHERE `key` = 'home_cta_text';
UPDATE `settings` SET `value` = 'Plan Your Project With HighQ Homes' WHERE `key` = 'home_cta_title';

-- Homepage messaging.
UPDATE `sliders`
SET `title` = 'Innovative Homes Built for Garowe',
    `subtitle` = 'Residential and Commercial Construction',
    `description` = 'HighQ Homes combines meticulous planning, quality materials, skilled craftsmanship, and transparent delivery for durable spaces that serve people and communities.',
    `button_text` = 'Contact Us',
    `button_link` = '/contact',
    `button_text_2` = 'Explore Projects',
    `button_link_2` = '/projects'
WHERE `id` = 1;
UPDATE `sliders`
SET `title` = 'Quality That Lasts',
    `subtitle` = 'Established in Garowe Since 2016',
    `description` = 'We build residential and commercial properties with an accountable approach to quality, sustainability, customer experience, and delivery.',
    `button_text` = 'Our Services',
    `button_link` = '/services',
    `button_text_2` = 'Our Story',
    `button_link_2` = '/about'
WHERE `id` = 2;
UPDATE `sliders`
SET `title` = 'Built With Integrity',
    `subtitle` = 'Consulting, Construction and Renovation',
    `description` = 'From project planning and budgeting to construction and renovation, HighQ Homes delivers thoughtful work with honesty, transparency, and care.',
    `button_text` = 'View Projects',
    `button_link` = '/projects',
    `button_text_2` = 'Contact Us',
    `button_link_2` = '/contact'
WHERE `id` = 3;

-- The three brochure services replace the older generic service catalog.
UPDATE `services` SET
  `title` = 'Project Consulting', `slug` = 'project-consulting',
  `short_description` = 'High-quality ideas for project planning and budgeting before work begins.',
  `description` = '<p>We support clients with practical planning, clear budgeting, and considered project guidance from the first consultation.</p>',
  `icon` = 'bi-chat-square-text', `is_featured` = 1, `is_published` = 1, `sort_order` = 1
WHERE `id` = 1;
UPDATE `services` SET
  `title` = 'Construction', `slug` = 'construction',
  `short_description` = 'Residential and commercial construction delivered with honesty, transparency, and ethical conduct.',
  `description` = '<p>HighQ Homes delivers carefully planned construction using quality materials, skilled craftsmanship, and clear communication throughout delivery.</p>',
  `icon` = 'bi-buildings', `is_featured` = 1, `is_published` = 1, `sort_order` = 2
WHERE `id` = 2;
UPDATE `services` SET
  `title` = 'Renovation', `slug` = 'renovation',
  `short_description` = 'Innovative solutions for improving and renewing existing spaces.',
  `description` = '<p>We assess existing buildings and deliver practical renovation work that improves function, quality, and long-term value.</p>',
  `icon` = 'bi-tools', `is_featured` = 1, `is_published` = 1, `sort_order` = 3
WHERE `id` = 3;
UPDATE `services` SET `is_featured` = 0, `is_published` = 0 WHERE `id` IN (4, 5, 6);

-- Keep named founders only; the brochure does not identify other public team members.
UPDATE `team` SET
  `name` = 'Bashir Mohamed', `position` = 'Co-Founder',
  `bio` = 'Co-founder of HighQ Homes, established in Garowe in 2016 to deliver high-quality residential properties with integrity, skilled craftsmanship, and modern design.',
  `is_published` = 1, `sort_order` = 1
WHERE `id` = 1;
UPDATE `team` SET
  `name` = 'Yahye Ismail', `position` = 'Co-Founder',
  `bio` = 'Co-founder of HighQ Homes, helping guide the company toward sustainable housing solutions, trusted client relationships, and quality project delivery.',
  `is_published` = 1, `sort_order` = 2
WHERE `id` = 2;
UPDATE `team` SET `is_published` = 0 WHERE `id` NOT IN (1, 2);

-- Portfolio: three completed projects and five current projects from the brochure.
UPDATE `projects` SET
  `title` = 'Zone Grand', `slug` = 'zone-grand', `category` = 'residential', `status` = 'completed',
  `location` = 'Garowe, Puntland', `client_name` = NULL, `project_year` = NULL, `project_area` = '169 m2 · G+1',
  `short_description` = 'A G+1 residential building with 169 square metres and a spacious 6 m high-ceiling living room.',
  `description` = '<p>Zone Grand is a G+1 residential building of 169 square metres. Both floors were planned around a spacious living room with a 6 m high ceiling, with interior design support integrated into the home.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 1
WHERE `id` = 1;
UPDATE `projects` SET
  `title` = 'Twin Houses', `slug` = 'twin-houses', `category` = 'residential', `status` = 'completed',
  `location` = 'Garowe, Puntland', `client_name` = NULL, `project_year` = NULL, `project_area` = '200 m2 each',
  `short_description` = 'A newly finished residential complex of two 200 square metre homes.',
  `description` = '<p>Twin Houses is a newly finished residential complex with two 200 square metre buildings. Each home was delivered to an agreed project budget with a focus on practical family living.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 2
WHERE `id` = 2;
UPDATE `projects` SET
  `title` = 'NODO Building', `slug` = 'nodo-building', `category` = 'commercial', `status` = 'completed',
  `location` = 'Near Kalis Cafeteria, Garowe, Puntland', `client_name` = 'NODO NGO', `project_year` = NULL, `project_area` = '240 m2',
  `short_description` = 'A 240 square metre building for housing and office accommodation for NODO NGO.',
  `description` = '<p>The NODO Building is a 240 square metre completed building designed for both housing and office accommodation for NODO NGO, located near Kalis Cafeteria in Garowe.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 3
WHERE `id` = 3;
UPDATE `projects` SET
  `title` = 'Residential House', `slug` = 'residential-house', `category` = 'residential', `status` = 'in_progress',
  `location` = 'Garowe, Puntland', `client_name` = NULL, `project_year` = 2024, `project_area` = '250 m2 · Two storeys',
  `short_description` = 'A 250 square metre two-storey residential house, under construction since late June 2024.',
  `description` = '<p>A 250 square metre, two-storey residential house currently under construction. Work began in late June 2024.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 4
WHERE `id` = 4;
UPDATE `projects` SET
  `title` = 'Arc Side', `slug` = 'arc-side', `category` = 'commercial', `status` = 'in_progress',
  `location` = 'Near the Arc, opposite Grand Hotel, Garowe, Puntland', `client_name` = NULL, `project_year` = NULL, `project_area` = NULL,
  `short_description` = 'A mixed-use building with ground-floor shops and upper residential apartments.',
  `description` = '<p>Arc Side is a current commercial and residential apartment building with ground-floor shopping stores and upper residential apartments, located near the Arc opposite Grand Hotel.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 5
WHERE `id` = 5;
UPDATE `projects` SET
  `title` = 'Residential Building', `slug` = 'spegethi-residential-building', `category` = 'residential', `status` = 'in_progress',
  `location` = 'Spegethi Street, Garowe, Puntland', `client_name` = NULL, `project_year` = NULL, `project_area` = '180 m2',
  `short_description` = 'A 180 square metre residential building with a large compound and integrated two-car parking garage.',
  `description` = '<p>This current 180 square metre residential building near Spegethi Street includes a large compound and a built-in two-car parking garage.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 6
WHERE `id` = 6;
UPDATE `projects` SET
  `title` = 'Bossaso Chicken Building', `slug` = 'bossaso-chicken-building', `category` = 'commercial', `status` = 'in_progress',
  `location` = 'Garowe, Puntland', `client_name` = 'Bossaso Chicken', `project_year` = NULL, `project_area` = '260 m2 · Four storeys',
  `short_description` = 'A 260 square metre four-storey mixed-use building with an elevator planned for the west wing.',
  `description` = '<p>Bossaso Chicken is a current 260 square metre, four-storey building designed for commercial and residential use. The west wing includes an elevator.</p>',
  `is_featured` = 1, `is_published` = 1, `sort_order` = 7
WHERE `id` = 7;
INSERT INTO `projects` (`title`, `slug`, `category`, `status`, `location`, `client_name`, `project_year`, `project_area`, `short_description`, `description`, `featured_image`, `gallery_images`, `is_featured`, `is_published`, `sort_order`)
VALUES ('Grand Hotel Adjacent Building', 'grand-hotel-adjacent-building', 'commercial', 'in_progress', 'Adjacent to Grand Hotel, Garowe, Puntland', NULL, NULL, '420 m2', 'A current 420 square metre commercial building adjacent to Grand Hotel.', '<p>A current 420 square metre commercial building adjacent to Grand Hotel. The project is at ground-floor stage.</p>', NULL, NULL, 1, 1, 8)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `category` = VALUES(`category`), `status` = VALUES(`status`), `location` = VALUES(`location`), `client_name` = VALUES(`client_name`), `project_year` = VALUES(`project_year`), `project_area` = VALUES(`project_area`), `short_description` = VALUES(`short_description`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_published` = VALUES(`is_published`), `sort_order` = VALUES(`sort_order`);

-- Page-builder content used by the homepage, About page, services page, header CTA, and footer.
INSERT INTO `page_sections` (`page_key`, `section_key`, `title`, `subtitle`, `content`, `data`, `image_url`, `is_enabled`, `sort_order`)
VALUES
('about', 'hero', 'HighQ Homes', 'Registered construction company in Garowe since 2016', 'Founded by Bashir Mohamed and Yahye Ismail, HighQ Homes creates durable, modern residential and commercial properties for Puntland.', NULL, NULL, 1, 1),
('about', 'history', 'Our History', 'Built in Garowe. Focused on quality.', 'HighQ Homes was established in 2016 in Garowe City, Puntland State of Somalia. The company was founded to create reputable, high-quality residential properties and has grown through meticulous planning, quality materials, skilled craftsmanship, sustainability, and modern design.', JSON_OBJECT('facts', JSON_ARRAY(JSON_OBJECT('label','Established','value','2016'), JSON_OBJECT('label','Location','value','Garowe, Puntland'), JSON_OBJECT('label','Founders','value','Bashir Mohamed and Yahye Ismail')), 'timeline', JSON_ARRAY(JSON_OBJECT('year','2016','text','HighQ Homes was established in Garowe by Bashir Mohamed and Yahye Ismail.'), JSON_OBJECT('year','Today','text','We continue to deliver residential and commercial projects with quality, transparency, and sustainable design principles.'))), NULL, 1, 2),
('about', 'values', 'Our Corporate Principles', 'Customer satisfaction and quality above everything else.', 'Our work is guided by customer commitment, quality, accountable delivery, and integrity.', JSON_ARRAY(JSON_OBJECT('icon','bi-people','title','Customer Commitment','text','We build relationships that make a positive difference in our customers lives.'), JSON_OBJECT('icon','bi-award','title','Quality','text','We provide outstanding service and products that deliver premium value.'), JSON_OBJECT('icon','bi-calendar2-check','title','Accountable Delivery','text','We are personally accountable for delivering commitments on time and on budget.'), JSON_OBJECT('icon','bi-shield-check','title','Integrity','text','We uphold high standards of integrity in all our actions.')), NULL, 1, 3),
('home', 'why_us', 'Why HighQ Homes', 'Quality, trust, and accountable delivery.', 'We create durable homes and commercial spaces that meet practical needs and enhance the Garowe community.', JSON_ARRAY(JSON_OBJECT('icon','bi-award','title','Exceptional Quality','text','Durable, safe, and aesthetically considered work that meets high standards.'), JSON_OBJECT('icon','bi-leaf','title','Sustainable Practices','text','Environmentally considerate materials and energy-efficient design principles.'), JSON_OBJECT('icon','bi-chat-square-heart','title','Customer Experience','text','Transparent communication, personalized service, and timely completion.'), JSON_OBJECT('icon','bi-people','title','Lasting Relationships','text','Trusted partnerships with clients, suppliers, and stakeholders.')), NULL, 1, 3),
('home', 'process', 'How We Help', 'Clear guidance from the first conversation.', 'Start with practical planning, move through transparent construction, and renew spaces with thoughtful renovation.', JSON_ARRAY(JSON_OBJECT('num','01','title','Consulting','text','High-quality ideas for planning and budgeting the project under consultation.'), JSON_OBJECT('num','02','title','Construction','text','Honest, transparent, and ethical conduct throughout every project interaction.'), JSON_OBJECT('num','03','title','Renovation','text','Innovative solutions and approaches to improve existing work.')), NULL, 1, 4),
('services', 'hero', 'Consulting, Construction and Renovation', 'Practical planning. Quality delivery. Lasting value.', 'HighQ Homes supports Garowe clients with clear project consulting, transparent construction, and thoughtful renovation services.', NULL, NULL, 1, 1),
('services', 'intro', 'What We Deliver', 'One accountable team from planning to improvement.', 'We bring together practical advice, quality construction, and renovation solutions designed around your project needs.', NULL, NULL, 1, 2),
('header', 'cta', 'Contact Us', '', 'Contact HighQ Homes to discuss planning, construction, or renovation in Garowe.', NULL, NULL, 1, 1),
('footer', 'about', '', '', 'HighQ Homes is a registered construction company in Garowe, Puntland. Established in 2016, we deliver high-quality residential and commercial projects with integrity, quality, and sustainable thinking.', NULL, NULL, 1, 1)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`), `content` = VALUES(`content`), `data` = VALUES(`data`), `image_url` = VALUES(`image_url`), `is_enabled` = VALUES(`is_enabled`), `sort_order` = VALUES(`sort_order`);

-- Page-specific SEO based on the verified company profile.
UPDATE `seo` SET `meta_title` = 'HighQ Homes | Construction Company in Garowe, Puntland', `meta_description` = 'HighQ Homes is a registered construction company established in Garowe in 2016. We deliver quality residential and commercial construction, consulting, and renovation.', `meta_keywords` = 'HighQ Homes, Garowe construction, Puntland construction, residential building, commercial building, renovation, construction consulting' WHERE `page_slug` = 'home';
UPDATE `seo` SET `meta_title` = 'About HighQ Homes | Established in Garowe Since 2016', `meta_description` = 'Learn about HighQ Homes, founded in Garowe by Bashir Mohamed and Yahye Ismail to deliver durable, modern, and sustainable properties.', `meta_keywords` = 'HighQ Homes history, Bashir Mohamed, Yahye Ismail, Garowe builders, Puntland construction company' WHERE `page_slug` = 'about';
UPDATE `seo` SET `meta_title` = 'Construction Services in Garowe | HighQ Homes', `meta_description` = 'HighQ Homes offers project consulting, quality construction, and renovation services for residential and commercial properties in Garowe.', `meta_keywords` = 'Garowe construction services, project consulting, renovation, HighQ Homes' WHERE `page_slug` = 'services';
UPDATE `seo` SET `meta_title` = 'HighQ Homes Projects | Garowe, Puntland', `meta_description` = 'Explore HighQ Homes completed and current residential and commercial projects in Garowe, Puntland.', `meta_keywords` = 'HighQ Homes projects, Garowe buildings, Puntland construction portfolio' WHERE `page_slug` = 'projects';
UPDATE `seo` SET `meta_title` = 'Contact HighQ Homes | Garowe Construction Company', `meta_description` = 'Contact HighQ Homes in Garowe, Puntland for project consulting, construction, and renovation.', `meta_keywords` = 'contact HighQ Homes, Garowe construction company, Puntland builders' WHERE `page_slug` = 'contact';
