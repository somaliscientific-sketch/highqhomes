-- Site identity CMS group
INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES
('legal_name', '', 'text', 'Legal / Copyright Name', 'identity', 3),
('footer_logo', '', 'image', 'Footer Logo Mark', 'identity', 5);

UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 1, `label` = 'Site Name' WHERE `key` = 'site_name';
UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 2, `label` = 'Tagline' WHERE `key` = 'tagline';
UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 4, `label` = 'Header Logo Mark' WHERE `key` = 'logo';
UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 6, `label` = 'Favicon' WHERE `key` = 'favicon';
UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 7, `label` = 'Primary Color' WHERE `key` = 'primary_color';
UPDATE `settings` SET `group_name` = 'identity', `sort_order` = 8, `label` = 'Accent Color' WHERE `key` = 'accent_color';
