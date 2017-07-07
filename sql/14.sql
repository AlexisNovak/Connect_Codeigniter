CREATE TABLE invoices (
invoice_id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  invoice_no varchar(20) NOT NULL,
  courier_id int(10) unsigned NOT NULL,
  customer_id int(11) NOT NULL,
  created_date datetime NOT NULL,
  is_deleted tinyint(1) NOT NULL DEFAULT '0',
  deleted_date datetime DEFAULT NULL,
  deleted_user_id int(10) unsigned DEFAULT NULL,
  `type` int(10) unsigned NOT NULL,
  from_date datetime NOT NULL,
  to_date datetime NOT NULL,
  file_name varchar(100) NOT NULL,
  pdf tinyint(1) NOT NULL DEFAULT '0',
  excel tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;