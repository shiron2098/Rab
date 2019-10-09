-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: daws2
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Table structure for table `commands`
--

DROP TABLE IF EXISTS `commands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `CodeIndexCommands` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commands`
--

LOCK TABLES `commands` WRITE;
/*!40000 ALTER TABLE `commands` DISABLE KEYS */;
INSERT INTO `commands` VALUES (2,'get_pos_bi','T2s_exportPos'),(3,'get_pro_bi','t2s_exportPRO_BI'),(4,'get_vvs_bi','t2s_exportVisits');
/*!40000 ALTER TABLE `commands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_history`
--

DROP TABLE IF EXISTS `job_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `command_name` varchar(100) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `operator_name` varchar(100) NOT NULL,
  `software_provider` varchar(50) NOT NULL,
  `execute_start_time_dt` datetime(3) NOT NULL,
  `execute_end_time_dt` datetime(3) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Indexjob_history` (`job_id`,`execute_start_time_dt`,`execute_end_time_dt`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_history`
--

LOCK TABLES `job_history` WRITE;
/*!40000 ALTER TABLE `job_history` DISABLE KEYS */;
INSERT INTO `job_history` VALUES (1,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 12:36:47.971','2019-09-06 12:36:48.121','ERROR','[Job id #2]Command Execution is already in progress'),(2,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 12:36:48.142','2019-09-06 12:36:48.245','ERROR','[Job id #3]Command Execution is already in progress'),(3,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 12:36:48.249','2019-09-06 12:36:48.379','ERROR','[Job id #4]Command Execution is already in progress'),(4,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:07.479','2019-09-06 12:37:25.268','ERROR','[Job id #2]Provider returned no data'),(5,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:07.712','2019-09-06 12:37:27.457','ERROR','[Job id #3]Provider returned no data'),(6,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:07.958','2019-09-06 12:37:29.816','ERROR','[Job id #4]Provider returned no data'),(7,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:42.617','2019-09-06 12:39:59.620','OK','[Job id #2]Result was delivered to Data queue successfully.'),(8,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:42.938',NULL,'RUN','[Job id #3]is processing'),(9,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 12:37:43.166',NULL,'RUN','[Job id #4]is processing'),(10,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 12:40:03.625','2019-09-06 12:40:03.640','ERROR','[Job id #2]Task tried to be performed out of schedule '),(11,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 12:40:03.684','2019-09-06 12:56:28.773','OK','[Job id #3]Result was delivered to Data queue successfully.'),(12,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 12:40:03.972','2019-09-06 12:56:44.923','OK','[Job id #4]Result was delivered to Data queue successfully.'),(13,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 12:59:39.336','2019-09-06 13:01:52.675','OK','[Job id #2]Result was delivered to Data queue successfully.'),(14,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 12:59:39.681','2019-09-06 13:01:55.154','OK','[Job id #3]Result was delivered to Data queue successfully.'),(15,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 12:59:39.963','2019-09-06 13:01:58.289','OK','[Job id #4]Result was delivered to Data queue successfully.'),(16,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 13:03:18.071','2019-09-06 13:03:23.874','OK','[Job id #2]Result was delivered to Data queue successfully.'),(17,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 13:03:18.352','2019-09-06 13:03:26.209','OK','[Job id #3]Result was delivered to Data queue successfully.'),(18,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 13:03:18.557','2019-09-06 13:03:28.700','OK','[Job id #4]Result was delivered to Data queue successfully.'),(19,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 13:16:35.253','2019-09-06 13:16:39.996','OK','[Job id #2]Result was delivered to Data queue successfully.'),(20,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 13:16:35.521','2019-09-06 13:16:42.300','OK','[Job id #3]Result was delivered to Data queue successfully.'),(21,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 13:16:35.702','2019-09-06 13:16:43.210','OK','[Job id #4]Result was delivered to Data queue successfully.'),(22,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-06 14:28:51.189','2019-09-06 14:29:12.812','OK','[Job id #2]Result was delivered to Data queue successfully.'),(23,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-06 14:28:51.851','2019-09-06 14:29:12.834','OK','[Job id #3]Result was delivered to Data queue successfully.'),(24,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-06 14:28:52.242','2019-09-06 14:29:15.420','OK','[Job id #4]Result was delivered to Data queue successfully.'),(25,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-11 10:11:03.353','2019-09-11 10:11:29.954','OK','[Job id #2]Result was delivered to Data queue successfully.'),(26,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-11 10:11:05.288','2019-09-11 10:11:29.900','OK','[Job id #3]Result was delivered to Data queue successfully.'),(27,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-11 10:11:05.846','2019-09-11 10:11:32.491','OK','[Job id #4]Result was delivered to Data queue successfully.'),(28,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-11 10:14:24.044',NULL,'RUN','[Job id #2]is processing'),(29,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-11 10:14:24.303',NULL,'RUN','[Job id #3]is processing'),(30,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-11 10:14:24.503',NULL,'RUN','[Job id #4]is processing'),(31,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-18 08:35:03.491','2019-09-18 08:35:43.032','OK','[Job id #2]Result was delivered to Data queue successfully.'),(32,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-18 08:35:09.910','2019-09-18 08:35:45.558','OK','[Job id #3]Result was delivered to Data queue successfully.'),(33,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-18 08:35:10.278','2019-09-18 08:35:48.757','OK','[Job id #4]Result was delivered to Data queue successfully.'),(34,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-18 09:43:52.254','2019-09-18 09:43:59.899','OK','[Job id #2]Result was delivered to Data queue successfully.'),(35,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-18 09:43:52.861','2019-09-18 09:44:02.588','OK','[Job id #3]Result was delivered to Data queue successfully.'),(36,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-18 09:43:53.083','2019-09-18 09:44:05.090','OK','[Job id #4]Result was delivered to Data queue successfully.'),(37,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-18 09:47:04.440','2019-09-18 09:47:13.112','OK','[Job id #2]Result was delivered to Data queue successfully.'),(38,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-18 09:47:05.869','2019-09-18 09:47:15.462','OK','[Job id #3]Result was delivered to Data queue successfully.'),(39,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-18 09:47:06.095','2019-09-18 09:47:18.011','OK','[Job id #4]Result was delivered to Data queue successfully.'),(40,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 08:26:42.489','2019-09-25 08:27:08.393','OK','[Job id #2]Result was delivered to Data queue successfully.'),(41,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 08:26:47.576','2019-09-25 08:27:10.716','OK','[Job id #3]Result was delivered to Data queue successfully.'),(42,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 08:26:47.914','2019-09-25 08:27:24.048','OK','[Job id #4]Result was delivered to Data queue successfully.'),(43,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 08:28:31.696','2019-09-25 08:28:39.960','OK','[Job id #2]Result was delivered to Data queue successfully.'),(44,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 08:28:31.992','2019-09-25 08:28:42.233','OK','[Job id #3]Result was delivered to Data queue successfully.'),(45,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 08:28:32.203','2019-09-25 08:28:56.569','OK','[Job id #4]Result was delivered to Data queue successfully.'),(46,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 11:12:33.606','2019-09-25 11:12:42.440','OK','[Job id #2]Result was delivered to Data queue successfully.'),(47,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 11:12:34.764','2019-09-25 11:12:44.778','OK','[Job id #3]Result was delivered to Data queue successfully.'),(48,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 11:12:35.066','2019-09-25 11:12:56.910','OK','[Job id #4]Result was delivered to Data queue successfully.'),(49,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 11:30:02.856','2019-09-25 11:30:08.390','OK','[Job id #2]Result was delivered to Data queue successfully.'),(50,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 11:30:03.180','2019-09-25 11:30:10.837','OK','[Job id #3]Result was delivered to Data queue successfully.'),(51,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 11:30:03.402','2019-09-25 11:30:22.091','OK','[Job id #4]Result was delivered to Data queue successfully.'),(52,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 11:52:46.880','2019-09-25 11:52:54.579','OK','[Job id #2]Result was delivered to Data queue successfully.'),(53,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 11:52:47.190','2019-09-25 11:52:57.127','OK','[Job id #3]Result was delivered to Data queue successfully.'),(54,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 11:52:47.358','2019-09-25 11:53:09.490','OK','[Job id #4]Result was delivered to Data queue successfully.'),(55,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-25 15:36:36.610','2019-09-25 15:36:48.552','OK','[Job id #2]Result was delivered to Data queue successfully.'),(56,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-25 15:36:36.942','2019-09-25 15:36:50.890','OK','[Job id #3]Result was delivered to Data queue successfully.'),(57,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-25 15:36:37.306','2019-09-25 15:37:03.266','OK','[Job id #4]Result was delivered to Data queue successfully.'),(58,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-26 09:32:52.455','2019-09-26 09:34:08.283','ERROR','[Job id #2]Provider returned no data'),(59,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-26 09:32:54.388','2019-09-26 09:34:33.468','ERROR','[Job id #3]Provider returned no data'),(60,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-26 09:32:54.778',NULL,'RUN','[Job id #4]is processing'),(61,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-26 15:33:35.519','2019-09-26 15:33:42.684','OK','[Job id #2]Result was delivered to Data queue successfully.'),(62,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-26 15:33:36.514','2019-09-26 15:33:45.071','OK','[Job id #3]Result was delivered to Data queue successfully.'),(63,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-26 15:33:36.788','2019-09-26 15:33:59.670','OK','[Job id #4]Result was delivered to Data queue successfully.'),(64,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-26 15:51:37.337','2019-09-26 15:51:43.534','OK','[Job id #2]Result was delivered to Data queue successfully.'),(65,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-26 15:51:37.859','2019-09-26 15:51:45.998','OK','[Job id #3]Result was delivered to Data queue successfully.'),(66,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-26 15:51:38.116','2019-09-26 15:51:58.064','OK','[Job id #4]Result was delivered to Data queue successfully.'),(67,2,'get_pos_bi',2,'CLASS2','Vendmax','2019-09-30 11:47:41.768','2019-09-30 11:47:53.116','OK','[Job id #2]Result was delivered to Data queue successfully.'),(68,3,'get_pro_bi',2,'CLASS2','Vendmax','2019-09-30 11:47:44.341','2019-09-30 11:47:56.508','OK','[Job id #3]Result was delivered to Data queue successfully.'),(69,4,'get_vvs_bi',2,'CLASS2','Vendmax','2019-09-30 11:47:44.990','2019-09-30 11:48:08.893','OK','[Job id #4]Result was delivered to Data queue successfully.');
/*!40000 ALTER TABLE `job_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_schedule`
--

DROP TABLE IF EXISTS `job_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `execute_interval` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CodeAndDecriptionIndex` (`job_id`,`execute_interval`),
  CONSTRAINT `job_schedule_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_schedule`
--

LOCK TABLES `job_schedule` WRITE;
/*!40000 ALTER TABLE `job_schedule` DISABLE KEYS */;
INSERT INTO `job_schedule` VALUES (2,2,5),(3,3,5),(4,4,5);
/*!40000 ALTER TABLE `job_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) NOT NULL,
  `last_execute_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `command_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `JobIndex` (`operator_id`,`last_execute_dt`),
  KEY `OperatorIndexJobs` (`command_id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`command_id`) REFERENCES `commands` (`id`),
  CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (2,2,'2019-09-30 11:47:53',2),(3,2,'2019-09-30 11:47:56',3),(4,2,'2019-09-30 11:48:08',4);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operators`
--

DROP TABLE IF EXISTS `operators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `software_provider_id` int(11) NOT NULL,
  `connection_url` varchar(100) NOT NULL,
  `streams` tinyint(4) DEFAULT NULL,
  `streams_response` tinyint(4) DEFAULT NULL,
  `user_name` varchar(60) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `code` (`code`),
  KEY `Indexoperators` (`software_provider_id`),
  CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`software_provider_id`) REFERENCES `software_providers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operators`
--

LOCK TABLES `operators` WRITE;
/*!40000 ALTER TABLE `operators` DISABLE KEYS */;
INSERT INTO `operators` VALUES (2,'CLASS2','29',2,'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2',0,0,'Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466');
/*!40000 ALTER TABLE `operators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `software_providers`
--

DROP TABLE IF EXISTS `software_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `software_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `CodeSoftware_Providers` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `software_providers`
--

LOCK TABLES `software_providers` WRITE;
/*!40000 ALTER TABLE `software_providers` DISABLE KEYS */;
INSERT INTO `software_providers` VALUES (2,'Vendmax','Vendmax');
/*!40000 ALTER TABLE `software_providers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-10-09 15:33:29
