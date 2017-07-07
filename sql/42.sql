CREATE TABLE IF NOT EXISTS `surcharge_types` (
`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `surcharge_types` (`id`, `name`, `description`) VALUES
(1, 'Other Restricted Areas', ''),
(2, 'Sentosa', ''),
(4, 'CBD', ''),
(8, 'Tuas ', ''),
(16, 'Collect and deliver on the same day ', ''),
(17, 'Collect the day before delivery', ''),
(18, 'Collect back on next business day', ''),
(19, 'Collect back on same day as delivery', ''),
(20, 'Collect back within a week', ''),
(25, 'By Appointment', '');