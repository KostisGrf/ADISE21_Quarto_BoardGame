-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: quarto_adise
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.21-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `board`
--

DROP TABLE IF EXISTS `board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board` (
  `x` tinyint(1) NOT NULL,
  `y` tinyint(1) NOT NULL,
  `piece_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`x`,`y`),
  KEY `piece_id` (`piece_id`),
  CONSTRAINT `board_ibfk_1` FOREIGN KEY (`piece_id`) REFERENCES `pieces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board`
--

LOCK TABLES `board` WRITE;
/*!40000 ALTER TABLE `board` DISABLE KEYS */;
INSERT INTO `board` VALUES (1,1,NULL),(1,2,NULL),(1,3,NULL),(1,4,NULL),(2,1,NULL),(2,2,NULL),(2,3,NULL),(2,4,NULL),(3,1,NULL),(3,2,NULL),(3,3,NULL),(3,4,NULL),(4,1,NULL),(4,2,NULL),(4,3,NULL),(4,4,NULL);
/*!40000 ALTER TABLE `board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `board_empty`
--

DROP TABLE IF EXISTS `board_empty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_empty` (
  `x` tinyint(1) NOT NULL,
  `y` tinyint(1) NOT NULL,
  `piece_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board_empty`
--

LOCK TABLES `board_empty` WRITE;
/*!40000 ALTER TABLE `board_empty` DISABLE KEYS */;
INSERT INTO `board_empty` VALUES (1,1,NULL),(1,2,NULL),(1,3,NULL),(1,4,NULL),(2,1,NULL),(2,2,NULL),(2,3,NULL),(2,4,NULL),(3,1,NULL),(3,2,NULL),(3,3,NULL),(3,4,NULL),(4,1,NULL),(4,2,NULL),(4,3,NULL),(4,4,NULL);
/*!40000 ALTER TABLE `board_empty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_status`
--

DROP TABLE IF EXISTS `game_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `game_status` (
  `status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
  `p_turn` int(11) DEFAULT NULL,
  `selected_piece` int(11) DEFAULT NULL,
  `result` enum('1','2','D') DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
  KEY `selected_piece` (`selected_piece`),
  KEY `p_turn` (`p_turn`),
  CONSTRAINT `game_status_ibfk_2` FOREIGN KEY (`p_turn`) REFERENCES `players` (`id`),
  CONSTRAINT `game_status_ibfk_3` FOREIGN KEY (`selected_piece`) REFERENCES `pieces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_status`
--

LOCK TABLES `game_status` WRITE;
/*!40000 ALTER TABLE `game_status` DISABLE KEYS */;
INSERT INTO `game_status` VALUES ('not active',NULL,NULL,NULL,'2021-12-07 02:43:12');
/*!40000 ALTER TABLE `game_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pieces`
--

DROP TABLE IF EXISTS `pieces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pieces` (
  `id` int(11) NOT NULL,
  `round_shape` tinyint(1) NOT NULL,
  `big_size` tinyint(1) NOT NULL,
  `light_color` tinyint(1) NOT NULL,
  `top_hole` tinyint(1) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pieces`
--

LOCK TABLES `pieces` WRITE;
/*!40000 ALTER TABLE `pieces` DISABLE KEYS */;
INSERT INTO `pieces` VALUES (1,0,1,0,0,1),(2,0,1,0,1,1),(3,1,1,0,0,1),(4,1,1,0,1,1),(5,0,0,0,0,1),(6,0,0,0,1,1),(7,1,0,0,0,1),(8,1,0,0,1,1),(9,0,1,1,0,1),(10,0,1,1,1,1),(11,1,1,1,0,1),(12,1,1,1,1,1),(13,0,0,1,0,1),(14,0,0,1,1,1),(15,1,0,1,0,1),(16,1,0,1,1,1);
/*!40000 ALTER TABLE `pieces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (1,NULL,NULL,NULL),(2,NULL,NULL,NULL);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'quarto_adise'
--

--
-- Dumping routines for database 'quarto_adise'
--
/*!50003 DROP PROCEDURE IF EXISTS `clean_board` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DROP procedure IF EXISTS `clean_board`;

DELIMITER $$
USE `quarto_adise`$$
CREATE PROCEDURE `clean_board` ()
BEGIN
replace into board select * from board_empty;
	update `players` set username=null, token=null;
  update `pieces` set `is_available`=1 Where `is_available`=0;
  update `game_status` set `status`='not active', `p_turn`=null, `result`=null, `selected_piece`=null;
END$$

DELIMITER ;



DROP procedure IF EXISTS `select_piece`;

DELIMITER $$
USE `quarto_adise`$$
CREATE PROCEDURE `select_piece` (piece_id int)
BEGIN
Update game_status set selected_piece=piece_id;
Update pieces set is_available=0 Where id=piece_id;
update game_status set p_turn=if(p_turn=1,2,1);
END$$

DELIMITER ;

DROP procedure IF EXISTS `move_piece`;

DELIMITER $$
USE `quarto_adise`$$
CREATE PROCEDURE `move_piece` (piece_id int,x1 int,y1 int)
BEGIN
Update board set piece_id=piece_id Where x=x1 and y=y1;
update game_status set selected_piece=null;
END$$

DELIMITER ;




-- Dump completed on 2021-12-27  5:32:38
