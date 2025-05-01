CREATE TABLE IF NOT EXISTS `salla_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` text NOT NULL,
  `expires_at` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_salla_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `salla_merchants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `store_id` varchar(255) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_url` varchar(255) NOT NULL,
  `store_currency` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_salla_merchants_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `salla_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_id` int(11) NOT NULL,
  `salla_product_id` varchar(255) NOT NULL,
  `salla_order_id` varchar(255) DEFAULT NULL,
  `salla_customer_id` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `membership_id` (`membership_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add Salla API credentials to settings table
ALTER TABLE `settings` 
ADD COLUMN `salla_client_id` varchar(255) DEFAULT NULL,
ADD COLUMN `salla_client_secret` varchar(255) DEFAULT NULL,
ADD COLUMN `salla_webhook_secret` varchar(255) DEFAULT NULL;

-- Add salla_product_id column to memberships table
ALTER TABLE `memberships` 
ADD COLUMN `salla_product_id` varchar(255) DEFAULT NULL,
ADD INDEX `idx_salla_product_id` (`salla_product_id`);

-- Add customer details to salla_subscriptions table
-- This will help in identifying the customer associated with the subscription
ALTER TABLE `salla_subscriptions` 
ADD COLUMN `customer_name` varchar(255) DEFAULT NULL AFTER `salla_customer_id`,
ADD COLUMN `customer_email` varchar(255) DEFAULT NULL AFTER `customer_name`,
ADD COLUMN `customer_phone` varchar(50) DEFAULT NULL AFTER `customer_email`;

-- Alter users table to increase country and custom_fields, and add salla_customer_id
ALTER TABLE `users` 
    MODIFY `country` VARCHAR(50),
    MODIFY `custom_fields` TEXT,
    ADD COLUMN `salla_customer_id` VARCHAR(50) NULL AFTER `custom_fields`;

-- Modify the users table to allow NULL emails
ALTER TABLE `users` MODIFY `email` varchar(60) NULL;