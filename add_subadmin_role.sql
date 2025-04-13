-- Add the sub_admin role
INSERT INTO `roles` (`code`, `icon`, `name`, `description`)
VALUES ('sub_admin', 'user', 'Sub Administrator', 'Sub Administrator has limited privileges compared to admin, can only manage specific subscriptions and users.');

-- Get the ID of the newly inserted role
SET @sub_admin_role_id = LAST_INSERT_ID();

-- Copy permissions from staff role (ID: 2) but with restrictions
-- First, get all privileges
INSERT INTO `role_privileges` (`rid`, `pid`, `active`)
SELECT @sub_admin_role_id, pid, active
FROM `role_privileges`
WHERE rid = 2; -- Copy from staff role

-- Now restrict specific privileges 
-- Disable manage_backup privilege
UPDATE `role_privileges` SET `active` = 0
WHERE rid = @sub_admin_role_id AND pid = (SELECT id FROM `privileges` WHERE code = 'manage_backup');

-- Disable system management
UPDATE `role_privileges` SET `active` = 0
WHERE rid = @sub_admin_role_id AND pid = (SELECT id FROM `privileges` WHERE code = 'manage_languages');

-- Add the new user type to allow role assignment
ALTER TABLE `users` MODIFY COLUMN `type` ENUM('owner', 'staff', 'editor', 'member', 'sub_admin') NOT NULL DEFAULT 'member';

-- Add created_by column to users table to track which sub-admin created a user
ALTER TABLE `users` ADD COLUMN `created_by` INT UNSIGNED NULL DEFAULT NULL AFTER `created`;
ALTER TABLE `users` ADD INDEX `idx_created_by` (`created_by`);

-- Add created_by column to memberships table to track which sub-admin created a membership
ALTER TABLE `memberships` ADD COLUMN `created_by` INT UNSIGNED NULL DEFAULT NULL AFTER `created`;
ALTER TABLE `memberships` ADD INDEX `idx_membership_created_by` (`created_by`);