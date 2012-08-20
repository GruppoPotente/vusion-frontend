-- MySQL dump 10.13  Distrib 5.5.16, for Linux (i686)
--
-- Host: localhost    Database: vusion
-- ------------------------------------------------------
-- Server version	5.5.16

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
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'administrator',0,'2012-01-30 20:48:19','2012-01-30 20:48:19'),(2,'manager',0,'2012-01-30 20:49:52','2012-01-30 20:49:52'),(3,'program manager',1,'2012-01-30 20:50:00','2012-01-31 08:03:07'),(4,'customer',1,'2012-01-30 20:50:08','2012-01-31 08:03:18');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `acos`
--

LOCK TABLES `acos` WRITE;
/*!40000 ALTER TABLE `acos` DISABLE KEYS */;
INSERT INTO `acos` VALUES (31,28,'',0,'add',43,44),(3,2,'',0,'index',3,4),(4,2,'',0,'view',5,6),(32,28,'',0,'edit',45,46),(5,2,'',0,'add',7,8),(6,2,'',0,'edit',9,10),(2,1,'',0,'Groups',2,13),(7,2,'',0,'delete',11,12),(33,28,'',0,'delete',47,48),(34,28,'',0,'login',49,50),(35,28,'',0,'logout',51,52),(17,16,'',0,'index',15,16),(18,16,'',0,'view',17,18),(19,16,'',0,'add',19,20),(20,16,'',0,'edit',21,22),(16,1,'',0,'Programs',14,25),(21,16,'',0,'delete',23,24),(23,22,'',0,'index',27,28),(24,22,'',0,'view',29,30),(25,22,'',0,'add',31,32),(26,22,'',0,'edit',33,34),(22,1,'',0,'ProgramsUsers',26,37),(27,22,'',0,'delete',35,36),(29,28,'',0,'index',39,40),(30,28,'',0,'view',41,42),(28,1,'',0,'Users',38,57),(36,1,'',0,'AclExtras',58,59),(37,1,'',0,'Mongodb',60,61),(44,28,'',0,'initDB',53,54),(1,0,'',0,'controllers',1,184),(72,59,NULL,NULL,'index',65,66),(59,1,NULL,NULL,'ProgramSettings',62,69),(60,59,NULL,NULL,'edit',63,64),(61,1,NULL,NULL,'ShortCodes',70,79),(62,61,NULL,NULL,'index',71,72),(63,61,NULL,NULL,'add',73,74),(64,61,NULL,NULL,'edit',75,76),(65,61,NULL,NULL,'delete',77,78),(66,28,NULL,NULL,'changePassword',55,56),(69,1,NULL,NULL,'Admin',80,83),(70,69,NULL,NULL,'index',81,82),(73,59,NULL,NULL,'view',67,68),(74,1,NULL,NULL,'ProgramHistory',84,89),(75,74,NULL,NULL,'index',85,86),(76,74,NULL,NULL,'export',87,88),(77,1,NULL,NULL,'ProgramHome',90,95),(78,77,NULL,NULL,'index',91,92),(79,1,NULL,NULL,'ProgramParticipants',96,111),(80,79,NULL,NULL,'index',97,98),(81,79,NULL,NULL,'add',99,100),(82,79,NULL,NULL,'edit',101,102),(83,79,NULL,NULL,'delete',103,104),(84,79,NULL,NULL,'view',105,106),(85,79,NULL,NULL,'import',107,108),(86,79,NULL,NULL,'checkPhoneNumber',109,110),(94,1,NULL,NULL,'UnmatchableReply',112,115),(95,94,NULL,NULL,'index',113,114),(101,1,NULL,NULL,'ProgramUnattachedMessages',116,125),(102,101,NULL,NULL,'index',117,118),(103,101,NULL,NULL,'add',119,120),(104,101,NULL,NULL,'edit',121,122),(105,101,NULL,NULL,'delete',123,124),(106,1,NULL,NULL,'Documentation',126,129),(107,106,NULL,NULL,'view',127,128),(108,1,NULL,NULL,'ProgramSimulator',130,137),(109,108,NULL,NULL,'simulate',131,132),(110,108,NULL,NULL,'send',133,134),(111,108,NULL,NULL,'receive',135,136),(112,1,NULL,NULL,'ProgramLogs',138,143),(113,112,NULL,NULL,'index',139,140),(114,112,NULL,NULL,'getBackendNotifications',141,142),(116,1,NULL,NULL,'ProgramDialogues',144,159),(117,116,NULL,NULL,'index',145,146),(118,116,NULL,NULL,'save',147,148),(119,116,NULL,NULL,'edit',149,150),(120,116,NULL,NULL,'activate',151,152),(121,116,NULL,NULL,'validateKeyword',153,154),(122,116,NULL,NULL,'testSendAllMessages',155,156),(123,1,NULL,NULL,'Templates',160,169),(124,123,NULL,NULL,'index',161,162),(125,123,NULL,NULL,'add',163,164),(126,123,NULL,NULL,'edit',165,166),(127,123,NULL,NULL,'delete',167,168),(128,1,NULL,NULL,'ProgramRequests',170,183),(129,128,NULL,NULL,'index',171,172),(130,128,NULL,NULL,'add',173,174),(131,128,NULL,NULL,'edit',175,176),(132,128,NULL,NULL,'delete',177,178),(133,128,NULL,NULL,'validateKeyword',179,180),(134,128,NULL,NULL,'getActiveDialogue',181,182),(135,77,NULL,NULL,'restartWorker',93,94),(136,116,NULL,NULL,'delete',157,158);
/*!40000 ALTER TABLE `acos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `aros`
--

LOCK TABLES `aros` WRITE;
/*!40000 ALTER TABLE `aros` DISABLE KEYS */;
INSERT INTO `aros` VALUES (1,0,'Group',1,'',1,10),(8,1,'User',8,'',2,3),(5,0,'Group',2,'',11,16),(18,5,'User',17,NULL,14,15),(10,6,'User',10,'',18,19),(11,7,'User',11,'',22,23),(12,7,'User',12,'',24,25),(6,0,'Group',3,'',17,20),(7,0,'Group',4,'',21,28),(13,1,'User',13,NULL,4,5),(14,1,'User',14,NULL,6,7),(15,1,'User',1,NULL,8,9),(17,5,'User',16,NULL,12,13),(19,7,'User',18,NULL,26,27);
/*!40000 ALTER TABLE `aros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `aros_acos`
--

LOCK TABLES `aros_acos` WRITE;
/*!40000 ALTER TABLE `aros_acos` DISABLE KEYS */;
INSERT INTO `aros_acos` VALUES (65,1,1,'1','1','1','1'),(66,5,1,'-1','-1','-1','-1'),(67,5,28,'1','1','1','1'),(68,5,16,'1','1','1','1'),(69,5,22,'1','1','1','1'),(70,5,77,'1','1','1','1'),(71,5,79,'1','1','1','1'),(105,6,116,'1','1','1','1'),(73,5,74,'1','1','1','1'),(74,5,59,'1','1','1','1'),(75,5,61,'1','1','1','1'),(76,6,1,'-1','-1','-1','-1'),(77,6,16,'-1','-1','-1','-1'),(78,6,17,'1','1','1','1'),(79,6,77,'1','1','1','1'),(80,6,79,'1','1','1','1'),(104,5,116,'1','1','1','1'),(82,6,74,'1','1','1','1'),(83,6,59,'-1','-1','-1','-1'),(84,6,72,'1','1','1','1'),(85,6,73,'1','1','1','1'),(86,6,61,'1','1','1','1'),(87,7,1,'-1','-1','-1','-1'),(88,7,17,'1','1','1','1'),(89,7,18,'1','1','1','1'),(90,7,77,'1','1','1','1'),(91,7,82,'-1','-1','-1','-1'),(92,7,81,'-1','-1','-1','-1'),(93,7,80,'1','1','1','1'),(94,7,84,'1','1','1','1'),(95,7,74,'1','1','1','1'),(96,5,94,'1','1','1','1'),(97,6,94,'1','1','1','1'),(98,5,108,'1','1','1','1'),(99,5,101,'1','1','1','1'),(100,6,108,'1','1','1','1'),(101,6,101,'1','1','1','1'),(102,5,112,'1','1','1','1'),(103,6,112,'1','1','1','1'),(106,5,123,'1','1','1','1'),(107,5,128,'1','1','1','1'),(108,6,128,'1','1','1','1');
/*!40000 ALTER TABLE `aros_acos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-20 12:44:26
