-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: kevinxn_campconnect
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.12.04.1

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
-- Current Database: `kevinxn_campconnect`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `kevinxn_campconnect` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `kevinxn_campconnect`;

--
-- Table structure for table `info`
--

DROP TABLE IF EXISTS `info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `info` (
  `key` varchar(10) NOT NULL,
  `value` varchar(25) NOT NULL,
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info`
--

LOCK TABLES `info` WRITE;
/*!40000 ALTER TABLE `info` DISABLE KEYS */;
INSERT INTO `info` VALUES ('title','Eisner Camp');
/*!40000 ALTER TABLE `info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pictures` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `filename` varchar(32) NOT NULL COMMENT 'md5 hash of img filename (salted?)',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pictures`
--

LOCK TABLES `pictures` WRITE;
/*!40000 ALTER TABLE `pictures` DISABLE KEYS */;
INSERT INTO `pictures` VALUES (1,'archery.jpeg','2007-11-18 00:25:44'),(2,'art.jpeg','2007-11-18 00:25:44'),(3,'artshack.jpeg','2007-11-18 00:25:44'),(4,'bball.jpeg','2007-11-18 00:25:44'),(6,'boating.jpeg','2007-11-18 00:26:20'),(7,'chillin2jpeg.jpeg','2007-11-18 00:26:20'),(8,'chillin.jpeg','2007-11-18 00:26:20'),(9,'dinner2.jpeg','2007-11-18 00:26:20'),(10,'random2.jpeg','2007-11-18 00:26:49'),(11,'random3.jpeg','2007-11-18 00:26:49'),(12,'random4.jpeg','2007-11-18 00:26:49'),(13,'random5.jpeg','2007-11-18 00:26:49'),(14,'random6.jpeg','2007-11-18 00:26:49'),(15,'dinner.jpeg','2007-11-18 08:13:53');
/*!40000 ALTER TABLE `pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `pic_id` tinyint(4) NOT NULL,
  `tag` varchar(15) NOT NULL,
  `type` varchar(12) NOT NULL,
  `hash` varchar(5) NOT NULL,
  UNIQUE KEY `hash` (`hash`),
  KEY `pic_id` (`pic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (5,'KÃ¢â‚¬Ëœtanim','Unit','87de4'),(5,'Bonim','Unit','b5e5d'),(3,'Bonim','Unit','1c679'),(3,'Art','Activities','ff2d8'),(2,'KÃ¢â‚¬Ëœtanim','Unit','ffdda');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `filename` varchar(32) NOT NULL COMMENT 'md5 hash of img filename (salted?)',
  `date` datetime NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'activities2.flv','2007-11-17 22:42:45','This is the first video.  Here is the description.'),(2,'Activities.flv','2007-11-17 22:46:08','This is the second video.  Videos this size will take a few minutes to upload on a fast connection.');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vtags`
--

DROP TABLE IF EXISTS `vtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vtags` (
  `pic_id` tinyint(4) NOT NULL,
  `tag` varchar(15) NOT NULL,
  `type` varchar(12) NOT NULL,
  `hash` varchar(5) NOT NULL,
  UNIQUE KEY `hash` (`hash`),
  KEY `pic_id` (`pic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vtags`
--

LOCK TABLES `vtags` WRITE;
/*!40000 ALTER TABLE `vtags` DISABLE KEYS */;
/*!40000 ALTER TABLE `vtags` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-02  8:43:29
-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: kevinxn_musicteachers
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.12.04.1

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
-- Current Database: `kevinxn_musicteachers`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `kevinxn_musicteachers` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `kevinxn_musicteachers`;

--
-- Table structure for table `event_preferences`
--

DROP TABLE IF EXISTS `event_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `monitor` varchar(20) NOT NULL,
  `pickup` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `due_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `archived` bit(1) DEFAULT NULL,
  `numrecital` varchar(2) DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) DEFAULT NULL,
  `hasEventForm` varchar(1) DEFAULT '1',
  `hasMasterList` varchar(1) DEFAULT '1',
  `price` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL,
  `student_id` int(11) NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `student2_id` int(11) DEFAULT NULL,
  `study_length` varchar(25) DEFAULT NULL,
  `composition` varchar(200) DEFAULT NULL,
  `composition2` varchar(200) DEFAULT NULL,
  `composition3` varchar(200) DEFAULT NULL,
  `composer` varchar(100) DEFAULT NULL,
  `composer2` varchar(100) DEFAULT NULL,
  `composer3` varchar(200) DEFAULT NULL,
  `early_late` varchar(5) DEFAULT NULL,
  `key1` varchar(10) DEFAULT NULL,
  `key2` varchar(10) DEFAULT NULL,
  `musicreq` varchar(2) DEFAULT NULL,
  `scale` varchar(10) DEFAULT NULL,
  `cadence` varchar(10) DEFAULT NULL,
  `rating` int(1) DEFAULT '1',
  `timepreference` int(3) DEFAULT NULL,
  `testlevel` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `student_id` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=963 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` varchar(4) DEFAULT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `grade` varchar(2) NOT NULL,
  `home_school` tinyint(1) DEFAULT '0',
  `info` text,
  `birthdate` date DEFAULT NULL,
  `lengthstudy` varchar(25) NOT NULL,
  `fallenrollment` tinyint(1) NOT NULL DEFAULT '0',
  `springenrollment` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `teacher_no` (`teacher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1429 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_no` varchar(4) DEFAULT NULL,
  `city_zip` varchar(25) DEFAULT NULL,
  `user` varchar(25) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `lname` varchar(16) NOT NULL,
  `pass` varchar(15) NOT NULL,
  `admin` tinyint(1) DEFAULT '0',
  `last_logged_in` datetime DEFAULT NULL,
  `locked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `teacher_no` (`teacher_no`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-02  8:43:29
