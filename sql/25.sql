CREATE TABLE IF NOT EXISTS `tender_files` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `tender_id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;