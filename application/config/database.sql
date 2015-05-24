-- --------------------------------------------------------
-- Host:                         192.168.33.10
-- Server versie:                5.5.41-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Versie:              9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Databasestructuur van mentoraat wordt geschreven
CREATE DATABASE IF NOT EXISTS `mentoraat` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mentoraat`;


-- Structuur van  tabel mentoraat.admins wordt geschreven
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_admins_userid` (`user_id`),
  CONSTRAINT `FK_admins_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.admins: ~1 rows (ongeveer)
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.config wordt geschreven
CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(25) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.config: ~2 rows (ongeveer)
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`key`, `value`) VALUES
	('REGISTRATION_STATUS', 'Open'),
	('PREFERENCES_STATUS', 'Open');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.failedlogins wordt geschreven
CREATE TABLE IF NOT EXISTS `failedlogins` (
  `netid` varchar(14) NOT NULL,
  `times` int(11) NOT NULL,
  PRIMARY KEY (`netid`),
  CONSTRAINT `FK__users` FOREIGN KEY (`netid`) REFERENCES `users` (`netid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.failedlogins: ~0 rows (ongeveer)
/*!40000 ALTER TABLE `failedlogins` DISABLE KEYS */;
/*!40000 ALTER TABLE `failedlogins` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.preferences wordt geschreven
CREATE TABLE IF NOT EXISTS `preferences` (
  `studentid` varchar(14) NOT NULL,
  `prefers_studentid` varchar(14) NOT NULL,
  `order` int(11) NOT NULL,
  UNIQUE KEY `unique_studentid_preferences_order` (`studentid`,`order`),
  UNIQUE KEY `unique_studentid_preferences_studentid` (`studentid`,`prefers_studentid`),
  KEY `FK_preferences_students` (`prefers_studentid`),
  CONSTRAINT `FK_preferences_students` FOREIGN KEY (`prefers_studentid`) REFERENCES `students` (`netid`),
  CONSTRAINT `FK_preferences_users` FOREIGN KEY (`studentid`) REFERENCES `users` (`netid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.preferences: ~0 rows (ongeveer)
/*!40000 ALTER TABLE `preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `preferences` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.students wordt geschreven
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `netid` varchar(14) NOT NULL,
  `studentid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `netid` (`netid`),
  KEY `studentid` (`studentid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.students: ~4 rows (ongeveer)
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.team_roles wordt geschreven
CREATE TABLE IF NOT EXISTS `team_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `netid` varchar(14) NOT NULL DEFAULT '0',
  `role` enum('Analyst','Chairman','Completer','Driver','Executive','Expert','Explorer','Innovater','TeamPlayer') NOT NULL DEFAULT 'Analyst',
  `percentage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`netid`,`role`),
  CONSTRAINT `FK_team_role_users` FOREIGN KEY (`netid`) REFERENCES `users` (`netid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.team_roles: ~0 rows (ongeveer)
/*!40000 ALTER TABLE `team_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_roles` ENABLE KEYS */;


-- Structuur van  tabel mentoraat.users wordt geschreven
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `netid` varchar(14) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_netid_students_netid` (`netid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumpen data van tabel mentoraat.users: ~1 rows (ongeveer)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
