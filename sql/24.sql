ALTER TABLE `mailqueue` ADD `user_type` TINYINT(2) NOT NULL COMMENT '1=customer 2=courier' AFTER `attachment`;
ALTER TABLE `couriers` ADD `last_msg_time` DATETIME NULL ;