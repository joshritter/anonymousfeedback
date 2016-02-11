-- MySQL dump 10.11
--
-- Host: mysql.anonymousfeedback.net    Database: anonfeed
-- ------------------------------------------------------
-- Server version	5.1.39-log

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
-- Table structure for table `admin`
--
use anonfeed;
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminUser` varchar(200) NOT NULL DEFAULT '',
  `adminPassword` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'anon','4n0nym0u5'),(2,'admin','admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banIP`
--

DROP TABLE IF EXISTS `banIP`;
CREATE TABLE `banIP` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=556 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banIP`
--

LOCK TABLES `banIP` WRITE;
/*!40000 ALTER TABLE `banIP` DISABLE KEYS */;
INSERT INTO `banIP` VALUES (104,'97.87.139.123'),(102,'190.4.139.220'),(100,'66.194.98.54'),(98,'76.87.255.135'),(96,'200.198.98.212'),(95,'200.198.98.212'),(94,'205.211.96.100'),(337,'121.210.132.234'),(105,'193.53.87.75'),(336,'121.210.132.234'),(107,'194.8.75.212'),(335,'121.210.132.234'),(109,'67.166.73.124'),(334,'121.210.132.253'),(333,'203.20.33.97'),(112,'86.31.101.163'),(332,'203.20.33.97'),(331,'203.20.33.97'),(118,'76.102.161.49'),(330,'121.210.132.253'),(200,'194.8.75.44'),(201,'208.53.138.150'),(202,'86.10.210.158'),(203,'64.74.153.189'),(204,'38.98.245.50'),(205,'70.84.243.130'),(206,'72.47.224.16'),(207,'208.53.138.150'),(208,'86.10.210.158'),(209,'38.98.245.50'),(210,'72.71.241.83'),(329,'121.210.132.253'),(300,'194.8.75.247'),(301,'194.8.75.147'),(302,'194.8.75.239'),(303,'194.8.74.10'),(304,'194.8.75.245'),(305,'194.8.75.214'),(306,'82.38.192.57'),(328,'121.210.132.253'),(308,'71.56.126.124'),(327,'207.112.50.192'),(310,'69.64.43.216'),(326,'207.112.50.192'),(325,'207.112.50.192'),(321,'65.183.184.230'),(315,'86.51.91.195'),(324,'99.225.148.153'),(317,'86.51.91.195'),(323,'99.225.148.153'),(322,'65.183.184.230'),(320,'41.207.66.113'),(555,'76.126.156.158');
/*!40000 ALTER TABLE `banIP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toEmail` varchar(200) NOT NULL DEFAULT '',
  `toName` varchar(200) NOT NULL DEFAULT '',
  `fromEmail` varchar(200) NOT NULL DEFAULT '',
  `convo` longtext NOT NULL,
  `convoID` varchar(200) NOT NULL DEFAULT '',
  `time` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(200) NOT NULL DEFAULT '',
  `sendStyle` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28660 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom`
--

DROP TABLE IF EXISTS `custom`;
CREATE TABLE `custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `recieverName` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `recieverNamePublic` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `receiverEmail` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `receiverEmailPublic` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `theCompany` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `orgPublic` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `message` longtext COLLATE latin1_general_ci NOT NULL,
  `formName` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `customID` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `custom`
--

LOCK TABLES `custom` WRITE;
/*!40000 ALTER TABLE `custom` DISABLE KEYS */;
INSERT INTO `custom` VALUES (1,'','David Brown','yes','dpbhere@gmail.com','yes','','yes','This is test form 1','','93346742-0','69.91.199.154'),(2,'dpb','David Brown','no','dpbhere@gmail.com','no','Scoloncs','no','This is test form 2','','92811024-1','69.91.199.154'),(3,'dpb','David Brown','no','dpbhere@gmail.com','no','Scoloncs','no','This is test form 3','Test3','55700303-2','69.91.199.154');
/*!40000 ALTER TABLE `custom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examples`
--

DROP TABLE IF EXISTS `examples`;
CREATE TABLE `examples` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `example` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `examples`
--

LOCK TABLES `examples` WRITE;
/*!40000 ALTER TABLE `examples` DISABLE KEYS */;
INSERT INTO `examples` VALUES (1,'Let your boss know they are not the God they think they are.'),(2,'Tell your crush that you think they are something special.'),(3,'Explain to your teacher that they give too much homework.'),(4,'Give your friend a clue about the perfume they wear.'),(5,'Tell someone they talk too loud.'),(6,'Tell a person you liked what they wore today.'),(7,'Explain that mullets are no longer in style.'),(8,'Inform your friend their 1984 Camaro is not as cool as they think.'),(9,'Tell the cable company how you really fee'),(10,'Let your neighbor know you can hear their music at 3am'),(11,'Inform your friend that his girlfriend is sleeping with his brother'),(12,'Inform a retail store they need better customer service'),(13,'Tell your classmate that you have a crush on them.'),(14,'Inform your professor that they grade to hard.'),(15,'Let your neighbor know that you know their secret.'),(16,'Let your crush know how awesome you think they are.'),(17,'Become a secret admirer.'),(18,'Tell your aunt that her potato salad needs a bit more salt.'),(19,'Be a whistle blower.'),(20,'Do not let that bully push you around anymore.'),(21,'Tell your friend who dented their car.'),(22,'Re-kindle a lost love.'),(23,'Give the honest feedback you really want to give.'),(24,'Give a suggestion that will change the world.'),(25,'Tell someone their husband is cheating on them.');
/*!40000 ALTER TABLE `examples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userInfo`
--

DROP TABLE IF EXISTS `userInfo`;
CREATE TABLE `userInfo` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `userName` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `userRealName` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `userPassword` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `userEmail` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `userAccess` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `userPhone` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `theCompany` varchar(250) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `userInfo`
--

LOCK TABLES `userInfo` WRITE;
/*!40000 ALTER TABLE `userInfo` DISABLE KEYS */;
INSERT INTO `userInfo` VALUES (5,'dpb','David Brown','scanme','dpbhere@gmail.com','user','206-724-9602','Scoloncs');
/*!40000 ALTER TABLE `userInfo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-05-21 22:00:49
