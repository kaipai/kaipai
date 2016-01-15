/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : kaipai

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2016-01-15 16:38:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activity
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `activityID` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `activityName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '活动名称',
  `startTime` int(11) DEFAULT NULL COMMENT '开始时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束时间',
  `storeID` int(11) DEFAULT NULL COMMENT '选送商家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of activity
-- ----------------------------

-- ----------------------------
-- Table structure for activityproduct
-- ----------------------------
DROP TABLE IF EXISTS `activityproduct`;
CREATE TABLE `activityproduct` (
  `activityProductID` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动拍品记录ID',
  `activityID` int(11) DEFAULT NULL COMMENT '活动ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `activityPrice` decimal(9,2) DEFAULT NULL COMMENT '活动价',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activityProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of activityproduct
-- ----------------------------

-- ----------------------------
-- Table structure for ad
-- ----------------------------
DROP TABLE IF EXISTS `ad`;
CREATE TABLE `ad` (
  `adID` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告ID',
  `img` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '广告图片',
  `position` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '广告位置',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`adID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ad
-- ----------------------------

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `articleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `articleCategoryID` int(11) DEFAULT NULL COMMENT '文章分类ID',
  `articleName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '文章名称',
  `articleContent` text COLLATE utf8_general_mysql500_ci COMMENT '文章内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for articlecategory
