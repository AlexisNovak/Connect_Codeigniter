ALTER TABLE `couriers` ADD `is_invited` TINYINT(1) NOT NULL COMMENT '0=no 1=yes' ;

ALTER TABLE `couriers` CHANGE `access_key` `access_key` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;