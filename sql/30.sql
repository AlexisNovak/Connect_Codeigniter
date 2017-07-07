ALTER TABLE `member_details` ADD `payments` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0001' AFTER `description`;

ALTER TABLE `organizations` ADD `payments` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0001' AFTER `status`;

ALTER TABLE `consignments` ADD `payment_mode` INT NOT NULL AFTER `remarks`;