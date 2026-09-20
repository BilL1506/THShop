-- TalesRunnerShop - MySQL/MariaDB schema for the WEB database.
-- The GAME database is also MySQL/MariaDB and is expected to already contain the TalesRunner game tables.
-- Recommended: MySQL 5.7+/8.0+ or MariaDB 10.4+.

CREATE DATABASE IF NOT EXISTS `talesrunner_web`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `talesrunner_web`;

CREATE TABLE IF NOT EXISTS `Web_User` (
  `fdUserID` varchar(64) NOT NULL,
  `fdPoint` bigint NOT NULL DEFAULT 0,
  `fdEXP_VIP` bigint NOT NULL DEFAULT 0,
  `fdAdmin` tinyint NOT NULL DEFAULT 0,
  `MEETA_Account` varchar(128) DEFAULT NULL,
  `fdProfile_Picture` varchar(1024) DEFAULT NULL,
  `fdRank` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`fdUserID`),
  KEY `idx_meeta_account` (`MEETA_Account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_ItemShopMenu` (
  `MenuNum` int NOT NULL AUTO_INCREMENT,
  `MenuName` text NOT NULL,
  PRIMARY KEY (`MenuNum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_ItemShop` (
  `Num` bigint NOT NULL AUTO_INCREMENT,
  `ItemNum` bigint NOT NULL,
  `ItemName` text NOT NULL,
  `ItemPrice` bigint NOT NULL DEFAULT 0,
  `ItemMenu` int NOT NULL DEFAULT 0,
  `ItemDelete` tinyint NOT NULL DEFAULT 0,
  `ItemLimit` tinyint NOT NULL DEFAULT 0,
  `ItemCount` bigint NOT NULL DEFAULT 0,
  `ItemVIP` int NOT NULL DEFAULT 0,
  `ItemDateTime` tinyint NOT NULL DEFAULT 0,
  `Item_DateStart` varchar(64) DEFAULT NULL,
  `Item_DateEnd` varchar(64) DEFAULT NULL,
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ItemHot` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`Num`),
  KEY `idx_item_menu` (`ItemMenu`),
  KEY `idx_item_deleted` (`ItemDelete`),
  KEY `idx_item_hot` (`ItemHot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Log_BuyItems` (
  `Num` bigint NOT NULL AUTO_INCREMENT,
  `ItemOrder` bigint NOT NULL,
  `ItemNum` bigint NOT NULL,
  `ItemPrice` bigint NOT NULL DEFAULT 0,
  `UserID` varchar(64) NOT NULL,
  `UserPoint_Before` bigint NOT NULL DEFAULT 0,
  `UserPoint_After` bigint NOT NULL DEFAULT 0,
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Num`),
  KEY `idx_buy_user` (`UserID`),
  KEY `idx_buy_date` (`DateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `LogTopup` (
  `Num` bigint NOT NULL AUTO_INCREMENT,
  `Password` varchar(64) NOT NULL,
  `Account_ID` varchar(64) NOT NULL,
  `Amount` bigint NOT NULL DEFAULT 0,
  `Status` int NOT NULL DEFAULT 0,
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Num`),
  KEY `idx_topup_password` (`Password`),
  KEY `idx_topup_account` (`Account_ID`),
  KEY `idx_topup_status_date` (`Status`,`DateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_VIP` (
  `No` int NOT NULL,
  `Exp` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_VIP_Reward` (
  `Num` bigint NOT NULL AUTO_INCREMENT,
  `VIP_Num` int NOT NULL,
  `VIP_Value` bigint NOT NULL DEFAULT 0,
  `VIP_Type` int NOT NULL DEFAULT 0,
  `VIP_ItemDesc` text DEFAULT NULL,
  PRIMARY KEY (`Num`),
  KEY `idx_vip_reward_level` (`VIP_Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_VIP_RewardLog` (
  `Num` bigint NOT NULL AUTO_INCREMENT,
  `UserNum` bigint NOT NULL,
  `VIP_Num` int NOT NULL,
  `VIP_Value` bigint NOT NULL DEFAULT 0,
  `VIP_Type` int NOT NULL DEFAULT 0,
  `DateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Num`),
  UNIQUE KEY `uq_vip_reward_user_level` (`UserNum`,`VIP_Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_Download` (
  `Num` int NOT NULL AUTO_INCREMENT,
  `FileTitle` varchar(255) NOT NULL,
  `FileDescription` text DEFAULT NULL,
  `FileURL` text NOT NULL,
  PRIMARY KEY (`Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Character` (
  `Character_Num` int NOT NULL,
  `Character_Name2` varchar(255) DEFAULT NULL,
  `Character_Image` text DEFAULT NULL,
  PRIMARY KEY (`Character_Num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Web_AlchemistMenu` (
  `Sub` int NOT NULL,
  `Name` text NOT NULL,
  `Icon` text DEFAULT NULL,
  PRIMARY KEY (`Sub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Webboard_Category` (
  `Num` int NOT NULL AUTO_INCREMENT,
  `URL` varchar(255) NOT NULL,
  `Title` text NOT NULL,
  PRIMARY KEY (`Num`),
  UNIQUE KEY `uq_webboard_category_url` (`URL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default VIP thresholds inferred from the website's getVIP() logic.
INSERT INTO `Web_VIP` (`No`,`Exp`) VALUES
(0,0),(1,5000),(2,20000),(3,35000),(4,50000),(5,80000),
(6,100000),(7,130000),(8,150000),(9,170000),(10,200000)
ON DUPLICATE KEY UPDATE `Exp`=VALUES(`Exp`);
