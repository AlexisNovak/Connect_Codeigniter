
ALTER TABLE `couriers` ADD `compliance_id` INT NULL AFTER `rating`;

CREATE TABLE IF NOT EXISTS `compliance_ratings` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `display_name` varchar(150) NOT NULL,
  `label_class` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `compliance_ratings` (`id`, `display_name`, `label_class`) VALUES
(1, 'Full Compliance', 'custom-label-green'),
(2, 'Acceptable Compliance', 'custom-label-yellow'),
(3, 'Non-compliance', 'custom-label-red'),
(4, 'Not Assess', 'custom-label-blue');

ALTER TABLE `member` ADD `oldpass1` VARCHAR(255) NULL , ADD `oldpass2` VARCHAR(255) NULL;

ALTER TABLE `couriers` ADD `oldpass1` VARCHAR(255) NULL , ADD `oldpass2` VARCHAR(255) NULL ;