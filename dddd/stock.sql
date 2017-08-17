/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 100108
Source Host           : localhost:3306
Source Database       : stock

Target Server Type    : MYSQL
Target Server Version : 100108
File Encoding         : 65001

Date: 2017-08-17 20:07:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lists
-- ----------------------------
DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `addtime` date NOT NULL,
  `num` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1为入库 0为出库',
  PRIMARY KEY (`id`),
  KEY `清单类型` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for main
-- ----------------------------
DROP TABLE IF EXISTS `main`;
CREATE TABLE `main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '中文名字',
  `mark` varchar(100) NOT NULL COMMENT '供应商货号',
  `image` varchar(255) NOT NULL,
  `huohao` varchar(50) NOT NULL,
  `color` varchar(30) NOT NULL,
  `sizes` varchar(30) NOT NULL,
  `num` int(11) NOT NULL,
  `urls` text NOT NULL COMMENT '参考链接',
  `desc` text NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
