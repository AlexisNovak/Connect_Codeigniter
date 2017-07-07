ALTER TABLE `member_details` CHANGE `payments` `payments` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0011';

ALTER TABLE `organizations` CHANGE `payments` `payments` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0011';