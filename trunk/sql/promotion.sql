/*
Navicat MySQL Data Transfer

Source Server         : Fedora
Source Server Version : 50147
Source Host           : 192.168.2.107:3306
Source Database       : constellation_dev

Target Server Type    : MYSQL
Target Server Version : 50147
File Encoding         : 65001

Date: 2011-01-03 13:25:44
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `promotion`
-- ----------------------------
DROP TABLE IF EXISTS `promotion`;
CREATE TABLE `promotion` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_html` text,
  `promotion_start_date` datetime DEFAULT NULL,
  `promotion_end_date` datetime DEFAULT NULL,
  `promotion_duration` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`promotion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of promotion
-- ----------------------------
INSERT INTO `promotion` VALUES ('1', 'Some Pig! He Rocks!', '2011-01-03 09:55:42', '2011-01-03 09:55:45', '5');
INSERT INTO `promotion` VALUES ('2', '<img src=\"/images/banners/460x60judyseashell.jpg\" width=\"480\" height=\"60\" />', '2011-01-03 12:34:33', '2011-01-03 12:34:36', '10');
