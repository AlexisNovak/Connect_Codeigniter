ALTER TABLE `member` DROP `oldpass1`, DROP `oldpass2`;
ALTER TABLE `member` ADD `oldpasswords` VARCHAR(150) NULL ;
ALTER TABLE `couriers` DROP `oldpass1`, DROP `oldpass2`;
ALTER TABLE `couriers` ADD `oldpasswords` VARCHAR(150) NULL ;
ALTER TABLE `organizations` CHANGE `open_bid` `open_bid` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '0=closed bid 1=open bid';

CREATE TABLE IF NOT EXISTS `price_range` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `consignment_type` int(11) NOT NULL,
  `min_length` int(11) NOT NULL,
  `max_length` int(11) NOT NULL,
  `min_breadth` int(11) NOT NULL,
  `max_breadth` int(11) NOT NULL,
  `min_height` int(11) NOT NULL,
  `max_height` int(11) NOT NULL,
  `min_weight` int(11) NOT NULL,
  `max_weight` int(11) NOT NULL,
  `price` float NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `surcharge_items` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	`item_name` varchar(50) NOT NULL,
	`unit_price` float NOT NULL,
	`remarks` text NOT NULL,
	`service_id` int(11) NOT NULL,
	`added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `courier_service` ADD `payment_terms` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `destination`;
ALTER TABLE `courier_service` ADD `limit_use` TINYINT(1) NOT NULL COMMENT '0=not limited 1=limited' AFTER `threshold_price`;
ALTER TABLE `courier_service` ADD `is_new` TINYINT(1) NOT NULL COMMENT '1=new, 0=used' ;