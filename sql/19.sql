CREATE TABLE IF NOT EXISTS `service_parcel_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `type` smallint(11) NOT NULL,
  `price` float NOT NULL,
  `max_volume` float DEFAULT NULL,
  `volume_cost` float DEFAULT NULL,
  `max_weight` float DEFAULT NULL,
  `weight_cost` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_parcel` (`service_id`,`type`),
  KEY `service_id` (`service_id`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

ALTER TABLE `courier_service` ADD `derived_from` INT NOT NULL DEFAULT '0';