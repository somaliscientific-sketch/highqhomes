USE highqhomes;

UPDATE `roles` SET `name` = 'super_admin', `label` = 'Super Admin', `permissions` = '["*"]' WHERE `name` = 'admin';

INSERT IGNORE INTO `roles` (`name`,`label`,`permissions`) VALUES
('admin','Admin','["content.view","content.manage","media.manage","messages.view","messages.manage","settings.manage","users.manage","seo.manage","menus.manage","sections.manage","blog.manage"]'),
('viewer','Viewer','["content.view","messages.view"]');

UPDATE `roles` SET `permissions` = '["content.view","content.manage","media.manage","messages.view","sections.manage","blog.manage"]' WHERE `name` = 'editor';

ALTER TABLE `users` MODIFY `role` VARCHAR(40) NOT NULL DEFAULT 'editor';

UPDATE `users` SET `role` = 'super_admin' WHERE `email` = 'admin@highqhomes.net' OR `role` = '' OR `role` IS NULL;

INSERT INTO `users` (`name`,`email`,`password`,`role`,`is_active`) VALUES
('ICT Admin','info@highqhomes.com','$2y$12$ZOKI03ktqVnMeU/JF2GKku4kR6peh5j1PzmDnt3SMhRhmacBFFNkG','super_admin',1)
ON DUPLICATE KEY UPDATE `password` = VALUES(`password`), `role` = 'super_admin', `is_active` = 1;

ALTER TABLE `media`
  ADD COLUMN `caption` VARCHAR(300) DEFAULT NULL AFTER `alt_text`,
  ADD COLUMN `original_name` VARCHAR(220) DEFAULT NULL AFTER `title`,
  ADD COLUMN `mime_type` VARCHAR(100) DEFAULT NULL AFTER `file_type`,
  ADD COLUMN `uploaded_by` INT UNSIGNED DEFAULT NULL AFTER `folder`,
  ADD COLUMN `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

UPDATE `media` SET `mime_type` = `file_type` WHERE `mime_type` IS NULL;

CREATE TABLE IF NOT EXISTS `media_usage` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `media_id` INT UNSIGNED NOT NULL,
  `entity_type` VARCHAR(60) NOT NULL,
  `entity_id` INT UNSIGNED NOT NULL,
  `field_name` VARCHAR(80) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_media` (`media_id`),
  INDEX `idx_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_key` VARCHAR(60) NOT NULL,
  `section_key` VARCHAR(80) NOT NULL,
  `title` VARCHAR(220) DEFAULT NULL,
  `subtitle` VARCHAR(300) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `data` JSON DEFAULT NULL,
  `image_url` VARCHAR(400) DEFAULT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_page_section` (`page_key`, `section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(220) NOT NULL,
  `slug` VARCHAR(240) NOT NULL UNIQUE,
  `excerpt` VARCHAR(400) DEFAULT NULL,
  `content` MEDIUMTEXT DEFAULT NULL,
  `featured_image` VARCHAR(400) DEFAULT NULL,
  `author_id` INT UNSIGNED DEFAULT NULL,
  `meta_title` VARCHAR(200) DEFAULT NULL,
  `meta_description` VARCHAR(400) DEFAULT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 0,
  `published_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
