USE `highqhomes`;

INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `label`, `group_name`, `sort_order`) VALUES
('hero_carousel_autoplay', '1', 'boolean', 'Hero Auto-play Slides', 'homepage', 14),
('hero_carousel_interval', '6', 'number', 'Hero Slide Interval (seconds)', 'homepage', 15),
('hero_carousel_dots', '1', 'boolean', 'Hero Show Slide Dots', 'homepage', 16),
('hero_carousel_pause_hover', '1', 'boolean', 'Hero Pause on Hover', 'homepage', 17);

ALTER TABLE `sliders`
  ADD COLUMN IF NOT EXISTS `show_description` TINYINT(1) NOT NULL DEFAULT 1 AFTER `text_align`,
  ADD COLUMN IF NOT EXISTS `image_focus` VARCHAR(20) NOT NULL DEFAULT 'center' AFTER `show_description`,
  ADD COLUMN IF NOT EXISTS `content_style` VARCHAR(20) NOT NULL DEFAULT 'standard' AFTER `image_focus`,
  ADD COLUMN IF NOT EXISTS `badge_text` VARCHAR(80) DEFAULT NULL AFTER `content_style`;
