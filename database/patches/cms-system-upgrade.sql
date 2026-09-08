-- CMS system upgrade: admin logs, security settings, permissions

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

INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES
('security_max_attempts', '5', 'number', 'Max login attempts', 'security', 1),
('security_lockout_minutes', '15', 'number', 'Lockout duration (minutes)', 'security', 2),
('security_session_hours', '2', 'number', 'Session idle timeout (hours)', 'security', 3),
('security_audit_retention_days', '90', 'number', 'Keep audit logs (days)', 'security', 4),
('maintenance_mode', '0', 'boolean', 'Maintenance mode', 'security', 5);

UPDATE `roles` SET `permissions` = JSON_ARRAY_APPEND(
  `permissions`, '$', 'logs.view'
) WHERE `name` = 'admin' AND JSON_SEARCH(`permissions`, 'one', 'logs.view') IS NULL;

UPDATE `roles` SET `permissions` = JSON_ARRAY_APPEND(
  `permissions`, '$', 'security.manage'
) WHERE `name` = 'admin' AND JSON_SEARCH(`permissions`, 'one', 'security.manage') IS NULL;
