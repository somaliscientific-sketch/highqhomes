-- Hero Slider CMS upgrade: mobile images, scheduling, transitions, timestamps.
-- Safe to run more than once (PHP apply script is the preferred runner).

INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES
('hero_carousel_transition', 'kenburns', 'text', 'Hero Slide Transition', 'homepage', 18);

ALTER TABLE `sliders`
  ADD COLUMN IF NOT EXISTS `mobile_image` VARCHAR(400) DEFAULT NULL AFTER `image`,
  ADD COLUMN IF NOT EXISTS `autoplay_duration` INT UNSIGNED DEFAULT NULL AFTER `badge_text`,
  ADD COLUMN IF NOT EXISTS `transition_type` VARCHAR(20) NOT NULL DEFAULT 'inherit' AFTER `autoplay_duration`,
  ADD COLUMN IF NOT EXISTS `start_date` DATETIME DEFAULT NULL AFTER `transition_type`,
  ADD COLUMN IF NOT EXISTS `end_date` DATETIME DEFAULT NULL AFTER `start_date`,
  ADD COLUMN IF NOT EXISTS `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;
