-- Centralized CMS administration and role hardening.
-- Safe to run more than once.

ALTER TABLE `users`
  MODIFY `role` VARCHAR(80) NOT NULL DEFAULT 'editor';

INSERT INTO `roles` (`name`, `label`, `permissions`) VALUES
('super_admin', 'Super Administrator', '["*"]'),
('admin', 'Administrator', '["content.view","content.manage","media.manage","messages.view","messages.manage","settings.manage","menus.manage","seo.manage","users.manage","logs.view","security.manage","sections.manage"]'),
('editor', 'Content Editor', '["content.view","content.manage","media.manage","messages.view","sections.manage"]'),
('viewer', 'Read Only', '["content.view","messages.view"]')
ON DUPLICATE KEY UPDATE
  `label` = VALUES(`label`);

UPDATE `users`
SET `role` = 'super_admin'
WHERE `email` = 'admin@highqhomes.net'
  AND `role` = 'admin';
