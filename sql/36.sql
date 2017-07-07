ALTER TABLE `courier_rating` DROP PRIMARY KEY;

ALTER TABLE `courier_rating` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`) ;

ALTER TABLE `courier_rating` ADD `service_id` INT NOT NULL AFTER`user_id`;

ALTER TABLE `courier_rating` ADD `status` BOOLEAN NOT NULL DEFAULT TRUE AFTER `review`;