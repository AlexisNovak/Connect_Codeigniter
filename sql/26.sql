
ALTER TABLE `courier_service` ADD `is_archived` TINYINT(1) NOT NULL , ADD `archived_on` DATETIME NOT NULL ;

ALTER TABLE `service_requests` ADD `is_open_bid` TINYINT(1) NOT NULL COMMENT '0=no 1=yes' AFTER `other_conditions`;

INSERT INTO `consignment_status` (`status_id`, `display_name`, `courier_display_name`) VALUES ('11', 'Cancelled', 'Cancelled');