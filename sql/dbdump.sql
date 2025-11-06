-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: team02
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `athletes`
--

DROP TABLE IF EXISTS `athletes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `athletes` (
  `athlete_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`athlete_id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `athletes_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `athletes`
--

LOCK TABLES `athletes` WRITE;
/*!40000 ALTER TABLE `athletes` DISABLE KEYS */;
INSERT INTO `athletes` VALUES (1,'Mikael KINGSBURY',4),(2,'Walter WALLBERG',7),(3,'David WISE',3),(4,'Alex FERREIRA',3),(5,'Henrik HARLAUT',7),(6,'Birk RUUD',1),(7,'Colby STEVENSON',3),(8,'Alex FIVA',10),(9,'Ryan REGEZ',10),(10,'Mathilde GREMAUD',10),(11,'Tess LEDEUX',8),(12,'Jaelin KAUF',3),(13,'Marielle THOMPSON',4),(14,'Sandra NAESLUND',7),(15,'Daniela MAIER',2),(16,'Megan NICK',3),(17,'Cassie SHARPE',4),(18,'Rachel KARKER',4),(19,'Nicholas GOEPPER',3),(20,'Jesper TJADER',7),(21,'Alexander HALL',3),(22,'Suzanne SCHULTING',5),(23,'Kim BOUTIN',4),(24,'Steven DUBOIS',4),(25,'Chloe KIM',3),(26,'Meryeta ODINE',4),(27,'Chloe TRESPEUCH',8),(28,'Lindsey JACOBELLIS',3),(29,'Benjamin KARL',6),(30,'Anna GASSER',6),(31,'Eliot GRONDIN',4),(32,'Alessandro HAEMMERLE',6),(33,'Max PARROT',4),(34,'Mark MCMORRIS',4),(35,'Jan SCHERRER',10),(36,'Julia MARINO',3),(37,'Daniela ULBING',6),(38,'Mons ROISLAND',1),(39,'Marius LINDVIK',1),(40,'Karl GEIGER',2),(41,'Katharina ALTHAUS',2),(42,'Manuel FETTNER',6),(43,'Nathan CHEN',3),(44,'Wolfgang KINDL',6),(45,'Johannes LUDWIG',2),(46,'Anna BERREITER',2),(47,'Natalie GEISENBERGER',2),(48,'Quentin FILLON MAILLET',8),(49,'Tarjei BOE',1),(50,'Tiril ECKHOFF',1),(51,'Justine BRAISAZ',8),(52,'Marte Olsbu ROEISELAND',1),(53,'Elvira OEBERG',7),(54,'Johannes Thingnes BOE',1),(55,'Martin PONSILUOMA',7),(56,'Vetle Sjaastad CHRISTIANSEN',1),(57,'Denise HERRMANN',2),(58,'Anais CHEVALIER',8),(59,'Clement NOEL',8),(60,'Johannes STROLZ',6),(61,'Sebastian FOSS-SOLEVAAG',1),(62,'Beat FEUZ',10),(63,'Johan CLAREY',8),(64,'Matthias MAYER',6),(65,'Mirjam PUCHNER',6),(66,'Michelle GISIN',10),(67,'Lara GUT - BEHRAMI',10),(68,'Aleksander Aamodt KILDE',1),(69,'Ryan COCHRAN-SIEGLE',3),(70,'Corinne SUTER',10),(71,'James CRAWFORD',4),(72,'Mathieu FAIVRE',8),(73,'Marco ODERMATT',10),(74,'Sara HECTOR',7),(75,'Wendy HOLDENER',10),(76,'Katharina LIENSBERGER',6),(77,'Hannah NEISE',2),(78,'Kimberley BOS',5),(79,'Axel JUNGK',2),(80,'Christopher GROTHEER',2),(81,'Maja DAHLQVIST',7),(82,'Jessica DIGGINS',3),(83,'Jonna SUNDLING',7),(84,'Teresa STADLOBER',6),(85,'Therese JOHAUG',1),(86,'Simen Hegstad KRUEGER',1),(87,'Johannes Hoesflot KLAEBO',1),(88,'Kjeld NUIS',5),(89,'Thomas KROL',5),(90,'Jutta LEERDAM',5),(91,'Brittany BOWE',3),(92,'Laurent DUBREUIL',4),(93,'Havard LORENTZEN',1),(94,'Ivanie BLONDIN',4),(95,'Irene SCHOUTEN',5),(96,'Patrick ROEST',5),(97,'Nils VAN DER POEL',7),(98,'Hallgeir ENGEBRAATEN',1),(99,'Isabelle WEIDEMANN',4),(100,'Antoinette DE JONG',5),(101,'Erin JACKSON',3),(102,'Joergen GRAABAK',1),(103,'Vinzenz GEIGER',2),(104,'Lukas GREIDERER',6),(105,'Jens Luraas OFTEBRO',1),(106,'Elana MEYERS TAYLOR',3),(107,'Christine DE BRUIN',4),(108,'Kaillie HUMPHRIES',3),(109,'Jacqueline LOELLING',2),(110,'Sven KRAMER',5),(111,'Ted-Jan BLOEMEN',4),(112,'Sverre Lunde PEDERSEN',1),(113,'Jorrit BERGSMA',5),(114,'Carlijn ACHTEREEKTE',5),(115,'Esmee VISSER',5),(116,'Marrit LEENSTRA',5),(117,'Koen VERWEIJ',5),(118,'Jorien TER MORS',5),(119,'Dominik LANDERTINGER',6),(120,'Hanna OEBERG',7),(121,'Laura DAHLMEIER',2),(122,'Arnd PEIFFER',2),(123,'Martin FOURCADE',8),(124,'Sebastian SAMUELSSON',7),(125,'Benedikt DOLL',2),(126,'Marte OLSBU',1),(127,'Anais BESCOND',8),(128,'Simon SCHEMPP',2),(129,'Emil Hegle SVENDSEN',1),(130,'Pierre VAULTIER',8),(131,'Sebastien TOUTANT',4),(132,'Kyle MACK',3),(133,'Jamie ANDERSON',3),(134,'Laurie BLOUIN',4),(135,'Julia PEREIRA DE SOUSA MABILEAU',8),(136,'Arielle Gold',3),(137,'Selina JOERG',2),(138,'Ramona Theresia HOFMEISTER',2),(139,'Nevin GALMARINI',10),(140,'Shaun WHITE',3),(141,'Redmond GERARD',3),(142,'Sarah HOEFFLIN',10),(143,'Perrine LAFFONT',8),(144,'Justine DUFOUR-LAPOINTE',4),(145,'Kelsey SERWA',4),(146,'Brittany PHELAN',4),(147,'Fanny SMITH',10),(148,'Marie MARTINOD',8),(149,'Brita SIGOURNEY',3),(150,'Brady LEMAN',4),(151,'Marc BISCHOFBERGER',10),(152,'Alex BEAULIEU-MARCHAND',4),(153,'Johannes RYDZEK',2),(154,'Fabian RIESSLE',2),(155,'Eric FRENZEL',2),(156,'Lukas KLAPFER',6),(157,'Kaetlyn OSMOND',4),(158,'Andreas WELLINGER',2),(159,'Robert JOHANSSON',1),(160,'Maren LUNDBY',1),(161,'Johann Andre FORFANG',1),(162,'Yara VAN KERKHOF',5),(163,'Samuel GIRARD',4),(164,'John-Henry KRUEGER',3),(165,'Sjinkie KNEGT',5),(166,'Mikaela SHIFFRIN',3),(167,'Marcel HIRSCHER',6),(168,'Alexis PINTURAULT',8),(169,'Victor MUFFAT-JEANDET',8),(170,'Henrik KRISTOFFERSEN',1),(171,'Andre MYHRER',7),(172,'Ramon ZENHAEUSERN',10),(173,'Michael MATT',6),(174,'Kjetil JANSRUD',1),(175,'Frida HANSDOTTER',7),(176,'Katharina GALLHUBER',6),(177,'Ragnhild MOWINCKEL',1),(178,'Lindsey VONN',3),(179,'Aksel Lund SVINDAL',1),(180,'Anna VEITH',6),(181,'Dajana EITBERGER',2),(182,'Alex GOUGH',4),(183,'David GLEIRSCHER',6),(184,'Chris MAZDZER',3),(185,'Dario COLOGNA',10),(186,'Ragnhild HAGA',1),(187,'Charlotte KALLA',7),(188,'Marit BJOERGEN',1),(189,'Martin Johnsrud SUNDBY',1),(190,'Hans Christer HOLUND',1),(191,'Stina NILSSON',7),(192,'Maiken Caspersen FALLA',1),(193,'Jean Guillaume BEATRIX',8),(194,'Erik LESSER',2),(195,'Evgeniy GARANICHEV',9),(196,'Tora BERGER',1),(197,'Selina GASPARIN',10),(198,'Magnus KROG',1),(199,'Magnus Hovdal MOAN',1),(200,'Nicole HOSP',6),(201,'Andrew WEIBRECHT',3),(202,'Jan HUDEC',4),(203,'Bode MILLER',3),(204,'Julia MANCUSO',3),(205,'Viktoria REBENSBURG',2),(206,'Ted LIGETY',3),(207,'Steve MISSILLIER',8),(208,'Dominique GISIN',10),(209,'Marlies SCHILD',6),(210,'Kathrin ZETTEL',6),(211,'Sandro VILETTA',10),(212,'Mario MATT',6),(213,'Charles HAMELIN',4),(214,'Victor AN',9),(215,'Vladimir GRIGOREV',9),(216,'Charle COURNOYER',4),(217,'Jan BLOKHUIJSEN',5),(218,'Carien KLEIBEUKER',5),(219,'Stefan GROOTHUIS',5),(220,'Denny MORRISON',4),(221,'Michel MULDER',5),(222,'Olga GRAF',9),(223,'Lotte VAN BEEK',5),(224,'Olga FATKULINA',9),(225,'Margot BOER',5),(226,'Jan SMEEKENS',5),(227,'Ronald MULDER',5),(228,'Bob Johannes Carolus DE JONG',5),(229,'Marcus HELLNER',7),(230,'Ola Vigen HATTESTAD',1),(231,'Teodor PETERSON',7),(232,'Emil JOENSSON',7),(233,'Heidi WENG',1),(234,'Alexander LEGKOV',9),(235,'Maxim VYLEGZHANIN',9),(236,'Ilia CHERNOUSOV',9),(237,'Johan OLSSON',7),(238,'Daniel RICKARDSSON',7),(239,'Ingvild Flugstad OESTBERG',1),(240,'Kristin Stoermer STEIRA',1),(241,'Adelina SOTNIKOVA',9),(242,'Patrick CHAN',4),(243,'Anders BARDAL',1),(244,'Carina VOGT',2),(245,'Daniela IRASCHKO-STOLZ',6),(246,'Coline MATTEL',8),(247,'Julia DUJMOVITS',6),(248,'Anke WOEHRER',2),(249,'Amelie KOBER',2),(250,'Kaitlyn FARRINGTON',3),(251,'Kelly CLARK',3),(252,'Patrizia KUMMER',10),(253,'Alena ZAVARZINA',9),(254,'Dominique MALTAIS',4),(255,'Vic WILD',9),(256,'Iouri Podladtchikov',10),(257,'Sage KOTSENBURG',3),(258,'Staale SANDBECH',1),(259,'Nikolay OLYUNIN',9),(260,'Alex DEIBOLD',3),(261,'Alexander TRETYAKOV',9),(262,'Matt ANTOINE',3),(263,'Noelle PIKUS-PACE',3),(264,'Elena NIKITINA',9),(265,'Tatjana HUEFNER',2),(266,'Erin HAMLIN',3),(267,'Felix LOCH',2),(268,'Albert DEMCHENKO',9),(269,'Jean Frederic CHAPUIS',8),(270,'Arnaud BOVOLENTA',8),(271,'Jonathan MIDOL',8),(272,'Mike RIDDLE',4),(273,'Kevin ROLLAND',8),(274,'Dara HOWELL',4),(275,'Devin LOGAN',3),(276,'Kim LAMARRE',4),(277,'Maddie BOWMAN',3),(278,'Alexandre BILODEAU',4),(279,'Aleksandr SMYSHLIAEV',9),(280,'Chloe DUFOUR-LAPOINTE',4),(281,'Hannah KEARNEY',3),(282,'Joss CHRISTENSEN',3),(283,'Gus KENWORTHY',3),(284,'Anna HOLMLUND',7),(285,'Evan LYSACEK',3),(286,'Evgeni Viktorovich PLUSHENKO',9),(287,'Joannie ROCHETTE',4),(288,'Mike Schmid',10),(289,'Andreas MATT',6),(290,'Audun GROENVOLD',1),(291,'Bryon WILSON',3),(292,'Jennifer HEIL',4),(293,'Shannon BAHRKE',3),(294,'Jeret PETERSON',3),(295,'Ashleigh MCIVOR',4),(296,'Hedda BERNTSEN',1),(297,'Marion JOSSERAND',8),(298,'Stephanie BECKERT',2),(299,'Clara HUGHES',4),(300,'Christine NESBITT',4),(301,'Annette GERRITSEN',5),(302,'Laurine VAN RIESSEN',5),(303,'Jenny WOLF',2),(304,'Mark TUITERT',5),(305,'Shani DAVIS',3),(306,'Havard BOKKO',1),(307,'Kristina GROVES',4),(308,'Chad HEDRICK',3),(309,'Ivan SKOBREV',9),(310,'Silvan ZURBRIGGEN',10),(311,'Andrea FISCHBACHER',6),(312,'Elisabeth GOERGL',6),(313,'Didier DEFAGO',10),(314,'Carlo JANKA',10),(315,'Anja PAERSON',7),(316,'David MOLLER',2),(317,'Nina REITHMAYER',6),(318,'Jason LAMY CHAPPUIS',8),(319,'Johnny SPILLANE',3),(320,'William Michael DEMONG',3),(321,'Bernhard GRUBER',6),(322,'Jon MONTGOMERY',4),(323,'Kerstin SZYMKOWIAK',2),(324,'Anja HUBER',2),(325,'Katherine REUTTER',3),(326,'Apolo Anton OHNO',3),(327,'J R CELSKI',3),(328,'Marianne ST GELAIS',4),(329,'Simon AMMANN',10),(330,'Gregor SCHLIERENZAUER',6),(331,'Petter NORTHUG JR.',1),(332,'Axel TEICHMANN',2),(333,'Anna HAAG',7),(334,'Nikita KRIUKOV',9),(335,'Aleksandr Panzhinsky',9),(336,'Tobias ANGERER',2),(337,'Seth WESCOTT',3),(338,'Mike ROBERTSON',4),(339,'Tony RAMOIN',8),(340,'Maeelle Danica RICKER',4),(341,'Deborah ANTHONIOZ',8),(342,'Olivia NOBS',10),(343,'Scott LAGO',3),(344,'Nicolien SAUERBREIJ',5),(345,'Ekaterina ILYUKHINA',9),(346,'Marion KREINER',6),(347,'Hannah TETER',3),(348,'Jasey Jay ANDERSON',4),(349,'Mathieu BOZZETTO',8),(350,'Magdalena NEUNER',2),(351,'Olga ZAYTSEVA',9),(352,'Simone HAUSWALD',2),(353,'Marie DORIN HABERT',8),(354,'Marie Laure BRUNET',8),(355,'Vincent JAY',8),(356,'Evgeny USTYUGOV',9),(357,'Bjorn FERRY',7),(358,'Christoph SUMANN',6),(359,'Daeheon HWANG',11),(360,'Minjeong CHOI',11),(361,'Min Seok KIM',11),(362,'Jaewon CHUNG',11),(363,'Seung Hoon LEE',11),(364,'Min Kyu CHA',11),(365,'Sungbin YUN',11),(366,'Bo Reum KIM',11),(367,'Sang-Hwa LEE',11),(368,'Tae-Yun KIM',11),(369,'Sangho LEE',11),(370,'Yira SEO',11),(371,'Hyojun LIM',11),(372,'Seung-Hi PARK',11),(373,'Suk Hee SHIM',11),(374,'Yuna KIM',11),(375,'Tae-Bum MO',11),(376,'Si-Baek Seong',11),(377,'Jung-Su LEE',11),(378,'Eun-Byeol Lee',11),(379,'Ho-Suk LEE',11);
/*!40000 ALTER TABLE `athletes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `continent_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Norway','Europe'),(2,'Germany','Europe'),(3,'United States','North America'),(4,'Canada','North America'),(5,'Netherlands','Europe'),(6,'Austria','Europe'),(7,'Sweden','Europe'),(8,'France','Europe'),(9,'Russia','Europe'),(10,'Switzerland','Europe'),(11,'Republic of Korea','Asia');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `sport_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `sport_id` (`sport_id`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,1,'Men\'s Moguls'),(2,1,'Men\'s Freeski Halfpipe'),(3,1,'Men\'s Freeski Big Air'),(4,1,'Men\'s Ski Cross'),(5,1,'Women\'s Freeski Big Air'),(6,1,'Women\'s Moguls'),(7,1,'Women\'s Ski Cross'),(8,1,'Women\'s Aerials'),(9,1,'Women\'s Freeski Halfpipe'),(10,1,'Women\'s Freeski Slopestyle'),(11,1,'Men\'s Freeski Slopestyle'),(12,2,'Women\'s 500m'),(13,2,'Men\'s 500m'),(14,2,'Men\'s 1500m'),(15,2,'Women\'s 1000m'),(16,2,'Women\'s 1500m'),(17,3,'Women\'s Snowboard Halfpipe'),(18,3,'Women\'s Snowboard Cross'),(19,3,'Men\'s Parallel Giant Slalom'),(20,3,'Women\'s Snowboard Big Air'),(21,3,'Men\'s Snowboard Cross'),(22,3,'Men\'s Snowboard Slopestyle'),(23,3,'Men\'s Snowboard Halfpipe'),(24,3,'Women\'s Snowboard Slopestyle'),(25,3,'Women\'s Parallel Giant Slalom'),(26,3,'Men\'s Snowboard Big Air'),(27,4,'Men\'s LH Individual'),(28,4,'Women\'s NH Individual'),(29,4,'Men\'s NH Individual'),(30,5,'Men Single Skating'),(31,6,'Men\'s Singles'),(32,6,'Women\'s Singles'),(33,7,'Men\'s 12.5km Pursuit'),(34,7,'Women\'s 12.5km Mass Start'),(35,7,'Women\'s 10km Pursuit'),(36,7,'Men\'s 10km Sprint'),(37,7,'Women\'s 7.5km Sprint'),(38,7,'Men\'s 15km Mass Start'),(39,7,'Men\'s 20km Individual'),(40,7,'Women\'s 15km Individual'),(41,8,'Men\'s Slalom'),(42,8,'Men\'s Downhill'),(43,8,'Women\'s Super-G'),(44,8,'Men\'s Super-G'),(45,8,'Women\'s Downhill'),(46,8,'Men\'s Alpine Combined'),(47,8,'Men\'s Giant Slalom'),(48,8,'Women\'s Giant Slalom'),(49,8,'Women\'s Alpine Combined'),(50,8,'Women\'s Slalom'),(51,9,'Women'),(52,9,'Men'),(53,10,'Women\'s Sprint Free'),(54,10,'Women\'s 7.5km + 7.5km Skiathlon'),(55,10,'Women\'s 30km Mass Start Free'),(56,10,'Women\'s 10km Classic'),(57,10,'Men\'s 50km Mass Start Free'),(58,10,'Men\'s 15km Classic'),(59,10,'Men\'s Sprint Free'),(60,11,'Men\'s 1500m'),(61,11,'Women\'s 1000m'),(62,11,'Men\'s 1000m'),(63,11,'Women\'s Mass Start'),(64,11,'Men\'s 5000m'),(65,11,'Women\'s 5000m'),(66,11,'Women\'s 3000m'),(67,11,'Men\'s 10000m'),(68,11,'Women\'s 1500m'),(69,11,'Women\'s 500m'),(70,12,'Men Individual Gundersen Normal Hill/10km'),(71,12,'Men Individual Gundersen Large Hill/10km'),(72,13,'Women\'s Monobob'),(73,11,'Men 5000m'),(74,11,'Women Ladies Mass Start'),(75,11,'Men 500m'),(76,11,'Men 10000m'),(77,11,'Men 1500m'),(78,11,'Women Ladies 3000m'),(79,11,'Men 1000m'),(80,11,'Women Ladies 5000m'),(81,11,'Women Ladies 1500m'),(82,11,'Men Mass Start'),(83,11,'Women Ladies 1000m'),(84,7,'Men 20km Individual'),(85,7,'Women 15km Individual'),(86,7,'Men 10km Sprint'),(87,7,'Men 12.5km Pursuit'),(88,7,'Women 7.5km Sprint'),(89,7,'Women 12.5km Mass Start'),(90,7,'Women 10km Pursuit'),(91,7,'Men 15km Mass Start'),(92,3,'Men Snowboard Cross'),(93,3,'Men Big Air'),(94,3,'Women Ladies Slopestyle'),(95,3,'Women Ladies Snowboard Cross'),(96,3,'Women Ladies Halfpipe'),(97,3,'Women Ladies Parallel Giant Slalom'),(98,3,'Men Parallel Giant Slalom'),(99,3,'Men Halfpipe'),(100,3,'Men Slopestyle'),(101,3,'Women Ladies Big Air'),(102,1,'Women Ladies Ski Slopestyle'),(103,1,'Men Ski Halfpipe'),(104,1,'Women Ladies Moguls'),(105,1,'Men Moguls'),(106,1,'Women Ladies Ski Cross'),(107,1,'Women Ladies Ski Halfpipe'),(108,1,'Men Ski Cross'),(109,1,'Men Ski Slopestyle'),(110,5,'Women Ladies\' Single Skating'),(111,4,'Men Large Hill Individual'),(112,4,'Women Ladies Normal Hill Individual'),(113,4,'Men Normal Hill Individual'),(114,14,'Women Ladies 1000m'),(115,14,'Women Ladies 1500m'),(116,14,'Women Ladies 500m'),(117,14,'Men 1000m'),(118,14,'Men 1500m'),(119,8,'Women Ladies Alpine Combined'),(120,8,'Men Alpine Combined'),(121,8,'Men Giant Slalom'),(122,8,'Men Slalom'),(123,8,'Men Super-G'),(124,8,'Women Ladies Slalom'),(125,8,'Women Ladies Giant Slalom'),(126,8,'Women Ladies Downhill'),(127,8,'Men Downhill'),(128,8,'Women Ladies Super-G'),(129,6,'Women Singles'),(130,6,'Men Singles'),(131,10,'Men Sprint Classic'),(132,10,'Men 15km Free'),(133,10,'Women Ladies 10km Free'),(134,10,'Men 15km+15km Skiathlon'),(135,10,'Women Ladies 7.5km+7.5km Skiathlon'),(136,10,'Women Ladies Sprint Classic'),(137,10,'Women Ladies 30km Mass Start Classic'),(138,7,'12.5km pursuit men'),(139,7,'15km mass start men'),(140,7,'20km men'),(141,7,'12.5km mass start women'),(142,7,'10km pursuit women'),(143,7,'10km men'),(144,7,'15km women'),(145,12,'Individual men'),(146,12,'Individual LH men'),(147,8,'super-G women'),(148,8,'super-G men'),(149,8,'alpine combined women'),(150,8,'giant slalom women'),(151,8,'giant slalom men'),(152,8,'downhill men'),(153,8,'downhill women'),(154,8,'slalom women'),(155,8,'alpine combined men'),(156,8,'slalom men'),(157,2,'1500m men'),(158,2,'1000m men'),(159,2,'500m men'),(160,11,'5000m men'),(161,11,'5000m women'),(162,11,'1000m men'),(163,11,'3000m women'),(164,11,'1500m men'),(165,11,'1500m women'),(166,11,'2x500m women'),(167,11,'2x500m men'),(168,11,'10000m men'),(169,11,'1000m women'),(170,10,'Skiathlon 15km 15km men'),(171,10,'Sprint 15km men'),(172,10,'Skiathlon 7.5+7.5km women'),(173,10,'50km men'),(174,10,'15km men'),(175,10,'10km women'),(176,10,'sprint 15km women'),(177,10,'30km women'),(178,5,'Individual women'),(179,5,'Individual men'),(180,4,'Normal Hill Individual men'),(181,4,'Normal Hill Individual women'),(182,3,'Parallel slalom women'),(183,3,'Half-pipe women'),(184,3,'Giant parallel slalom women'),(185,3,'Slopestyle women'),(186,3,'Snowboard Cross women'),(187,3,'Giant parallel slalom men'),(188,3,'Parallel slalom men'),(189,3,'Half-pipe men'),(190,3,'Slopestyle men'),(191,3,'Snowboard Cross men'),(192,9,'Individual men'),(193,9,'Individual women'),(194,6,'Singles women'),(195,6,'Singles men'),(196,1,'Ski Cross men'),(197,1,'Ski Halfpipe men'),(198,1,'Ski Slopestyle women'),(199,1,'Ski Halfpipe women'),(200,1,'Moguls men'),(201,1,'Moguls women'),(202,1,'Ski Slopestyle men'),(203,1,'Ski Cross women'),(204,1,'Aerials men'),(205,8,'alpin combined men'),(206,12,'Individual sprint men'),(207,2,'1000m women'),(208,2,'500m women'),(209,4,'Large Hill Individual men'),(210,10,'Combined 7.5 + 7.5km mass start women'),(211,7,'125km mass start women'),(212,7,'75km women'),(213,7,'125km pursuit men'),(214,1,'Men Men\'s 1500m'),(215,1,'Women Women\'s 1000m'),(216,1,'Women Women\'s 1500m'),(217,1,'Men Men\'s Mass Start'),(218,1,'Men Men\'s 500m'),(219,1,'Men Men'),(220,1,'Women LadiesMass Start'),(221,1,'Men Mens 500m'),(222,1,'Women Ladies500m'),(223,1,'Men Mens 1500m'),(224,1,'Men Mens 1000m'),(225,1,'Men Mens Mass Start'),(226,1,'Men Mens Parallel Giant Slalom'),(227,1,'Women Ladies1500m'),(228,1,'Women 500m women'),(229,1,'Women 1000m women'),(230,1,'Women 1500m women'),(231,1,'Women 2x500m women'),(232,1,'Women Individual women'),(233,1,'Men 1000m men'),(234,1,'Men 10000m men'),(235,1,'Men 2x500m men'),(236,1,'Men 5000m men'),(237,1,'Men 500m men'),(238,1,'Men 1500m men');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medal_summary`
--

DROP TABLE IF EXISTS `medal_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medal_summary` (
  `summary_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `olympic_id` int(11) DEFAULT NULL,
  `bronze` int(11) DEFAULT 0,
  `gold` int(11) DEFAULT 0,
  `silver` int(11) DEFAULT 0,
  `total` int(11) DEFAULT 0,
  PRIMARY KEY (`summary_id`),
  UNIQUE KEY `uk_summary` (`country_id`,`olympic_id`),
  KEY `olympic_id` (`olympic_id`),
  CONSTRAINT `medal_summary_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`),
  CONSTRAINT `medal_summary_ibfk_2` FOREIGN KEY (`olympic_id`) REFERENCES `olympics` (`olympic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medal_summary`
--

LOCK TABLES `medal_summary` WRITE;
/*!40000 ALTER TABLE `medal_summary` DISABLE KEYS */;
INSERT INTO `medal_summary` VALUES (1,1,1,3,5,5,13),(2,1,2,4,3,10,17),(3,1,3,6,11,12,29),(4,1,4,13,11,4,28),(5,2,1,9,3,6,18),(6,2,2,1,5,3,9),(7,2,3,8,7,3,18),(8,2,4,2,7,4,13),(9,3,1,5,8,6,19),(10,3,2,11,7,9,27),(11,3,3,2,6,8,16),(12,3,4,5,5,8,18),(13,4,1,3,4,5,12),(14,4,2,4,10,10,24),(15,4,3,5,8,7,20),(16,4,4,8,1,9,18),(17,5,1,2,2,2,6),(18,5,2,5,6,4,15),(19,5,3,5,6,4,15),(20,5,4,3,6,5,14),(21,6,1,6,4,5,15),(22,6,2,6,6,1,13),(23,6,3,2,4,4,10),(24,6,4,2,6,6,14),(25,7,1,2,2,2,6),(26,7,2,6,5,3,14),(27,7,3,3,3,1,7),(28,7,4,2,6,4,12),(29,8,1,3,10,4,17),(30,8,2,3,4,3,10),(31,8,3,3,6,3,12),(32,8,4,1,4,5,10),(33,9,1,6,4,2,12),(34,9,2,9,6,4,19),(35,10,1,3,1,4,8),(36,10,2,5,1,2,8),(37,10,3,3,4,5,12),(38,10,4,5,7,2,14),(39,11,1,2,6,5,13),(40,11,2,2,2,2,6),(41,11,3,4,4,5,13),(42,11,4,2,2,3,7);
/*!40000 ALTER TABLE `medal_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `olympics`
--

DROP TABLE IF EXISTS `olympics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `olympics` (
  `olympic_id` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`olympic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `olympics`
--

LOCK TABLES `olympics` WRITE;
/*!40000 ALTER TABLE `olympics` DISABLE KEYS */;
INSERT INTO `olympics` VALUES (1,2010,'Vancouver','vancouver-2010'),(2,2014,'Sochi','sochi-2014'),(3,2018,'PyeongChang','pyeongchang-2018'),(4,2022,'Beijing','beijing-2022');
/*!40000 ALTER TABLE `olympics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `result_id` int(11) NOT NULL,
  `athlete_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `olympic_id` int(11) DEFAULT NULL,
  `medal` enum('Gold','Silver','Bronze','None') DEFAULT 'None',
  PRIMARY KEY (`result_id`),
  KEY `athlete_id` (`athlete_id`),
  KEY `event_id` (`event_id`),
  KEY `olympic_id` (`olympic_id`),
  CONSTRAINT `results_ibfk_1` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`athlete_id`),
  CONSTRAINT `results_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  CONSTRAINT `results_ibfk_3` FOREIGN KEY (`olympic_id`) REFERENCES `olympics` (`olympic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,1,1,4,'Silver'),(2,2,1,4,'Gold'),(3,3,2,4,'Silver'),(4,4,2,4,'Bronze'),(5,5,3,4,'Bronze'),(6,6,3,4,'Gold'),(7,7,3,4,'Silver'),(8,8,4,4,'Silver'),(9,9,4,4,'Gold'),(10,10,5,4,'Bronze'),(11,11,5,4,'Silver'),(12,12,6,4,'Silver'),(13,13,7,4,'Silver'),(14,14,7,4,'Gold'),(15,15,7,4,'Bronze'),(16,16,8,4,'Bronze'),(17,17,9,4,'Silver'),(18,18,9,4,'Bronze'),(19,10,10,4,'Gold'),(20,19,11,4,'Silver'),(21,20,11,4,'Bronze'),(22,21,11,4,'Gold'),(23,22,12,4,'Silver'),(24,23,12,4,'Bronze'),(25,24,13,4,'Bronze'),(26,24,14,4,'Silver'),(27,22,15,4,'Gold'),(28,22,16,4,'Bronze'),(29,25,17,4,'Gold'),(30,26,18,4,'Bronze'),(31,27,18,4,'Silver'),(32,28,18,4,'Gold'),(33,29,19,4,'Gold'),(34,30,20,4,'Gold'),(35,31,21,4,'Silver'),(36,32,21,4,'Gold'),(37,33,22,4,'Gold'),(38,34,22,4,'Bronze'),(39,35,23,4,'Bronze'),(40,36,24,4,'Silver'),(41,37,25,4,'Silver'),(42,38,26,4,'Silver'),(43,33,26,4,'Bronze'),(44,39,27,4,'Gold'),(45,40,27,4,'Bronze'),(46,41,28,4,'Silver'),(47,42,29,4,'Silver'),(48,43,30,4,'Gold'),(49,44,31,4,'Silver'),(50,45,31,4,'Gold'),(51,46,32,4,'Silver'),(52,47,32,4,'Gold'),(53,48,33,4,'Gold'),(54,49,33,4,'Silver'),(55,50,34,4,'Silver'),(56,51,34,4,'Gold'),(57,52,34,4,'Bronze'),(58,53,35,4,'Silver'),(59,50,35,4,'Bronze'),(60,52,35,4,'Gold'),(61,48,36,4,'Silver'),(62,54,36,4,'Gold'),(63,49,36,4,'Bronze'),(64,53,37,4,'Silver'),(65,52,37,4,'Gold'),(66,55,38,4,'Silver'),(67,56,38,4,'Bronze'),(68,54,38,4,'Gold'),(69,54,39,4,'Bronze'),(70,48,39,4,'Gold'),(71,57,40,4,'Gold'),(72,58,40,4,'Silver'),(73,52,40,4,'Bronze'),(74,59,41,4,'Gold'),(75,60,41,4,'Silver'),(76,61,41,4,'Bronze'),(77,62,42,4,'Gold'),(78,63,42,4,'Silver'),(79,64,42,4,'Bronze'),(80,65,43,4,'Silver'),(81,66,43,4,'Bronze'),(82,67,43,4,'Gold'),(83,64,44,4,'Gold'),(84,68,44,4,'Bronze'),(85,69,44,4,'Silver'),(86,70,45,4,'Gold'),(87,71,46,4,'Bronze'),(88,60,46,4,'Gold'),(89,68,46,4,'Silver'),(90,72,47,4,'Bronze'),(91,73,47,4,'Gold'),(92,67,48,4,'Bronze'),(93,74,48,4,'Gold'),(94,75,49,4,'Silver'),(95,66,49,4,'Gold'),(96,75,50,4,'Bronze'),(97,76,50,4,'Silver'),(98,77,51,4,'Gold'),(99,78,51,4,'Bronze'),(100,79,52,4,'Silver'),(101,80,52,4,'Gold'),(102,81,53,4,'Silver'),(103,82,53,4,'Bronze'),(104,83,53,4,'Gold'),(105,84,54,4,'Bronze'),(106,85,54,4,'Gold'),(107,82,55,4,'Silver'),(108,85,55,4,'Gold'),(109,85,56,4,'Gold'),(110,86,57,4,'Bronze'),(111,87,58,4,'Bronze'),(112,87,59,4,'Gold'),(113,88,60,4,'Gold'),(114,89,60,4,'Silver'),(115,90,61,4,'Silver'),(116,91,61,4,'Bronze'),(117,92,62,4,'Silver'),(118,93,62,4,'Bronze'),(119,89,62,4,'Gold'),(120,94,63,4,'Silver'),(121,95,63,4,'Gold'),(122,96,64,4,'Silver'),(123,97,64,4,'Gold'),(124,98,64,4,'Bronze'),(125,99,65,4,'Silver'),(126,95,65,4,'Gold'),(127,99,66,4,'Bronze'),(128,95,66,4,'Gold'),(129,97,67,4,'Gold'),(130,96,67,4,'Silver'),(131,100,68,4,'Bronze'),(132,101,68,4,'Gold'),(133,102,69,4,'Gold'),(134,103,70,4,'Silver'),(135,104,70,4,'Gold'),(136,105,70,4,'Bronze'),(137,103,71,4,'Gold'),(138,106,71,4,'Silver'),(139,107,72,4,'Silver'),(140,108,72,4,'Bronze'),(141,109,72,4,'Gold'),(142,110,51,3,'Silver'),(143,111,73,3,'Gold'),(144,112,73,3,'Silver'),(145,113,73,3,'Bronze'),(146,95,74,3,'Bronze'),(147,93,75,3,'Gold'),(148,112,76,3,'Gold'),(149,114,76,3,'Silver'),(150,88,77,3,'Gold'),(151,96,77,3,'Silver'),(152,115,78,3,'Gold'),(153,101,78,3,'Silver'),(154,100,78,3,'Bronze'),(155,88,79,3,'Gold'),(156,93,79,3,'Silver'),(157,116,80,3,'Gold'),(158,101,81,3,'Gold'),(159,117,81,3,'Bronze'),(160,118,82,3,'Bronze'),(161,119,83,3,'Gold'),(162,54,84,3,'Gold'),(163,120,84,3,'Bronze'),(164,121,85,3,'Gold'),(165,122,85,3,'Bronze'),(166,123,86,3,'Gold'),(167,124,87,3,'Gold'),(168,125,87,3,'Silver'),(169,126,87,3,'Bronze'),(170,122,88,3,'Gold'),(171,127,88,3,'Silver'),(172,50,89,3,'Bronze'),(173,122,90,3,'Gold'),(174,128,90,3,'Bronze'),(175,124,91,3,'Gold'),(176,129,91,3,'Silver'),(177,130,91,3,'Bronze'),(178,131,92,3,'Gold'),(179,132,93,3,'Gold'),(180,133,93,3,'Silver'),(181,134,94,3,'Gold'),(182,135,94,3,'Silver'),(183,136,95,3,'Silver'),(184,25,96,3,'Gold'),(185,137,96,3,'Bronze'),(186,138,97,3,'Silver'),(187,139,97,3,'Bronze'),(188,140,98,3,'Gold'),(189,141,99,3,'Gold'),(190,142,100,3,'Gold'),(191,33,100,3,'Silver'),(192,34,100,3,'Bronze'),(193,30,101,3,'Gold'),(194,134,101,3,'Silver'),(195,143,102,3,'Gold'),(196,10,102,3,'Silver'),(197,3,103,3,'Gold'),(198,4,103,3,'Silver'),(199,144,104,3,'Gold'),(200,145,104,3,'Silver'),(201,1,105,3,'Gold'),(202,146,106,3,'Gold'),(203,147,106,3,'Silver'),(204,148,106,3,'Bronze'),(205,17,107,3,'Gold'),(206,149,107,3,'Silver'),(207,150,107,3,'Bronze'),(208,151,108,3,'Gold'),(209,152,108,3,'Silver'),(210,153,109,3,'Gold'),(211,19,109,3,'Silver'),(212,154,109,3,'Bronze'),(213,155,71,3,'Gold'),(214,156,71,3,'Silver'),(215,157,71,3,'Bronze'),(216,157,70,3,'Gold'),(217,158,70,3,'Bronze'),(218,159,110,3,'Bronze'),(219,160,111,3,'Silver'),(221,161,112,3,'Gold'),(222,162,112,3,'Silver'),(223,160,113,3,'Gold'),(224,163,113,3,'Silver'),(226,22,114,3,'Gold'),(227,23,114,3,'Silver'),(228,23,115,3,'Bronze'),(229,164,116,3,'Silver'),(230,23,116,3,'Bronze'),(231,165,117,3,'Gold'),(232,166,117,3,'Silver'),(233,167,118,3,'Silver'),(234,66,119,3,'Gold'),(235,168,119,3,'Silver'),(236,75,119,3,'Bronze'),(237,169,120,3,'Gold'),(238,170,120,3,'Silver'),(239,171,120,3,'Bronze'),(240,169,121,3,'Gold'),(241,172,121,3,'Silver'),(242,170,121,3,'Bronze'),(243,173,122,3,'Gold'),(244,174,122,3,'Silver'),(245,175,122,3,'Bronze'),(246,64,123,3,'Gold'),(247,62,123,3,'Silver'),(248,176,123,3,'Bronze'),(249,177,124,3,'Gold'),(250,75,124,3,'Silver'),(251,178,124,3,'Bronze'),(252,168,125,3,'Gold'),(253,179,125,3,'Silver'),(254,179,126,3,'Silver'),(255,180,126,3,'Bronze'),(256,181,127,3,'Gold'),(257,176,127,3,'Silver'),(258,62,127,3,'Bronze'),(259,182,128,3,'Silver'),(260,47,129,3,'Gold'),(261,183,129,3,'Silver'),(262,184,129,3,'Bronze'),(263,185,130,3,'Gold'),(264,186,130,3,'Silver'),(265,45,130,3,'Bronze'),(266,87,131,3,'Gold'),(267,187,132,3,'Gold'),(268,86,132,3,'Silver'),(269,188,133,3,'Gold'),(270,189,133,3,'Silver'),(271,190,133,3,'Bronze'),(272,86,134,3,'Gold'),(273,191,134,3,'Silver'),(274,192,134,3,'Bronze'),(275,189,135,3,'Gold'),(276,190,135,3,'Silver'),(277,193,136,3,'Gold'),(278,194,136,3,'Silver'),(279,190,137,3,'Gold'),(280,193,137,3,'Bronze'),(281,124,138,2,'Gold'),(282,195,138,2,'Bronze'),(283,130,139,2,'Gold'),(284,124,139,2,'Silver'),(285,124,140,2,'Gold'),(286,196,140,2,'Silver'),(287,197,140,2,'Bronze'),(288,50,141,2,'Bronze'),(289,198,142,2,'Silver'),(290,199,143,2,'Gold'),(291,120,143,2,'Silver'),(292,200,144,2,'Silver'),(293,157,145,2,'Gold'),(294,201,145,2,'Bronze'),(295,103,146,2,'Gold'),(296,202,146,2,'Silver'),(297,156,146,2,'Bronze'),(298,182,147,2,'Gold'),(299,203,147,2,'Silver'),(300,204,147,2,'Bronze'),(301,176,148,2,'Gold'),(302,205,148,2,'Silver'),(303,206,148,2,'Bronze'),(304,207,148,2,'Bronze'),(305,203,149,2,'Gold'),(306,204,149,2,'Silver'),(307,208,149,2,'Bronze'),(308,182,150,2,'Silver'),(309,209,150,2,'Bronze'),(310,210,151,2,'Gold'),(311,211,151,2,'Silver'),(312,170,151,2,'Bronze'),(313,64,152,2,'Gold'),(314,176,152,2,'Bronze'),(315,212,153,2,'Gold'),(316,67,153,2,'Bronze'),(317,168,154,2,'Gold'),(318,213,154,2,'Silver'),(319,214,154,2,'Bronze'),(320,215,155,2,'Gold'),(321,216,156,2,'Gold'),(322,169,156,2,'Silver'),(323,172,156,2,'Bronze'),(324,217,157,2,'Gold'),(325,218,157,2,'Bronze'),(326,218,158,2,'Gold'),(327,219,158,2,'Silver'),(328,167,158,2,'Bronze'),(329,218,159,2,'Gold'),(330,220,159,2,'Bronze'),(331,111,160,2,'Gold'),(332,221,160,2,'Silver'),(333,114,160,2,'Bronze'),(334,101,161,2,'Silver'),(335,222,161,2,'Bronze'),(336,223,162,2,'Gold'),(337,224,162,2,'Silver'),(338,225,162,2,'Bronze'),(339,101,163,2,'Gold'),(340,226,163,2,'Bronze'),(341,118,164,2,'Silver'),(342,224,164,2,'Bronze'),(343,119,165,2,'Gold'),(344,101,165,2,'Silver'),(345,227,165,2,'Bronze'),(346,228,166,2,'Silver'),(347,229,166,2,'Bronze'),(348,225,167,2,'Gold'),(349,230,167,2,'Silver'),(350,231,167,2,'Bronze'),(351,114,168,2,'Gold'),(352,111,168,2,'Silver'),(353,232,168,2,'Bronze'),(354,101,169,2,'Silver'),(355,229,169,2,'Bronze'),(356,187,170,2,'Gold'),(357,233,170,2,'Silver'),(358,191,170,2,'Bronze'),(359,234,171,2,'Gold'),(360,235,171,2,'Silver'),(361,236,171,2,'Bronze'),(362,190,172,2,'Gold'),(363,189,172,2,'Silver'),(364,237,172,2,'Bronze'),(365,238,173,2,'Gold'),(366,239,173,2,'Silver'),(367,240,173,2,'Bronze'),(368,187,174,2,'Gold'),(369,241,174,2,'Silver'),(370,242,174,2,'Bronze'),(371,189,175,2,'Silver'),(372,85,175,2,'Bronze'),(373,194,176,2,'Gold'),(374,243,176,2,'Silver'),(375,190,177,2,'Gold'),(376,85,177,2,'Silver'),(377,244,177,2,'Bronze'),(378,245,178,2,'Gold'),(379,246,179,2,'Silver'),(380,247,180,2,'Bronze'),(381,248,181,2,'Gold'),(382,249,181,2,'Silver'),(383,250,181,2,'Bronze'),(384,251,182,2,'Gold'),(385,252,182,2,'Silver'),(386,253,182,2,'Bronze'),(387,254,183,2,'Gold'),(388,255,183,2,'Bronze'),(389,256,184,2,'Gold'),(390,257,184,2,'Bronze'),(391,134,185,2,'Gold'),(392,258,186,2,'Silver'),(393,27,186,2,'Bronze'),(394,259,187,2,'Gold'),(395,140,187,2,'Silver'),(396,259,188,2,'Gold'),(397,29,188,2,'Bronze'),(398,260,189,2,'Gold'),(399,261,190,2,'Gold'),(400,262,190,2,'Silver'),(401,34,190,2,'Bronze'),(402,131,191,2,'Gold'),(403,263,191,2,'Silver'),(404,264,191,2,'Bronze'),(405,265,192,2,'Gold'),(406,266,192,2,'Bronze'),(407,267,193,2,'Silver'),(408,268,193,2,'Bronze'),(409,47,194,2,'Gold'),(410,269,194,2,'Silver'),(411,270,194,2,'Bronze'),(412,271,195,2,'Gold'),(413,272,195,2,'Silver'),(414,273,196,2,'Gold'),(415,274,196,2,'Silver'),(416,275,196,2,'Bronze'),(417,3,197,2,'Gold'),(418,276,197,2,'Silver'),(419,277,197,2,'Bronze'),(420,278,198,2,'Gold'),(421,279,198,2,'Silver'),(422,280,198,2,'Bronze'),(423,281,199,2,'Gold'),(424,149,199,2,'Silver'),(425,282,200,2,'Gold'),(426,1,200,2,'Silver'),(427,283,200,2,'Bronze'),(428,145,201,2,'Gold'),(429,284,201,2,'Silver'),(430,285,201,2,'Bronze'),(431,286,202,2,'Gold'),(432,287,202,2,'Silver'),(433,19,202,2,'Bronze'),(434,13,203,2,'Gold'),(435,146,203,2,'Silver'),(436,288,203,2,'Bronze'),(437,289,179,1,'Gold'),(438,290,179,1,'Silver'),(439,291,178,1,'Bronze'),(440,292,196,1,'Gold'),(441,293,196,1,'Silver'),(442,294,196,1,'Bronze'),(443,282,200,1,'Gold'),(444,295,200,1,'Bronze'),(445,285,201,1,'Gold'),(446,296,201,1,'Silver'),(447,297,201,1,'Bronze'),(448,298,204,1,'Silver'),(449,299,203,1,'Gold'),(450,300,203,1,'Silver'),(451,301,203,1,'Bronze'),(452,302,161,1,'Silver'),(453,303,161,1,'Bronze'),(454,304,169,1,'Gold'),(455,305,169,1,'Silver'),(456,306,169,1,'Bronze'),(457,307,166,1,'Silver'),(458,308,164,1,'Gold'),(459,309,164,1,'Silver'),(460,310,164,1,'Bronze'),(461,101,165,1,'Gold'),(462,311,165,1,'Silver'),(463,302,163,1,'Silver'),(464,311,163,1,'Bronze'),(465,309,162,1,'Gold'),(466,312,162,1,'Bronze'),(467,313,168,1,'Silver'),(468,232,168,1,'Bronze'),(469,111,160,1,'Gold'),(470,313,160,1,'Bronze'),(471,207,205,1,'Gold'),(472,314,205,1,'Bronze'),(473,181,148,1,'Gold'),(474,207,148,1,'Silver'),(475,205,148,1,'Bronze'),(476,315,147,1,'Gold'),(477,180,147,1,'Bronze'),(478,209,150,1,'Gold'),(479,316,150,1,'Bronze'),(480,317,152,1,'Gold'),(481,181,152,1,'Silver'),(482,207,152,1,'Bronze'),(483,318,151,1,'Gold'),(484,176,151,1,'Silver'),(485,181,151,1,'Bronze'),(486,203,149,1,'Gold'),(487,208,149,1,'Silver'),(488,319,149,1,'Bronze'),(489,203,154,1,'Gold'),(490,213,154,1,'Silver'),(491,173,156,1,'Bronze'),(492,180,153,1,'Gold'),(493,208,153,1,'Silver'),(494,316,153,1,'Bronze'),(495,271,195,1,'Gold'),(496,320,195,1,'Silver'),(497,269,194,1,'Gold'),(498,321,194,1,'Silver'),(499,47,194,1,'Bronze'),(500,322,145,1,'Gold'),(501,323,145,1,'Silver'),(502,324,206,1,'Gold'),(503,323,206,1,'Silver'),(504,325,206,1,'Bronze'),(505,326,192,1,'Gold'),(506,265,192,1,'Bronze'),(507,327,193,1,'Silver'),(508,328,193,1,'Bronze'),(509,329,207,1,'Silver'),(510,217,159,1,'Gold'),(511,330,159,1,'Bronze'),(512,331,157,1,'Silver'),(513,332,157,1,'Bronze'),(514,333,208,1,'Silver'),(515,331,158,1,'Bronze'),(516,334,209,1,'Gold'),(517,335,209,1,'Bronze'),(518,334,180,1,'Gold'),(519,335,180,1,'Bronze'),(520,187,174,1,'Gold'),(521,336,173,1,'Gold'),(522,337,173,1,'Silver'),(523,241,173,1,'Bronze'),(524,190,210,1,'Gold'),(525,338,210,1,'Silver'),(526,339,171,1,'Gold'),(527,340,171,1,'Silver'),(528,336,171,1,'Bronze'),(529,190,176,1,'Gold'),(530,189,175,1,'Gold'),(531,190,175,1,'Bronze'),(532,190,177,1,'Silver'),(533,233,170,1,'Gold'),(534,341,170,1,'Silver'),(535,241,170,1,'Bronze'),(536,342,191,1,'Gold'),(537,343,191,1,'Silver'),(538,344,191,1,'Bronze'),(539,345,186,1,'Gold'),(540,346,186,1,'Silver'),(541,347,186,1,'Bronze'),(542,141,189,1,'Gold'),(543,348,189,1,'Bronze'),(544,349,184,1,'Gold'),(545,350,184,1,'Silver'),(546,351,184,1,'Bronze'),(547,352,183,1,'Silver'),(548,255,183,1,'Bronze'),(549,353,187,1,'Gold'),(550,29,187,1,'Silver'),(551,354,187,1,'Bronze'),(552,198,144,1,'Gold'),(553,355,211,1,'Gold'),(554,356,211,1,'Silver'),(555,357,211,1,'Bronze'),(556,355,212,1,'Silver'),(557,358,212,1,'Bronze'),(558,355,142,1,'Gold'),(559,130,143,1,'Silver'),(560,124,139,1,'Silver'),(561,130,140,1,'Gold'),(562,199,140,1,'Silver'),(563,359,214,4,'Gold'),(564,360,215,4,'Silver'),(565,360,216,4,'Gold'),(566,361,214,4,'Bronze'),(567,362,217,4,'Silver'),(568,363,217,4,'Bronze'),(569,364,218,4,'Silver'),(570,365,219,3,'Gold'),(571,366,220,3,'Silver'),(572,364,221,3,'Silver'),(573,367,222,3,'Silver'),(574,361,223,3,'Bronze'),(575,368,224,3,'Bronze'),(576,363,225,3,'Gold'),(577,369,226,3,'Silver'),(578,360,227,3,'Gold'),(579,370,224,3,'Bronze'),(580,359,221,3,'Silver'),(581,371,221,3,'Bronze'),(582,371,223,3,'Gold'),(583,372,228,2,'Bronze'),(584,372,229,2,'Gold'),(585,373,229,2,'Bronze'),(586,373,230,2,'Silver'),(587,367,231,2,'Gold'),(588,374,232,2,'Silver'),(589,374,232,1,'Gold'),(590,367,231,1,'Gold'),(591,375,233,1,'Silver'),(592,363,234,1,'Gold'),(593,375,235,1,'Gold'),(594,363,236,1,'Silver'),(595,372,229,1,'Bronze'),(596,376,237,1,'Silver'),(597,377,238,1,'Gold'),(598,378,230,1,'Silver'),(599,372,230,1,'Bronze'),(600,377,233,1,'Gold'),(601,379,233,1,'Silver');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sports`
--

DROP TABLE IF EXISTS `sports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sports` (
  `sport_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sports`
--

LOCK TABLES `sports` WRITE;
/*!40000 ALTER TABLE `sports` DISABLE KEYS */;
INSERT INTO `sports` VALUES (1,'Freestyle Skiing','Skiing'),(2,'Short Track Speed Skating','Skating'),(3,'Snowboard','Skiing'),(4,'Ski Jumping','Skiing'),(5,'Figure skating','Other'),(6,'Luge','Sliding'),(7,'Biathlon','Skiing'),(8,'Alpine Skiing','Skiing'),(9,'Skeleton','Sliding'),(10,'Cross Country Skiing','Skiing'),(11,'Speed skating','Other'),(12,'Nordic Combined','Skiing'),(13,'Bobsleigh','Sliding'),(14,'Short Track','Skating');
/*!40000 ALTER TABLE `sports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `country_focus` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `country_focus` (`country_focus`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country_focus`) REFERENCES `countries` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'team02','team02',11);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-06 14:18:35
