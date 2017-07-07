ALTER TABLE `consignments` ADD `is_third_party` TINYINT(1) NOT NULL COMMENT '0=no 1=yes' ;
CREATE TABLE `couriers_external` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `permalink` VARCHAR(32) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;