ALTER TABLE `consignments` ADD `collection_company_name` VARCHAR(100) NOT NULL AFTER `collection_contact_email`;

ALTER TABLE `consignments` ADD `delivery_company_name` VARCHAR(100) NOT NULL AFTER `delivery_contact_phone`;

ALTER TABLE `couriers` ADD `insured_amount` FLOAT NOT NULL AFTER `support_email`;

ALTER TABLE `organizations` ADD `use_public_service` TINYINT(1) NOT NULL COMMENT '1=enabled 0=disabled' AFTER `public_tracking`;