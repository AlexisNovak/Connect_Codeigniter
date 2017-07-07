ALTER TABLE `member` CHANGE `createdon` `createdon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `courier_service` ADD `load_limit` INT NULL AFTER `payments`;
ALTER TABLE `member` CHANGE `lastsignedinon` `lastsignedinon` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
