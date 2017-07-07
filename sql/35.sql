ALTER TABLE `courier_service` ADD `delivery_time` VARCHAR(20) NOT NULL DEFAULT '90-minute' AFTER `end_time`;

CREATE TABLE IF NOT EXISTS `consignment_attachments` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `path` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;