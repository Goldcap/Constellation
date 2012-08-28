-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: constellation
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.7

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'awalkinmyshoes','awalkinmyshoes@constellation.tv','password','http://awalkinmyshoes.com');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `viewer_id` int(10) unsigned NOT NULL,
  `comment` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `viewer_id_idx` (`viewer_id`),
  CONSTRAINT `comment_viewer_id_viewer_id` FOREIGN KEY (`viewer_id`) REFERENCES `viewer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (13,107,'Thoughts','2010-11-20 16:20:31','2010-11-20 16:20:31'),(14,108,'This is amazing.','2010-11-20 21:09:57','2010-11-20 21:09:57'),(15,108,'still amazing','2010-11-20 21:15:58','2010-11-20 21:15:58'),(16,108,'','2010-11-21 09:04:29','2010-11-21 09:04:29'),(17,108,'testing\r\n','2010-11-21 09:04:36','2010-11-21 09:04:36'),(18,108,NULL,'2010-11-22 10:11:52','2010-11-22 10:11:52'),(19,108,'Testing','2010-11-22 10:33:12','2010-11-22 10:33:12'),(20,108,'AWESOME MOVIE :)','2010-11-22 11:46:58','2010-11-22 11:46:58'),(21,108,'ksdgjk','2010-11-22 11:47:59','2010-11-22 11:47:59'),(22,111,'','2010-11-22 13:28:54','2010-11-22 13:28:54'),(23,108,'Testing','2010-11-24 08:51:19','2010-11-24 08:51:19');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_version`
--

