USE `highqhomes`;

DELETE FROM `menus` WHERE `url` = '/blog' OR LOWER(`label`) = 'blog';
DELETE FROM `seo` WHERE `page_slug` = 'blog';

UPDATE `roles` SET `permissions` = REPLACE(`permissions`, ',"blog.manage"', '') WHERE `permissions` LIKE '%blog.manage%';
UPDATE `roles` SET `permissions` = REPLACE(`permissions`, '"blog.manage",', '') WHERE `permissions` LIKE '%blog.manage%';
UPDATE `roles` SET `permissions` = REPLACE(`permissions`, '"blog.manage"', '') WHERE `permissions` LIKE '%blog.manage%';

DROP TABLE IF EXISTS `blog_posts`;
