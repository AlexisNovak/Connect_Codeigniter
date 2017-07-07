

ALTER TABLE `couriers` ADD `compliance_rating` INT NULL AFTER `rating`;


TRUNCATE TABLE `compliance_ratings`;
INSERT INTO `compliance_ratings` (`id`, `display_name`, `label_class`) VALUES

(1, 'Full Compliance', 'full_c'),

(2, 'Acceptable Compliance', 'acc_c'),

(3, 'Non-compliance', 'non_c'),

(4, 'Not Assess', 'not_c');



ALTER TABLE `member` ADD `oldpass1` VARCHAR(255) NULL , ADD `oldpass2` VARCHAR(255) NULL;



ALTER TABLE `couriers` ADD `oldpass1` VARCHAR(255) NULL , ADD `oldpass2` VARCHAR(255) NULL ;