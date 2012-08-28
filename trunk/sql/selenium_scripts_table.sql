'CREATE TABLE `auto_test_scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manager` varchar(45) NOT NULL,
  `group` varchar(45) NOT NULL,
  `type` varchar(145) NOT NULL,
  `locator` varchar(145) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1';
