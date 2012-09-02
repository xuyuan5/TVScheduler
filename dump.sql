-- MySQL dump 10.13  Distrib 5.1.52, for Win32 (ia32)
--
-- Host: localhost    Database: TVScheduler
-- ------------------------------------------------------
-- Server version	5.1.52-community

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
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channels` (
  `Channel` varchar(10) NOT NULL DEFAULT '',
  `IconURL` text,
  `QueryURL` text,
  `ChannelNumber` int(11) DEFAULT NULL,
  `Tag` varchar(100) DEFAULT NULL,
  `ProgrammingClassName` varchar(100) DEFAULT NULL,
  `ProgrammingTimeClassName` varchar(100) DEFAULT NULL,
  `ProgrammingNameClassName` varchar(100) DEFAULT NULL,
  `ProgrammingEpClassName` varchar(100) DEFAULT NULL,
  `ProgrammingNewEpClassName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Channel`),
  UNIQUE KEY `Channel` (`Channel`),
  UNIQUE KEY `ChannelNumber` (`ChannelNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channels`
--

LOCK TABLES `channels` WRITE;
/*!40000 ALTER TABLE `channels` DISABLE KEYS */;
INSERT INTO `channels` VALUES ('CTV','http://images.zap2it.com/station_logo/cfto.gif','http://affiliate.zap2it.com/tvlistings/ZCSGrid.do',9,'zc-ssl-scrollList','zc-ssl-pg','zc-ssl-pg-time','zc-ssl-pg-title','zc-ssl-pg-ep','zc-ic zc-ic-ne');
/*!40000 ALTER TABLE `channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ignoreregex`
--

DROP TABLE IF EXISTS `ignoreregex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ignoreregex` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Channel` varchar(10) DEFAULT NULL,
  `RegEx` text,
  PRIMARY KEY (`ID`),
  KEY `Channel` (`Channel`),
  CONSTRAINT `ignoreregex_ibfk_1` FOREIGN KEY (`Channel`) REFERENCES `channels` (`Channel`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ignoreregex`
--

LOCK TABLES `ignoreregex` WRITE;
/*!40000 ALTER TABLE `ignoreregex` DISABLE KEYS */;
INSERT INTO `ignoreregex` VALUES (158,'CTV','{<script.*?</script>}s'),(159,'CTV','{href=\"[^\"]*?&.*?\"}s'),(160,'CTV','{<div id=\'zc-topbar-search\'.*?</div>}s'),(161,'CTV','{<div id=\"zc-advsea-limitHD\".*?</div>}s'),(162,'CTV','{<br.*?>}s'),(163,'CTV','{id=\"row(\\d*?Time|Title\\d*)\"}s'),(164,'CTV','{<!.*?>}s');
/*!40000 ALTER TABLE `ignoreregex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queries`
--

DROP TABLE IF EXISTS `queries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queries` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Channel` varchar(10) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Type` varchar(10) DEFAULT NULL,
  `DefaultValue` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Channel` (`Channel`),
  CONSTRAINT `queries_ibfk_1` FOREIGN KEY (`Channel`) REFERENCES `channels` (`Channel`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queries`
--

LOCK TABLES `queries` WRITE;
/*!40000 ALTER TABLE `queries` DISABLE KEYS */;
INSERT INTO `queries` VALUES (117,'CTV','fromTimeInMillis','date-ms',''),(118,'CTV','stnNum','string','10108'),(119,'CTV','channel','int','8');
/*!40000 ALTER TABLE `queries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedules` (
  `Channel` varchar(10) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `EndTime` datetime DEFAULT NULL,
  `User` varchar(100) DEFAULT NULL,
  KEY `Channel` (`Channel`),
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`Channel`) REFERENCES `channels` (`Channel`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable` (
  `Channel` varchar(10) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `StartTime` bigint(20) unsigned DEFAULT NULL,
  `Episode` varchar(255) DEFAULT NULL,
  `IsNewEpisode` tinyint(1) DEFAULT NULL,
  KEY `Channel` (`Channel`),
  CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`Channel`) REFERENCES `channels` (`Channel`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES ('CTV','Dr. Phil',1301943600,'\"Born to Rage?\"',1),('CTV','Oprah Winfrey',1301947200,'\"Best of Oprah: A TV First: Donald Trump & His Five Children\"',0),('CTV','The Dr. Oz Show',1301950800,'\"Are You a Candidate for Weight Loss Surgery?\"',1),('CTV','CTV News',1301954400,'',1),('CTV','etalk',1301958000,'',1),('CTV','The Big Bang Theory',1301959800,'\"The Pants Alternative\"',0),('CTV','Dancing With Stars',1301961600,'',0),('CTV','Castle',1301968860,'\"Slice of Death\"',1),('CTV','CTV National News',1301972400,'',1),('CTV','CTV News',1301974200,'',1),('CTV','Daily Show',1301976300,'\"Billy Crystal\"',1),('CTV','The Colbert Report',1301978100,'\"Andrew Chaikin\"',1),('CTV','Conan',1301980020,'',1),('CTV','TMZ',1301983620,'',1),('CTV','etalk',1301985420,'',0),('CTV','Paid Programming',1301987160,'',0),('CTV','Paid Programming',1301988600,'',0),('CTV','Paid Programming',1301990400,'',0),('CTV','Paid Programming',1301992200,'',0),('CTV','Paid Programming',1301994000,'',0),('CTV','Paid Programming',1301995800,'',0),('CTV','Canada AM',1301997600,'',0),('CTV','Live Regis & Kelly',1302008400,'',1),('CTV','Marilyn Denis',1302012000,'',1),('CTV','The View',1302015600,'',1),('CTV','CTV News',1302019200,'',1),('CTV','etalk',1302022800,'',0),('CTV','Bold/Beautiful',1302024600,'',1),('CTV','Marilyn Denis',1302026400,'',0),('CTV','Dr. Phil',1302030000,'\"Betrayed by Blood\"',1),('CTV','Oprah Winfrey',1302033600,'\"Best of Oprah: Legendary Soap Stars Reunite: Luke & Laura, Plus Erica Kane & ALL Her TV Husbands\"',0),('CTV','The Dr. Oz Show',1302037200,'\"Five Wrong Turns That Lead to Cancer\"',1),('CTV','CTV News',1302040800,'',1),('CTV','etalk',1302044400,'',1),('CTV','The Big Bang Theory',1302046200,'\"The Wheaton Recurrence\"',0),('CTV','No Ordinary Family',1302048000,'\"No Ordinary Beginning\"',1),('CTV','Dancing With Stars',1302051600,'',1),('CTV','Law & Order: SVU',1302055260,'\"Reparations\"',1),('CTV','CTV National News',1302058800,'',1),('CTV','CTV News',1302060600,'',1),('CTV','Daily Show',1302062700,'\"Colin Quinn\"',1),('CTV','The Colbert Report',1302064500,'\"James Franco\"',1),('CTV','Conan',1302066420,'',1),('CTV','TMZ',1302070020,'',1),('CTV','etalk',1302071820,'',0),('CTV','Paid Programming',1302073560,'',0),('CTV','Paid Programming',1302075000,'',0),('CTV','Paid Programming',1302076800,'',0),('CTV','Paid Programming',1302078600,'',0),('CTV','Paid Programming',1302080400,'',0),('CTV','Paid Programming',1302082200,'',0),('CTV','Canada AM',1302084000,'',0),('CTV','Live Regis & Kelly',1302094800,'',1),('CTV','Marilyn Denis',1302098400,'',1),('CTV','The View',1302102000,'',1),('CTV','CTV News',1302105600,'',1),('CTV','etalk',1302109200,'',0),('CTV','Bold/Beautiful',1302111000,'',1),('CTV','Marilyn Denis',1302112800,'',0),('CTV','Dr. Phil',1302116400,'\"When Family Members Attack\"',1),('CTV','Oprah Winfrey',1302120000,'\"Best of Oprah: Oprah & 378 Staffers Go Vegan: The One-Week Challenge\"',0),('CTV','The Dr. Oz Show',1302123600,'\"Couples Show: Shocking Ways Your Hormones Are Affecting Your Marriage, With Dr. John Gray\"',1),('CTV','CTV News',1302127200,'',1),('CTV','etalk',1302130800,'',0),('CTV','The Big Bang Theory',1302132600,'\"The Spaghetti Catalyst\"',0),('CTV','American Idol',1302134400,'\"Nine Finalists Compete\"',1),('CTV','Breaking In',1302139800,'\"Pilot\"',1),('CTV','C.M.: Suspect',1302141600,'\"Night Hawks\"',1),('CTV','CTV National News',1302145200,'',1),('CTV','CTV News',1302147000,'',1),('CTV','Daily Show',1302149100,'\"Mike Huckabee\"',1),('CTV','The Colbert Report',1302150900,'\"Sir David Tang\"',1),('CTV','Conan',1302152820,'',1),('CTV','TMZ',1302156420,'',1),('CTV','etalk',1302158220,'',0),('CTV','Paid Programming',1302159960,'',0),('CTV','Paid Programming',1302161400,'',0),('CTV','Paid Programming',1302163200,'',0),('CTV','Paid Programming',1302165000,'',0),('CTV','Paid Programming',1302166800,'',0),('CTV','Paid Programming',1302168600,'',0),('CTV','Canada AM',1302170400,'',0),('CTV','Live Regis & Kelly',1302181200,'',1),('CTV','Marilyn Denis',1302184800,'',1),('CTV','The View',1302188400,'',1),('CTV','CTV News',1302192000,'',1),('CTV','etalk',1302195600,'',0),('CTV','Bold/Beautiful',1302197400,'',1),('CTV','Marilyn Denis',1302199200,'',0),('CTV','Dr. Phil',1302202800,'\"Trolling, Luring and Taunting: Online and Out-of-Control\"',0),('CTV','Oprah Winfrey',1302206400,'\"All New! The Suburban Mom Who Was a Fugitive for 32 Years\"',1),('CTV','The Dr. Oz Show',1302210000,'\"The Revolutionary Breakthrough in Alzheimer\'s\"',1),('CTV','CTV News',1302213600,'',1),('CTV','CSI: Miami',1302217200,'',0),('CTV','The Big Bang Theory',1302220800,'\"The Herb Garden Germination\"',1),('CTV','Hot in Cleveland',1302222660,'\"LeBron Is Le Gone\"',1),('CTV','CSI: Crime Scene',1302224400,'\"Unleashed\"',1),('CTV','The Mentalist',1302228000,'\"Every Rose Has Its Thorn\"',1),('CTV','CTV National News',1302231600,'',1),('CTV','CTV News',1302233400,'',1),('CTV','Daily Show',1302235500,'\"Jamie Oliver\"',1),('CTV','The Colbert Report',1302237300,'\"Jeff Greenfield\"',1),('CTV','Conan',1302239220,'',0),('CTV','TMZ',1302242820,'',0),('CTV','etalk',1302244620,'',0),('CTV','Paid Programming',1302246360,'',0),('CTV','Paid Programming',1302247800,'',0),('CTV','Paid Programming',1302249600,'',0),('CTV','Paid Programming',1302251400,'',0),('CTV','Paid Programming',1302253200,'',0),('CTV','Paid Programming',1302255000,'',0),('CTV','Canada AM',1302256800,'',0),('CTV','Live Regis & Kelly',1302267600,'',1),('CTV','Marilyn Denis',1302271200,'',1),('CTV','The View',1302274800,'',1),('CTV','CTV News',1302278400,'',1),('CTV','etalk',1302282000,'',0),('CTV','Bold/Beautiful',1302283800,'',1),('CTV','Marilyn Denis',1302285600,'',0),('CTV','Dr. Phil',1302289200,'\"Midlife Crisis or Excuse?\"',1),('CTV','Oprah Winfrey',1302292800,'\"All New! Incredible ``Where Are They Now?\'\' Follow-Ups!\"',1),('CTV','The Dr. Oz Show',1302296400,'\"7 Revolutionary Essentials for Every Woman Over 40\"',0),('CTV','CTV News',1302300000,'',1),('CTV','etalk',1302303600,'',0),('CTV','The Big Bang Theory',1302305400,'\"The Plimpton Stimulation\"',0),('CTV','The Listener',1302307200,'\"Vanished\"',1),('CTV','CSI: NY',1302310800,'\"Food for Thought\"',1),('CTV','Blue Bloods',1302314400,'\"Model Behavior\"',1),('CTV','CTV National News',1302318000,'',1),('CTV','CTV News',1302319800,'',1),('CTV','Criminal Minds',1302321900,'\"Birthright\"',0),('CTV','South Park',1302325620,'\"Imaginationland: Episode III\"',0),('CTV','etalk',1302327420,'',0),('CTV','TMZ',1302329160,'',1),('CTV','Paid Programming',1302330960,'',0),('CTV','Paid Programming',1302332400,'',0),('CTV','Paid Programming',1302334200,'',0),('CTV','Paid Programming',1302336000,'',0),('CTV','Paid Programming',1302337800,'',0),('CTV','Paid Programming',1302339600,'',0),('CTV','Paid Programming',1302341400,'',0),('CTV','Kids at Discovery',1302343200,'\"The Truth is Out There\"',0),('CTV','Kingdom Adventure',1302345000,'\"Once Upon a Dream\"',0),('CTV','Anne of Green Gables',1302346800,'\"Idle Chatter\"',0),('CTV','Anne of Green Gables',1302348600,'\"Bully by the Horns\"',0),('CTV','Kingdom Adventure',1302350400,'\"The Green Spot Plot\"',0),('CTV','The Littlest Hobo',1302352200,'\"Firehorse\"',0),('CTV','Sue Thomas F.B.Eye',1302354000,'\"Greed\"',0),('CTV','SickKids Foundation',1302357600,'',0),('CTV','It Is Written',1302361200,'\"Blessed Are the Merciful\"',1),('CTV','Car/Business',1302363000,'\"Geneva Auto Show 2011\"',1),('CTV','Worst Driver',1302364800,'',0),('CTV','Worst Handyman',1302368400,'\"Finale\"',0),('CTV','Ultimate Africa',1302372000,'',0),('CTV','Sue Thomas F.B.Eye',1302375600,'\"Greed\"',0),('CTV','Corner Gas',1302379200,'\"Block Party\"',0),('CTV','Corner Gas',1302381000,'\"Physical Credit\"',0),('CTV','etalk',1302382800,'',0),('CTV','App Central',1302384600,'',0),('CTV','CTV News',1302386400,'',1),('CTV','W5',1302390000,'\"APA 2011; SIU\"',1),('CTV','The Listener',1302393600,'\"The Magician\"',0),('CTV','C.M.: Suspect',1302397200,'\"Lonely Heart\"',0),('CTV','Law & Order: SVU',1302400800,'\"Possessed\"',0),('CTV','CTV National News',1302404400,'',1),('CTV','CTV News',1302406200,'',1),('CTV','The Exorcist',1302408300,'',0),('CTV','Nicki Minaj My Time',1302417000,'',0),('CTV','The Real World',1302420600,'',0),('CTV','Paid Programming',1302424200,'',0),('CTV','Paid Programming',1302426000,'',0),('CTV','Paid Programming',1302427800,'',0),('CTV','Sue Thomas F.B.Eye',1302429600,'\"Diplomatic Immunity\"',0),('CTV','Sue Thomas F.B.Eye',1302433200,'\"Dirty Bomb\"',0),('CTV','Instant Star',1302436800,'',0),('CTV','Sunday Mass',1302438600,'',0),('CTV','PLAN Canada',1302440400,'\"Spirit for Survival\"',0),('CTV','Living Truth',1302444000,'',0),('CTV','Question Period',1302447600,'',0),('CTV','W5',1302451200,'\"APA 2011; SIU\"',0),('CTV','Gridlock',1302454800,'',0),('CTV','Sue Thomas F.B.Eye',1302458400,'\"Diplomatic Immunity\"',0),('CTV','Sticks and Stones',1302462000,'',0),('CTV','In Fashion',1302469200,'',0),('CTV','Fashion Television',1302471000,'',0),('CTV','CTV News',1302472800,'',1),('CTV','Flashpoint',1302476400,'\"Acceptable Risk\"',0),('CTV','The Amazing Race',1302480000,'',1),('CTV','Undercover Boss',1302483600,'\"Baja Fresh\"',1),('CTV','CSI: Miami',1302487200,'\"Caged\"',1),('CTV','CTV National News',1302490800,'',1),('CTV','CTV News',1302492600,'',1),('CTV','Criminal Minds',1302494700,'\"3rd Life\"',0),('CTV','Important Things',1302498420,'\"Money\"',0),('CTV','Root of All Evil',1302500220,'\"Steroids vs. Boob Jobs\"',0),('CTV','S Silverman Program',1302502020,'\"A Fairly Attractive Mind\"',0),('CTV','Comedy Central Pres.',1302503820,'\"Pete Holmes\"',0),('CTV','Paid Programming',1302505620,'',0),('CTV','Paid Programming',1302507000,'',0),('CTV','Paid Programming',1302508800,'',0),('CTV','Paid Programming',1302510600,'',0),('CTV','Paid Programming',1302512400,'',0),('CTV','Paid Programming',1302514200,'',0),('CTV','Canada AM',1302516000,'',0),('CTV','Live Regis & Kelly',1302526800,'',1),('CTV','Marilyn Denis',1302530400,'',1),('CTV','The View',1302534000,'',1),('CTV','CTV News',1302537600,'',1),('CTV','etalk',1302541200,'',0),('CTV','Bold/Beautiful',1302543000,'',1),('CTV','Marilyn Denis',1302544800,'',0),('CTV','Dr. Phil',1302548400,'',0),('CTV','Oprah Winfrey',1302552000,'',0),('CTV','The Dr. Oz Show',1302555600,'\"Great American Stress Test\"',1),('CTV','CTV News',1302559200,'',1),('CTV','etalk',1302562800,'',0),('CTV','The Big Bang Theory',1302564600,'\"The Staircase Implementation\"',0),('CTV','Dancing With Stars',1302566400,'',1),('CTV','Castle',1302573660,'\"The Dead Pool\"',1),('CTV','CTV National News',1302577200,'',1),('CTV','CTV News',1302579000,'',1),('CTV','Daily Show',1302581100,'',1),('CTV','The Colbert Report',1302582900,'',1),('CTV','Conan',1302584820,'',1),('CTV','TMZ',1302588420,'',1),('CTV','etalk',1302590220,'',0),('CTV','Paid Programming',1302591960,'',0),('CTV','Paid Programming',1302593400,'',0),('CTV','Paid Programming',1302595200,'',0),('CTV','Paid Programming',1302597000,'',0),('CTV','Paid Programming',1302598800,'',0),('CTV','Paid Programming',1302600600,'',0),('CTV','Canada AM',1302602400,'',0),('CTV','Live Regis & Kelly',1302613200,'',1),('CTV','Marilyn Denis',1302616800,'',1),('CTV','The View',1302620400,'',1),('CTV','CTV News',1302624000,'',1),('CTV','etalk',1302627600,'',0),('CTV','Bold/Beautiful',1302629400,'',1),('CTV','Marilyn Denis',1302631200,'',0),('CTV','Dr. Phil',1302634800,'',0),('CTV','Oprah Winfrey',1302638400,'',0),('CTV','The Dr. Oz Show',1302642000,'\"Colleen Returns\"',1),('CTV','CTV News',1302645600,'',1),('CTV','etalk',1302649200,'',0),('CTV','The Big Bang Theory',1302651000,'\"The Lunar Excitation\"',0),('CTV','No Ordinary Family',1302652800,'',0),('CTV','Dancing With Stars',1302656400,'',1),('CTV','Law & Order: SVU',1302660060,'\"Rescue\"',0),('CTV','CTV National News',1302663600,'',1),('CTV','CTV News',1302665400,'',1),('CTV','Daily Show',1302667500,'',1),('CTV','The Colbert Report',1302669300,'',1),('CTV','Conan',1302671220,'',1),('CTV','TMZ',1302674820,'',1),('CTV','etalk',1302676620,'',0),('CTV','Paid Programming',1302678360,'',0),('CTV','Paid Programming',1302679800,'',0),('CTV','Paid Programming',1302681600,'',0),('CTV','Paid Programming',1302683400,'',0),('CTV','Paid Programming',1302685200,'',0),('CTV','Paid Programming',1302687000,'',0),('CTV','Canada AM',1302688800,'',0),('CTV','Live Regis & Kelly',1302699600,'',1),('CTV','Marilyn Denis',1302703200,'',1),('CTV','The View',1302706800,'',1),('CTV','CTV News',1302710400,'',1),('CTV','etalk',1302714000,'',0),('CTV','Bold/Beautiful',1302715800,'',1),('CTV','Marilyn Denis',1302717600,'',0),('CTV','Dr. Phil',1302721200,'',0),('CTV','Oprah Winfrey',1302724800,'',0),('CTV','The Dr. Oz Show',1302728400,'\"28 Days to Prevent a Heart Attack\"',0),('CTV','CTV News',1302732000,'',1),('CTV','etalk',1302735600,'',0),('CTV','The Big Bang Theory',1302737400,'',0),('CTV','American Idol',1302739200,'\"Eight Finalists Compete\"',1),('CTV','Breaking In',1302744600,'\"\'Tis Better to Have Loved and Flossed\"',1),('CTV','C.M.: Suspect',1302746400,'\"Smother\"',1),('CTV','CTV National News',1302750000,'',1),('CTV','CTV News',1302751800,'',1),('CTV','Daily Show',1302753900,'',1),('CTV','The Colbert Report',1302755700,'',1),('CTV','Conan',1302757620,'',1),('CTV','TMZ',1302761220,'',1),('CTV','etalk',1302763020,'',0),('CTV','Paid Programming',1302764760,'',0),('CTV','Paid Programming',1302766200,'',0),('CTV','Paid Programming',1302768000,'',0),('CTV','Paid Programming',1302769800,'',0),('CTV','Paid Programming',1302771600,'',0),('CTV','Paid Programming',1302773400,'',0),('CTV','Canada AM',1302775200,'',0),('CTV','Live Regis & Kelly',1302786000,'',1),('CTV','Marilyn Denis',1302789600,'',1),('CTV','The View',1302793200,'',1),('CTV','CTV News',1302796800,'',1),('CTV','etalk',1302800400,'',0),('CTV','Bold/Beautiful',1302802200,'',1),('CTV','Marilyn Denis',1302804000,'',0),('CTV','Dr. Phil',1302807600,'',0),('CTV','Oprah Winfrey',1302811200,'',0),('CTV','The Dr. Oz Show',1302814800,'\"Cancer Detectives\"',1),('CTV','CTV News',1302818400,'',1),('CTV','CSI: Crime Scene',1302822000,'\"Bump and Grind\"',0),('CTV','The Big Bang Theory',1302825600,'\"The Justice League Recombination\"',0),('CTV','Hot in Cleveland',1302827460,'\"Elka\'s Snowbird\"',1),('CTV','Grey\'s Anatomy',1302829200,'\"P.Y.T. (Pretty Young Thing)\"',0),('CTV','The Mentalist',1302832800,'\"Red Carpet Treatment\"',0),('CTV','CTV National News',1302836400,'',1),('CTV','CTV News',1302838200,'',1),('CTV','Daily Show',1302840300,'',1),('CTV','The Colbert Report',1302842100,'',1),('CTV','Conan',1302844020,'',0),('CTV','TMZ',1302847620,'',0),('CTV','etalk',1302849420,'',0),('CTV','Paid Programming',1302851160,'',0),('CTV','Paid Programming',1302852600,'',0),('CTV','Paid Programming',1302854400,'',0),('CTV','Paid Programming',1302856200,'',0),('CTV','Paid Programming',1302858000,'',0),('CTV','Paid Programming',1302859800,'',0),('CTV','Canada AM',1302861600,'',0),('CTV','Live Regis & Kelly',1302872400,'',1),('CTV','Marilyn Denis',1302876000,'',1),('CTV','The View',1302879600,'',1),('CTV','CTV News',1302883200,'',1),('CTV','etalk',1302886800,'',0),('CTV','Bold/Beautiful',1302888600,'',1),('CTV','Marilyn Denis',1302890400,'',0),('CTV','Dr. Phil',1302894000,'',0),('CTV','Oprah Winfrey',1302897600,'',0),('CTV','The Dr. Oz Show',1302901200,'\"What Would You Do? Dr. Oz Goes Under Cover to See How People Handle Uncomfortable Situations\"',1),('CTV','CTV News',1302904800,'',1),('CTV','etalk',1302908400,'',0),('CTV','The Big Bang Theory',1302910200,'',0),('CTV','The Listener',1302912000,'\"Jericho\"',1),('CTV','CSI: NY',1302915600,'\"Out of the Sky\"',0),('CTV','Blue Bloods',1302919200,'\"Little Fish\"',0),('CTV','CTV National News',1302922800,'',1),('CTV','CTV News',1302924600,'',1),('CTV','Criminal Minds',1302926700,'\"Limelight\"',0),('CTV','South Park',1302930420,'\"Guitar Queer-O\"',0),('CTV','etalk',1302932220,'',0),('CTV','TMZ',1302933960,'',1),('CTV','Paid Programming',1302935760,'',0),('CTV','Paid Programming',1302937200,'',0),('CTV','Paid Programming',1302939000,'',0),('CTV','Paid Programming',1302940800,'',0),('CTV','Paid Programming',1302942600,'',0),('CTV','Paid Programming',1302944400,'',0),('CTV','Paid Programming',1302946200,'',0),('CTV','Kids at Discovery',1302948000,'\"Going Ape\"',0),('CTV','Kingdom Adventure',1302949800,'\"Heart Talk\"',0),('CTV','Anne of Green Gables',1302951600,'\"The Ice Cream Promise\"',0),('CTV','Anne of Green Gables',1302953400,'\"The Sleeves\"',0),('CTV','Kingdom Adventure',1302955200,'\"Battle for the Princess\"',0),('CTV','The Littlest Hobo',1302957000,'\"Lucky\"',0),('CTV','Sue Thomas F.B.Eye',1302958800,'\"The Heist\"',0),('CTV','Nature Conservancy',1302962400,'',0),('CTV','Nature Conservancy',1302964200,'',0),('CTV','It Is Written',1302966000,'\"Ask and It Will Be Given\"',1),('CTV','Car/Business',1302967800,'\"Vancouver; M-B Fuel Cell; Ford Truck; Toyota Truck\"',1),('CTV','Worst Driver',1302969600,'',0),('CTV','Worst Handyman',1302973200,'',0),('CTV','Ultimate Africa',1302976800,'\"Fugitives of Laikipia\"',0),('CTV','Sue Thomas F.B.Eye',1302980400,'\"The Heist\"',0),('CTV','Corner Gas',1302984000,'\"Telescope Trouble\"',0),('CTV','Corner Gas',1302985800,'\"Bean There\"',0),('CTV','etalk',1302987600,'',0),('CTV','App Central',1302989400,'',0),('CTV','CTV News',1302991200,'',1),('CTV','W5',1302994800,'',0),('CTV','The Listener',1302998400,'\"Ace in the Hole\"',0),('CTV','To Be Announced',1303002000,'',0),('CTV','Law & Order: SVU',1303005600,'\"Gray\"',0),('CTV','CTV National News',1303009200,'',1),('CTV','CTV News',1303011000,'',1),('CTV','Doc Hollywood',1303013100,'',0),('CTV','Teen Mom 2',1303020000,'',0),('CTV','The Real World',1303023600,'\"Las Vegas: Stands By Me\"',0),('CTV','Paid Programming',1303027200,'',0),('CTV','Paid Programming',1303029000,'',0),('CTV','Paid Programming',1303030800,'',0),('CTV','Paid Programming',1303032600,'',0),('CTV','Sue Thomas F.B.Eye',1303034400,'\"The Leak\"',0),('CTV','Sue Thomas F.B.Eye',1303038000,'\"Missing\"',0),('CTV','Instant Star',1303041600,'\"Won\'t Get Fooled Again\"',0),('CTV','Sunday Mass',1303043400,'',0),('CTV','PLAN Canada',1303045200,'\"Spirit for Survival\"',0),('CTV','Living Truth',1303048800,'',0),('CTV','Question Period',1303052400,'',0),('CTV','W5',1303056000,'',0),('CTV','Chasing Wild Horses',1303059600,'',0),('CTV','Sue Thomas F.B.Eye',1303063200,'\"The Leak\"',0),('CTV','Plague City: SARS...',1303066800,'',0),('CTV','In Fashion',1303074000,'',0),('CTV','Fashion Television',1303075800,'',0),('CTV','CTV News',1303077600,'',1),('CTV','Undercover Boss',1303081200,'',0),('CTV','The Amazing Race',1303084800,'',1),('CTV','Desperate Housewives',1303088400,'\"Moments in the Woods\"',1),('CTV','CSI: Miami',1303092000,'\"Paint It Black\"',1),('CTV','CTV National News',1303095600,'',1),('CTV','CTV News',1303097400,'',1),('CTV','Criminal Minds',1303099500,'\"Damaged\"',0),('CTV','Important Things',1303103220,'\"2\"',0),('CTV','Root of All Evil',1303105020,'\"Olympic Games vs. Drinking Games\"',0),('CTV','S Silverman Program',1303106820,'\"Songs in the Key of Yuck\"',0),('CTV','Comedy Central Pres.',1303108620,'\"Rob Riggle\"',0),('CTV','Paid Programming',1303110420,'',0),('CTV','Paid Programming',1303111800,'',0),('CTV','Paid Programming',1303113600,'',0),('CTV','Paid Programming',1303115400,'',0),('CTV','Paid Programming',1303117200,'',0),('CTV','Paid Programming',1303119000,'',0),('CTV','Canada AM',1303120800,'',0),('CTV','Live Regis & Kelly',1303131600,'',0),('CTV','Marilyn Denis',1303135200,'',1),('CTV','The View',1303138800,'',0),('CTV','CTV News',1303142400,'',1),('CTV','etalk',1303146000,'',0),('CTV','Bold/Beautiful',1303147800,'',1),('CTV','Marilyn Denis',1303149600,'',0);
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-04-08 17:19:55
