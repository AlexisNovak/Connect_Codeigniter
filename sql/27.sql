
ALTER TABLE `consignments` ADD `cancel_request` TINYINT(1) NOT NULL COMMENT '0=no 1=yes 2=accepted' , ADD `requested_on` DATETIME NOT NULL ;