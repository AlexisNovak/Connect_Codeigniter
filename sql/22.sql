CREATE TABLE `mailqueue` (
`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `to` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '1=new 2=send',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;