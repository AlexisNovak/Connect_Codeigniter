ALTER TABLE `couriers` CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `organizations` DROP `use_public_service`;

ALTER TABLE `organizations` ADD `use_public_service` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0=no 1=yes' AFTER `public_tracking`;