DROP TABLE IF EXISTS `migration_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_version` (
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_version`
--

LOCK TABLES `migration_version` WRITE;
/*!40000 ALTER TABLE `migration_version` DISABLE KEYS */;
INSERT INTO `migration_version` VALUES (12);
/*!40000 ALTER TABLE `migration_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movie`
--

DROP TABLE IF EXISTS `movie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` text,
  `password` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movie_client_id_idx` (`client_id`),
  CONSTRAINT `movie_client_id_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movie`
--

LOCK TABLES `movie` WRITE;
/*!40000 ALTER TABLE `movie` DISABLE KEYS */;
INSERT INTO `movie` VALUES (1,'a walk in my shoes','<object width=\"640\" height=\"359\">\n<param name=\"movie\" value=\"http://jnjit.origindigital.com/com/flyover/bin-debug/FlyoverPlayer.swf?userToken=ABC145E7F73DA2ADCF786C41220ADEF04CBCCFB46B33AD2E075F6DC76F52C784AD43419D20BA8EC4DDEFDEFFBBC9388E4962511E7AB3BAD4F77A80500DBE13A2&configURL=http://jnjit.origindigital.com/FlyoverInitv1.2.xml&cssURL=http://jnjit.origindigital.com/com/flyover/bin-debug/com/CSS/Schematics.swf&width=640&height=359&showTrac=false&isAuth=True&mode=QS&resizeButt=false&assetPath=http://jnjit.origindigital.com/com/flyover/bin-debug/com/&CID=6254467&autoPlay=false&qaMonitor=false&menuBackDoor=false&toggleBackDoor=true\"></param>                                                                                                                                                                                                                                                                                                                                               <param name=\"allowscriptaccess\" value=\"always\"></param>\n<param NAME=wmode VALUE=transparent/></param>\n\n<embed src=\"http://jnjit.origindigital.com/com/flyover/bin-debug/FlyoverPlayer.swf?userToken=ABC145E7F73DA2ADCF786C41220ADEF04CBCCFB46B33AD2E075F6DC76F52C784AD43419D20BA8EC4DDEFDEFFBBC9388E4962511E7AB3BAD4F77A80500DBE13A2&configURL=http://jnjit.origindigital.com/FlyoverInitv1.2.xml&cssURL=http://jnjit.origindigital.com/com/flyover/bin-debug/com/CSS/Schematics.swf&width=640&height=359&showTrac=false&isAuth=True&mode=QS&resizeButt=false&assetPath=http://jnjit.origindigital.com/com/flyover/bin-debug/com/&CID=6254467&autoPlay=false&qaMonitor=false&menuBackDoor=false&toggleBackDoor=true\" wmode=\"transparent\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"640\" height=\"359\"></embed>\n</object>','2010-11-17 22:59:34','2010-11-17 22:59:34','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `movie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `active` tinyint(3) unsigned DEFAULT '1',
  `client_id` int(10) unsigned NOT NULL,
  `viewer_id` int(10) unsigned DEFAULT NULL,
  `uses` int(10) unsigned NOT NULL,
  `total_uses` int(10) unsigned NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ActiveClientToken_idx` (`client_id`,`token`,`active`),
  KEY `client_id_idx` (`client_id`),
  KEY `viewer_id_idx` (`viewer_id`),
  CONSTRAINT `token_client_id_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE,
  CONSTRAINT `token_viewer_id_viewer_id` FOREIGN KEY (`viewer_id`) REFERENCES `viewer` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` VALUES (82,'c7d0-36604',1,1,37,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(83,'c7d0-32175',1,1,38,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(84,'c7d0-26104',1,1,39,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(85,'c7d0-47184',1,1,40,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(86,'c7d0-10279',1,1,41,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(87,'c7d0-22650',1,1,42,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(88,'c7d0-74277',1,1,43,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(89,'c7d0-47025',1,1,44,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(90,'c7d0-39603',1,1,45,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(91,'c7d0-45952',1,1,46,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(92,'c7d0-36631',1,1,47,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(93,'c7d0-95145',1,1,48,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(94,'c7d0-19411',1,1,49,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(95,'c7d0-57726',1,1,50,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(96,'c7d0-95947',1,1,51,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(97,'c7d0-94430',1,1,52,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(98,'c7d0-10435',1,1,53,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(99,'c7d0-86270',1,1,54,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(100,'c7d0-18420',1,1,55,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(101,'c7d0-75154',1,1,56,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(102,'c7d0-26606',1,1,57,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(103,'c7d0-95353',1,1,58,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(104,'c7d0-95973',1,1,59,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(105,'c7d0-72389',1,1,60,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(106,'c7d0-50715',1,1,61,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(107,'c7d0-29568',1,1,62,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(108,'c7d0-71549',1,1,63,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(109,'c7d0-55231',1,1,64,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(110,'c7d0-37415',1,1,65,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(111,'c7d0-72760',1,1,66,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(112,'c7d0-93202',1,1,67,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(113,'c7d0-64020',1,1,68,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(114,'c7d0-94936',1,1,69,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(115,'c7d0-19306',1,1,70,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(116,'c7d0-11204',1,1,71,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(117,'c7d0-95216',1,1,72,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(118,'c7d0-31957',1,1,73,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(119,'c7d0-75482',1,1,74,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(120,'c7d0-42241',1,1,75,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(121,'c7d0-61560',1,1,76,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(122,'c7d0-21434',1,1,77,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(123,'c7d0-68873',1,1,78,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(124,'c7d0-56706',1,1,79,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(125,'c7d0-30846',1,1,80,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(126,'c7d0-26600',1,1,81,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(127,'c7d0-52653',1,1,82,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(128,'c7d0-25276',1,1,83,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(129,'c7d0-27035',1,1,84,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(130,'c7d0-38923',1,1,85,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(131,'c7d0-33697',1,1,86,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(132,'c7d0-92189',1,1,87,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(133,'c7d0-55530',1,1,88,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(134,'c7d0-29050',1,1,89,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(135,'c7d0-88163',1,1,90,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(136,'c7d0-27920',1,1,91,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(137,'c7d0-69766',1,1,92,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(138,'c7d0-17731',1,1,93,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(139,'c7d0-89469',1,1,94,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(140,'c7d0-24997',1,1,95,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(141,'c7d0-45147',1,1,96,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(142,'c7d0-62230',1,1,97,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(143,'c7d0-18200',1,1,98,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(144,'c7d0-99167',1,1,99,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(145,'c7d0-57166',1,1,100,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(146,'c7d0-27506',1,1,101,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(147,'c7d0-10372',1,1,102,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(148,'c7d0-52382',1,1,103,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(149,'c7d0-49463',1,1,104,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(150,'c7d0-75854',1,1,105,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(151,'c7d0-84624',1,1,106,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(152,'c7d0-11024',1,1,107,1,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 15:51:25'),(153,'9f4f-87289',1,1,108,14,100,'2010-11-20 16:20:00','2010-12-03 00:00:00','2010-11-20 16:22:06'),(154,'78cf-40021',1,1,109,0,1,'2010-11-23 12:00:00','2010-12-03 00:00:00','2010-11-20 21:14:10'),(155,'6ff3-21129',1,1,110,1,1,'2010-11-21 13:16:00','2010-12-03 00:00:00','2010-11-21 13:17:51'),(156,'d4b8-66177',1,1,111,1,1,'2010-11-22 13:22:00','2010-12-03 00:00:00','2010-11-22 13:27:45'),(157,'4e81-63337',1,1,112,1,2,'2010-11-23 10:54:00','2010-12-03 00:00:00','2010-11-23 10:55:18'),(158,'bb21-43548',1,1,113,1,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(159,'bb21-19609',1,1,114,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(160,'bb21-32816',1,1,115,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(161,'bb21-72523',1,1,116,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(162,'bb21-19678',1,1,117,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(163,'bb21-40754',1,1,118,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(164,'bb21-84242',1,1,119,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(165,'bb21-70872',1,1,120,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(166,'bb21-40586',1,1,121,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(167,'bb21-90283',1,1,122,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(168,'bb21-48803',1,1,123,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(169,'bb21-84695',1,1,124,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(170,'bb21-43452',1,1,125,0,1,'2010-11-29 16:36:00','2010-12-03 00:00:00','2010-11-29 16:37:12'),(184,'7d6d-52128',1,1,139,2,10,'2010-12-10 11:48:00','2010-12-17 11:48:00','2010-12-10 11:49:18');
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token_bucket`
--

DROP TABLE IF EXISTS `token_bucket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token_bucket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `movie_id` int(10) unsigned NOT NULL,
  `token_id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movie_id_idx` (`movie_id`),
  KEY `token_id_idx` (`token_id`),
  KEY `client_id_idx` (`client_id`),
  CONSTRAINT `token_bucket_client_id_client_id` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE,
  CONSTRAINT `token_bucket_movie_id_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  CONSTRAINT `token_bucket_token_id_token_id` FOREIGN KEY (`token_id`) REFERENCES `token` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token_bucket`
--

LOCK TABLES `token_bucket` WRITE;
/*!40000 ALTER TABLE `token_bucket` DISABLE KEYS */;
INSERT INTO `token_bucket` VALUES (78,1,82,1),(79,1,83,1),(80,1,84,1),(81,1,85,1),(82,1,86,1),(83,1,87,1),(84,1,88,1),(85,1,89,1),(86,1,90,1),(87,1,91,1),(88,1,92,1),(89,1,93,1),(90,1,94,1),(91,1,95,1),(92,1,96,1),(93,1,97,1),(94,1,98,1),(95,1,99,1),(96,1,100,1),(97,1,101,1),(98,1,102,1),(99,1,103,1),(100,1,104,1),(101,1,105,1),(102,1,106,1),(103,1,107,1),(104,1,108,1),(105,1,109,1),(106,1,110,1),(107,1,111,1),(108,1,112,1),(109,1,113,1),(110,1,114,1),(111,1,115,1),(112,1,116,1),(113,1,117,1),(114,1,118,1),(115,1,119,1),(116,1,120,1),(117,1,121,1),(118,1,122,1),(119,1,123,1),(120,1,124,1),(121,1,125,1),(122,1,126,1),(123,1,127,1),(124,1,128,1),(125,1,129,1),(126,1,130,1),(127,1,131,1),(128,1,132,1),(129,1,133,1),(130,1,134,1),(131,1,135,1),(132,1,136,1),(133,1,137,1),(134,1,138,1),(135,1,139,1),(136,1,140,1),(137,1,141,1),(138,1,142,1),(139,1,143,1),(140,1,144,1),(141,1,145,1),(142,1,146,1),(143,1,147,1),(144,1,148,1),(145,1,149,1),(146,1,150,1),(147,1,151,1),(148,1,152,1),(149,1,153,1),(150,1,154,1),(151,1,155,1),(152,1,156,1),(153,1,157,1),(154,1,158,1),(155,1,159,1),(156,1,160,1),(157,1,161,1),(158,1,162,1),(159,1,163,1),(160,1,164,1),(161,1,165,1),(162,1,166,1),(163,1,167,1),(164,1,168,1),(165,1,169,1),(166,1,170,1),(180,1,184,1);
/*!40000 ALTER TABLE `token_bucket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token_history`
--

DROP TABLE IF EXISTS `token_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token_id` int(10) unsigned NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token_id_idx` (`token_id`),
  CONSTRAINT `token_history_token_id_token_id` FOREIGN KEY (`token_id`) REFERENCES `token` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token_history`
--

LOCK TABLES `token_history` WRITE;
/*!40000 ALTER TABLE `token_history` DISABLE KEYS */;
INSERT INTO `token_history` VALUES (62,152,'2010-11-20 16:19:13'),(63,153,'2010-11-20 21:09:43'),(64,153,'2010-11-20 21:15:48'),(65,153,'2010-11-21 09:04:11'),(66,155,'2010-11-21 13:18:05'),(67,153,'2010-11-21 19:12:56'),(68,153,'2010-11-22 10:01:17'),(69,153,'2010-11-22 10:28:58'),(70,153,'2010-11-22 10:32:53'),(71,153,'2010-11-22 11:43:51'),(72,153,'2010-11-22 11:46:27'),(73,156,'2010-11-22 13:28:35'),(74,153,'2010-11-22 15:20:01'),(75,153,'2010-11-22 23:20:49'),(76,153,'2010-11-23 08:42:40'),(77,157,'2010-11-23 12:43:50'),(78,153,'2010-11-24 08:48:47'),(79,117,'2010-11-26 15:38:33'),(80,153,'2010-11-27 08:14:28'),(81,127,'2010-11-28 17:10:22'),(83,106,'2010-11-29 19:20:49'),(84,125,'2010-11-29 21:47:43'),(85,150,'2010-11-30 14:30:31'),(86,133,'2010-11-30 16:05:23'),(87,148,'2010-11-30 16:54:06'),(91,158,'2010-12-01 19:35:08'),(92,138,'2010-12-02 16:08:13'),(93,149,'2010-12-02 16:19:42'),(94,96,'2010-12-02 18:09:57'),(95,130,'2010-12-02 23:59:32'),(97,184,'2010-12-10 11:59:43'),(98,184,'2010-12-10 12:49:06');
/*!40000 ALTER TABLE `token_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viewer`
--

DROP TABLE IF EXISTS `viewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viewer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token_id` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token_id_idx` (`token_id`),
  CONSTRAINT `viewer_token_id_token_id` FOREIGN KEY (`token_id`) REFERENCES `token` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viewer`
--

LOCK TABLES `viewer` WRITE;
/*!40000 ALTER TABLE `viewer` DISABLE KEYS */;
INSERT INTO `viewer` VALUES (37,82,'Anonymous'),(38,83,'Anonymous'),(39,84,'Anonymous'),(40,85,'Anonymous'),(41,86,'Anonymous'),(42,87,'Anonymous'),(43,88,'Anonymous'),(44,89,'Anonymous'),(45,90,'Anonymous'),(46,91,'Anonymous'),(47,92,'Anonymous'),(48,93,'Anonymous'),(49,94,'Anonymous'),(50,95,'Anonymous'),(51,96,'Anonymous'),(52,97,'Anonymous'),(53,98,'Anonymous'),(54,99,'Anonymous'),(55,100,'Anonymous'),(56,101,'Anonymous'),(57,102,'Anonymous'),(58,103,'Anonymous'),(59,104,'Anonymous'),(60,105,'Anonymous'),(61,106,'Anonymous'),(62,107,'Anonymous'),(63,108,'Anonymous'),(64,109,'Anonymous'),(65,110,'Anonymous'),(66,111,'Anonymous'),(67,112,'Anonymous'),(68,113,'Anonymous'),(69,114,'Anonymous'),(70,115,'Anonymous'),(71,116,'Anonymous'),(72,117,'Anonymous'),(73,118,'Anonymous'),(74,119,'Anonymous'),(75,120,'Anonymous'),(76,121,'Anonymous'),(77,122,'Anonymous'),(78,123,'Anonymous'),(79,124,'Anonymous'),(80,125,'Anonymous'),(81,126,'Anonymous'),(82,127,'Anonymous'),(83,128,'Anonymous'),(84,129,'Anonymous'),(85,130,'Anonymous'),(86,131,'Anonymous'),(87,132,'Anonymous'),(88,133,'Anonymous'),(89,134,'Anonymous'),(90,135,'Anonymous'),(91,136,'Anonymous'),(92,137,'Anonymous'),(93,138,'Anonymous'),(94,139,'Anonymous'),(95,140,'Anonymous'),(96,141,'Anonymous'),(97,142,'Anonymous'),(98,143,'Anonymous'),(99,144,'Anonymous'),(100,145,'Anonymous'),(101,146,'Anonymous'),(102,147,'Anonymous'),(103,148,'Anonymous'),(104,149,'Anonymous'),(105,150,'Anonymous'),(106,151,'Anonymous'),(107,152,'Test'),(108,153,'Peggy Carlton'),(109,154,'Anonymous'),(110,155,'Anonymous'),(111,156,'Anonymous'),(112,157,'Anonymous'),(113,158,'Anonymous'),(114,159,'Anonymous'),(115,160,'Anonymous'),(116,161,'Anonymous'),(117,162,'Anonymous'),(118,163,'Anonymous'),(119,164,'Anonymous'),(120,165,'Anonymous'),(121,166,'Anonymous'),(122,167,'Anonymous'),(123,168,'Anonymous'),(124,169,'Anonymous'),(125,170,'Anonymous'),(139,184,'Anonymous');
/*!40000 ALTER TABLE `viewer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-13 18:14:13
