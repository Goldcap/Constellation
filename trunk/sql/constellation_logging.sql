CREATE TABLE `xhr_selenium_log` (
  `xhr_selenium_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `xhr_selenium_log_action` varchar(255) DEFAULT NULL,
  `xhr_selenium_log_result` varchar(255) DEFAULT NULL,
  `xhr_selenium_log_browser` varchar(255) DEFAULT NULL,
  `xhr_selenium_log_date` datetime DEFAULT NULL,
  `xhr_selenium_log_group` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`xhr_selenium_log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

