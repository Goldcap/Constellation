/*
Navicat MySQL Data Transfer

Source Server         : Fedora
Source Server Version : 50147
Source Host           : 192.168.2.107:3306
Source Database       : constellation_dev

Target Server Type    : MYSQL
Target Server Version : 50147
File Encoding         : 65001

Date: 2011-01-11 15:24:19
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `subscription`
-- ----------------------------
DROP TABLE IF EXISTS `subscription`;
CREATE TABLE `subscription` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(11) DEFAULT NULL,
  `fk_payment_id` int(11) DEFAULT NULL,
  `fk_payment_status` int(11) DEFAULT NULL,
  `subscription_unique_key` varchar(20) DEFAULT NULL,
  `subscription_date_added` datetime DEFAULT NULL,
  `subscription_type` varchar(0) DEFAULT NULL,
  `subscription_number` int(11) DEFAULT NULL,
  `subscription_term` varchar(20) DEFAULT NULL,
  `subscription_period` int(11) DEFAULT NULL,
  `subscription_ticket_price` float DEFAULT NULL,
  `subscription_total_price` float DEFAULT NULL,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of subscription
-- ----------------------------
INSERT INTO `subscription` VALUES ('1', '2339', '1209', null, null, '2011-01-11 14:00:43', null, null, null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('2', '2339', '1210', null, null, '2011-01-11 14:01:28', null, null, null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('3', '2339', '1211', null, null, '2011-01-11 14:02:29', null, null, null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('4', '2339', '1212', null, null, '2011-01-11 14:07:13', '', '20', null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('5', '2339', '1213', null, 'XcKIzI2JgQ', '2011-01-11 14:11:42', '', '20', null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('6', '2339', '1214', null, 'kjgGiCcGis', '2011-01-11 14:14:08', '', '20', null, null, '2.99', null);
INSERT INTO `subscription` VALUES ('7', '2339', '1215', null, 'M72eaC3Rts', '2011-01-11 14:15:53', '', '20', null, null, '2.99', null);