-- ----------------------------
DROP TABLE IF EXISTS `articlecategory`;
CREATE TABLE `articlecategory` (
  `articleCategoryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章分类ID',
  `categoryName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`articleCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of articlecategory
-- ----------------------------

-- ----------------------------
-- Table structure for artist
-- ----------------------------
DROP TABLE IF EXISTS `artist`;
CREATE TABLE `artist` (
  `artistID` int(11) NOT NULL AUTO_INCREMENT COMMENT '名家ID',
  `artistName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '名家姓名',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '擅长的排民',
  `shortDesc` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '头衔介绍',
  `artistDetail` text COLLATE utf8_general_mysql500_ci COMMENT '艺术家个人介绍',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`artistID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of artist
-- ----------------------------

-- ----------------------------
-- Table structure for auctionlog
-- ----------------------------
DROP TABLE IF EXISTS `auctionlog`;
CREATE TABLE `auctionlog` (
  `auctionLogID` int(11) NOT NULL AUTO_INCREMENT COMMENT '竞拍记录ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '竞拍人',
  `nickName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '竞拍人昵称',
  `autctionPrice` decimal(9,2) DEFAULT NULL COMMENT '出价',
  `instime` int(11) DEFAULT NULL COMMENT '出价时间',
  PRIMARY KEY (`auctionLogID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of auctionlog
-- ----------------------------

-- ----------------------------
-- Table structure for auctionmember
-- ----------------------------
DROP TABLE IF EXISTS `auctionmember`;
CREATE TABLE `auctionmember` (
  `auctionMemberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '竞拍人关系ID',
  `productID` int(11) DEFAULT NULL COMMENT '产品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auctionMemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of auctionmember
-- ----------------------------

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `memberName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '密码',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for memberarticle
-- ----------------------------
DROP TABLE IF EXISTS `memberarticle`;
CREATE TABLE `memberarticle` (
  `memberArticleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章ID',
  `memberArticleName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '用户文章名称',
  `memberArticleContent` text COLLATE utf8_general_mysql500_ci COMMENT '用户文章内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberarticle
-- ----------------------------

-- ----------------------------
-- Table structure for memberarticlecomment
-- ----------------------------
DROP TABLE IF EXISTS `memberarticlecomment`;
CREATE TABLE `memberarticlecomment` (
  `memberArticleCommentID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章评论ID',
  `memberArticleID` int(11) DEFAULT NULL COMMENT '用户文章ID',
  `commentContent` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '评论内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberarticlecomment
-- ----------------------------

-- ----------------------------
-- Table structure for memberarticlemark
-- ----------------------------
DROP TABLE IF EXISTS `memberarticlemark`;
CREATE TABLE `memberarticlemark` (
  `memberArticleMarkID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章收藏ID',
  `memberID` int(11) DEFAULT NULL COMMENT '收藏人的用户ID',
  `memberArticleID` int(11) DEFAULT NULL COMMENT '用户文章ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleMarkID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberarticlemark
-- ----------------------------

-- ----------------------------
-- Table structure for membercomment
-- ----------------------------
DROP TABLE IF EXISTS `membercomment`;
CREATE TABLE `membercomment` (
  `memberCommentID` int(11) NOT NULL COMMENT '用户评论',
  `memberCommentContent` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '用户评论内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of membercomment
-- ----------------------------

-- ----------------------------
-- Table structure for memberinfo
-- ----------------------------
DROP TABLE IF EXISTS `memberinfo`;
CREATE TABLE `memberinfo` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '昵称',
  `signature` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '签名',
  `avatar` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '头像',
  `email` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT 'email地址',
  `qq` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT 'qq号',
  `wechat` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '微信号',
  `selfDesc` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '个人描述',
  `mobile` varchar(11) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '绑定手机',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberinfo
-- ----------------------------

-- ----------------------------
-- Table structure for membermessage
-- ----------------------------
DROP TABLE IF EXISTS `membermessage`;
CREATE TABLE `membermessage` (
  `messageID` int(11) NOT NULL COMMENT '站内信ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `content` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '站内信内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of membermessage
-- ----------------------------

-- ----------------------------
-- Table structure for memberorder
-- ----------------------------
DROP TABLE IF EXISTS `memberorder`;
CREATE TABLE `memberorder` (
  `orderID` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `businessID` varchar(16) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '业务流水号',
  `unitePayID` varchar(16) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '支付号',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `productSnapshot` text COLLATE utf8_general_mysql500_ci COMMENT '拍品快照',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberorder
-- ----------------------------

-- ----------------------------
-- Table structure for memberorderdelivery
-- ----------------------------
DROP TABLE IF EXISTS `memberorderdelivery`;
CREATE TABLE `memberorderdelivery` (
  `deliveryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '配送ID',
  `orderID` int(11) DEFAULT NULL COMMENT '订单ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deliveryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberorderdelivery
-- ----------------------------

-- ----------------------------
-- Table structure for memberpaydetail
-- ----------------------------
DROP TABLE IF EXISTS `memberpaydetail`;
CREATE TABLE `memberpaydetail` (
  `unitePayID` varchar(16) COLLATE utf8_general_mysql500_ci NOT NULL COMMENT '支付号',
  `payMoney` decimal(9,2) DEFAULT NULL COMMENT '需要支付的金额',
  `paidMoney` decimal(9,2) DEFAULT NULL COMMENT '已支付的金额',
  `payStatus` tinyint(1) DEFAULT NULL COMMENT '支付状态',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`unitePayID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberpaydetail
-- ----------------------------

-- ----------------------------
-- Table structure for memberproductinterest
-- ----------------------------
DROP TABLE IF EXISTS `memberproductinterest`;
CREATE TABLE `memberproductinterest` (
  `productInterestID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品关注记录ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`productInterestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberproductinterest
-- ----------------------------

-- ----------------------------
-- Table structure for memberstoreinterest
-- ----------------------------
DROP TABLE IF EXISTS `memberstoreinterest`;
CREATE TABLE `memberstoreinterest` (
  `storeInterestID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家关注记录ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `storeID` int(11) DEFAULT NULL COMMENT '商家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`storeInterestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberstoreinterest
-- ----------------------------

-- ----------------------------
-- Table structure for memberzone
-- ----------------------------
DROP TABLE IF EXISTS `memberzone`;
CREATE TABLE `memberzone` (
  `zoneID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户空间ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `zoneName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '空间名称',
  `zoneSignature` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '空间签名',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zoneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of memberzone
-- ----------------------------

-- ----------------------------
-- Table structure for paynotifylog
-- ----------------------------
DROP TABLE IF EXISTS `paynotifylog`;
CREATE TABLE `paynotifylog` (
  `logID` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `unitePayID` varchar(32) DEFAULT NULL COMMENT '订单支付号',
  `money` decimal(9,2) DEFAULT '0.00' COMMENT '支付金额',
  `payNotifyInfo` text COMMENT '订单通知信息',
  `postData` text COMMENT 'post数据',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`logID`)
) ENGINE=MyISAM AUTO_INCREMENT=25505 DEFAULT CHARSET=utf8 COMMENT='支付通知日志\r\nsource: pay';

-- ----------------------------
-- Records of paynotifylog
-- ----------------------------

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `productName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '拍品名称',
  `listImg` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '列表图片',
  `productDetail` text COLLATE utf8_general_mysql500_ci COMMENT '产品介绍',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '拍品分类ID',
  `startPrice` decimal(9,2) DEFAULT NULL COMMENT '起拍价',
  `currPrice` decimal(9,2) DEFAULT NULL COMMENT '当前价格',
  `depositPrice` decimal(9,2) DEFAULT NULL COMMENT '保证金',
  `startTime` int(11) DEFAULT NULL COMMENT '起拍时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束时间',
  `auctionPerPrice` decimal(9,2) DEFAULT NULL COMMENT '单次出价',
  `coverDelivery` tinyint(1) DEFAULT '0' COMMENT '是否 包邮',
  `refundInDays` tinyint(1) DEFAULT '0' COMMENT '三日内可退',
  `certificateCard` tinyint(1) DEFAULT '0' COMMENT '是否有鉴定证书',
  `authorAuth` tinyint(1) DEFAULT '0' COMMENT '作者授权',
  `artistID` int(11) DEFAULT NULL COMMENT '艺术家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of product
-- ----------------------------

-- ----------------------------
-- Table structure for productcategory
-- ----------------------------
DROP TABLE IF EXISTS `productcategory`;
CREATE TABLE `productcategory` (
  `productCategoryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品分类ID',
  `categoryName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '分类名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`productCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of productcategory
-- ----------------------------

-- ----------------------------
-- Table structure for productcategoryfilter
-- ----------------------------
DROP TABLE IF EXISTS `productcategoryfilter`;
CREATE TABLE `productcategoryfilter` (
  `productCategoryFilterID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品分类筛选ID',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '产品分类ID',
  `filterName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '筛选名称',
  PRIMARY KEY (`productCategoryFilterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of productcategoryfilter
-- ----------------------------

-- ----------------------------
-- Table structure for productcategoryfilteroption
-- ----------------------------
DROP TABLE IF EXISTS `productcategoryfilteroption`;
CREATE TABLE `productcategoryfilteroption` (
  `productCategoryFilterOptionID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品分类筛选选项ID',
  `productCategoryFilterID` int(11) DEFAULT NULL COMMENT '产品分类筛选ID',
  `optionName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '筛选选项名称',
  PRIMARY KEY (`productCategoryFilterOptionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of productcategoryfilteroption
-- ----------------------------

-- ----------------------------
-- Table structure for productfilteroption
-- ----------------------------
DROP TABLE IF EXISTS `productfilteroption`;
CREATE TABLE `productfilteroption` (
  `productFilterOptionID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品筛选选项ID',
  `productID` int(11) DEFAULT NULL,
  `productCategoryFilterID` int(11) DEFAULT NULL COMMENT '产品分类筛选ID',
  `productCategoryFilterOptionID` int(11) DEFAULT NULL COMMENT '产品分类选项ID',
  PRIMARY KEY (`productFilterOptionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of productfilteroption
-- ----------------------------

-- ----------------------------
-- Table structure for store
-- ----------------------------
DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `storeID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家ID',
  `storeName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '商家名称',
  `storeDesc` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '商家介绍',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`storeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of store
-- ----------------------------
