ALTER TABLE `consignments` CHANGE `c_group_id` `c_group_id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `consignment_status` CHANGE `display_name` `display_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO `consignment_type`(`display_name`, `description`) VALUES ('Cheque Collect & Deposit','Cheques will be collected and deposit to your designated bank. Please remember to write the deposit account on the cheque beforehand.'),('VISA Processing','Send VISA to immigration office for processing and bring back.'),('2-Way Delivery','Send items to the delivery address and bring it back after. Note that Maximum waiting time is 10-minutes.');

INSERT INTO `consignment_status`(`status_id`, `display_name`, `description`, `for_courier`, `is_deleted`, `deleted_date`) VALUES (311,'In-Transit (2nd Leg)','Delivered to delivery address. Coming back',1,0,'0000-00-000:00:00'),(402,'Delivered (Cheque deposited)','Cheque deposited',1,0,'0000-00-000:00:00'),(321,'In-Transit (Processed)','VISA Processed. Coming back.',1,0,'0000-00-000:00:00');

UPDATE `consignment_status` SET `display_name` = 'Accepted' WHERE `consignment_status`.`status_id` = 1;
UPDATE `consignment_status` SET `display_name` = 'Courier Informed' WHERE `consignment_status`.`status_id` = 8;
UPDATE `consignment_status` SET `display_name` = 'Getting Quotes' WHERE `consignment_status`.`status_id` = 9;

ALTER TABLE `organizations` ADD `public_tracking` TINYINT(1) NOT NULL COMMENT '1=enabled 0=disabled' AFTER `open_bid`;