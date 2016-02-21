/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50534
Source Host           : localhost:3306
Source Database       : kaipai

Target Server Type    : MYSQL
Target Server Version : 50534
File Encoding         : 65001

Date: 2016-02-21 14:09:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `Ad`
-- ----------------------------
DROP TABLE IF EXISTS `Ad`;
CREATE TABLE `Ad` (
  `adID` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告ID',
  `img` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '广告图片',
  `position` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '广告位置',
  `alt` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '广告文字',
  `type` tinyint(1) DEFAULT '1' COMMENT '广告类型【1：轮播】',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`adID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Ad
-- ----------------------------

-- ----------------------------
-- Table structure for `Article`
-- ----------------------------
DROP TABLE IF EXISTS `Article`;
CREATE TABLE `Article` (
  `articleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `articleCategoryID` int(11) DEFAULT NULL COMMENT '文章分类ID',
  `articleName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '文章名称',
  `articleContent` text COLLATE utf8_general_mysql500_ci COMMENT '文章内容',
  `type` tinyint(4) DEFAULT '1' COMMENT '文章类型',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`articleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Article
-- ----------------------------

-- ----------------------------
-- Table structure for `ArticleCategory`
-- ----------------------------
DROP TABLE IF EXISTS `ArticleCategory`;
CREATE TABLE `ArticleCategory` (
  `articleCategoryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章分类ID',
  `categoryName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '分类名称',
  PRIMARY KEY (`articleCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ArticleCategory
-- ----------------------------

-- ----------------------------
-- Table structure for `Artist`
-- ----------------------------
DROP TABLE IF EXISTS `Artist`;
CREATE TABLE `Artist` (
  `artistID` int(11) NOT NULL AUTO_INCREMENT COMMENT '名家ID',
  `artistName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '名家姓名',
  `artistAvatar` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '艺术家头像',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '擅长的排民',
  `shortDesc` text COLLATE utf8_general_mysql500_ci COMMENT '头衔介绍',
  `artistDetail` text COLLATE utf8_general_mysql500_ci COMMENT '艺术家个人介绍',
  `productions` text COLLATE utf8_general_mysql500_ci COMMENT '作品欣赏',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`artistID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Artist
-- ----------------------------

-- ----------------------------
-- Table structure for `AuctionLog`
-- ----------------------------
DROP TABLE IF EXISTS `AuctionLog`;
CREATE TABLE `AuctionLog` (
  `auctionLogID` int(11) NOT NULL AUTO_INCREMENT COMMENT '竞拍记录ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '竞拍人',
  `nickName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '竞拍人昵称',
  `autctionPrice` decimal(9,2) DEFAULT NULL COMMENT '出价',
  `instime` int(11) DEFAULT NULL COMMENT '出价时间',
  PRIMARY KEY (`auctionLogID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of AuctionLog
-- ----------------------------

-- ----------------------------
-- Table structure for `AuctionMember`
-- ----------------------------
DROP TABLE IF EXISTS `AuctionMember`;
CREATE TABLE `AuctionMember` (
  `auctionMemberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '竞拍人关系ID',
  `productID` int(11) DEFAULT NULL COMMENT '产品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auctionMemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of AuctionMember
-- ----------------------------

-- ----------------------------
-- Table structure for `Comment`
-- ----------------------------
DROP TABLE IF EXISTS `Comment`;
CREATE TABLE `Comment` (
  `commentID` int(11) NOT NULL COMMENT '用户评论',
  `commenterName` varchar(25) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '留言者姓名',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `commentTitle` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '留言标题',
  `commentContent` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '留言内容',
  `replyContent` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '回复内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Comment
-- ----------------------------

-- ----------------------------
-- Table structure for `Member`
-- ----------------------------
DROP TABLE IF EXISTS `Member`;
CREATE TABLE `Member` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `mobile` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '密码',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Member
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberArticle`
-- ----------------------------
DROP TABLE IF EXISTS `MemberArticle`;
CREATE TABLE `MemberArticle` (
  `memberArticleID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章ID',
  `memberArticleName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '用户文章名称',
  `memberArticleContent` text COLLATE utf8_general_mysql500_ci COMMENT '用户文章内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberArticle
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberArticleComment`
-- ----------------------------
DROP TABLE IF EXISTS `MemberArticleComment`;
CREATE TABLE `MemberArticleComment` (
  `memberArticleCommentID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章评论ID',
  `memberArticleID` int(11) DEFAULT NULL COMMENT '用户文章ID',
  `commentContent` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '评论内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleCommentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberArticleComment
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberArticleMark`
-- ----------------------------
DROP TABLE IF EXISTS `MemberArticleMark`;
CREATE TABLE `MemberArticleMark` (
  `memberArticleMarkID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户文章收藏ID',
  `memberID` int(11) DEFAULT NULL COMMENT '收藏人的用户ID',
  `memberArticleID` int(11) DEFAULT NULL COMMENT '用户文章ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberArticleMarkID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberArticleMark
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberInfo`
-- ----------------------------
DROP TABLE IF EXISTS `MemberInfo`;
CREATE TABLE `MemberInfo` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '昵称',
  `signature` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '签名',
  `avatar` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '头像',
  `email` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT 'email地址',
  `qq` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT 'qq号',
  `wechat` varchar(20) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '微信号',
  `selfDesc` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '个人描述',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberInfo
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberMessage`
-- ----------------------------
DROP TABLE IF EXISTS `MemberMessage`;
CREATE TABLE `MemberMessage` (
  `messageID` int(11) NOT NULL COMMENT '站内信ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `content` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '站内信内容',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberMessage
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberOrder`
-- ----------------------------
DROP TABLE IF EXISTS `MemberOrder`;
CREATE TABLE `MemberOrder` (
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
-- Records of MemberOrder
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberOrderDelivery`
-- ----------------------------
DROP TABLE IF EXISTS `MemberOrderDelivery`;
CREATE TABLE `MemberOrderDelivery` (
  `deliveryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '配送ID',
  `orderID` int(11) DEFAULT NULL COMMENT '订单ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `type` tinyint(4) DEFAULT '1' COMMENT '派送方式',
  `receiverName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '收货人姓名',
  `receiverMobile` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '收货人电话',
  `receiverAddr` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '收货人地址',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deliveryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberOrderDelivery
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberPayDetail`
-- ----------------------------
DROP TABLE IF EXISTS `MemberPayDetail`;
CREATE TABLE `MemberPayDetail` (
  `unitePayID` varchar(16) COLLATE utf8_general_mysql500_ci NOT NULL COMMENT '支付号',
  `payMoney` decimal(9,2) DEFAULT NULL COMMENT '需要支付的金额',
  `paidMoney` decimal(9,2) DEFAULT NULL COMMENT '已支付的金额',
  `payStatus` tinyint(1) DEFAULT NULL COMMENT '支付状态',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`unitePayID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberPayDetail
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberProductInterest`
-- ----------------------------
DROP TABLE IF EXISTS `MemberProductInterest`;
CREATE TABLE `MemberProductInterest` (
  `productInterestID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品关注记录ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`productInterestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberProductInterest
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberStoreInterest`
-- ----------------------------
DROP TABLE IF EXISTS `MemberStoreInterest`;
CREATE TABLE `MemberStoreInterest` (
  `storeInterestID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家关注记录ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `storeID` int(11) DEFAULT NULL COMMENT '商家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`storeInterestID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberStoreInterest
-- ----------------------------

-- ----------------------------
-- Table structure for `MemberZone`
-- ----------------------------
DROP TABLE IF EXISTS `MemberZone`;
CREATE TABLE `MemberZone` (
  `zoneID` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户空间ID',
  `memberID` int(11) DEFAULT NULL COMMENT '用户ID',
  `zoneName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '空间名称',
  `zoneSignature` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '空间签名',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zoneID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of MemberZone
-- ----------------------------

-- ----------------------------
-- Table structure for `MobileVerifyCode`
-- ----------------------------
DROP TABLE IF EXISTS `MobileVerifyCode`;
CREATE TABLE `MobileVerifyCode` (
  `codeID` int(11) NOT NULL AUTO_INCREMENT COMMENT '验证码ID',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `verifyCode` char(10) DEFAULT NULL COMMENT '验证码',
  `expireTime` int(11) DEFAULT NULL COMMENT '过期时间',
  `isUsed` tinyint(4) DEFAULT '0' COMMENT '是否使用过',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`codeID`)
) ENGINE=MyISAM AUTO_INCREMENT=70288 DEFAULT CHARSET=utf8 COMMENT='手机验证码';

-- ----------------------------
-- Records of MobileVerifyCode
-- ----------------------------

-- ----------------------------
-- Table structure for `PaynotifyLog`
-- ----------------------------
DROP TABLE IF EXISTS `PaynotifyLog`;
CREATE TABLE `PaynotifyLog` (
  `logID` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `unitePayID` varchar(32) DEFAULT NULL COMMENT '订单支付号',
  `money` decimal(9,2) DEFAULT '0.00' COMMENT '支付金额',
  `payNotifyInfo` text COMMENT '订单通知信息',
  `postData` text COMMENT 'post数据',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`logID`)
) ENGINE=MyISAM AUTO_INCREMENT=25505 DEFAULT CHARSET=utf8 COMMENT='支付通知日志\r\nsource: pay';

-- ----------------------------
-- Records of PaynotifyLog
-- ----------------------------

-- ----------------------------
-- Table structure for `Product`
-- ----------------------------
DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `productID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品ID',
  `stroreID` int(11) DEFAULT NULL COMMENT '店铺ID',
  `productName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '拍品名称',
  `listImg` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '列表图片',
  `detailImgs` varchar(1024) COLLATE utf8_general_mysql500_ci DEFAULT NULL,
  `productDetail` text COLLATE utf8_general_mysql500_ci COMMENT '产品介绍',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '拍品分类ID',
  `productAuthor` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '作品作者',
  `productCreationTime` int(11) DEFAULT NULL COMMENT '作品创作时间',
  `productTheme` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '作品题材',
  `productSize` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '作品尺寸',
  `productMaterial` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '作品材质',
  `productMount` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '作品装裱',
  `startPrice` decimal(9,2) DEFAULT NULL COMMENT '起拍价',
  `currPrice` decimal(9,2) DEFAULT NULL COMMENT '当前价格',
  `deliveryPrice` decimal(9,2) DEFAULT NULL COMMENT '运费',
  `depositPrice` decimal(9,2) DEFAULT NULL COMMENT '保证金',
  `auctionCount` int(11) DEFAULT '0' COMMENT '出价次数',
  `startTime` int(11) DEFAULT NULL COMMENT '起拍时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束时间',
  `auctionStatus` tinyint(1) DEFAULT '0' COMMENT '状态',
  `auctionPerPrice` decimal(9,2) DEFAULT NULL COMMENT '单次出价',
  `coverDelivery` tinyint(1) DEFAULT '0' COMMENT '是否包邮',
  `refundInDays` tinyint(1) DEFAULT '0' COMMENT '三日内可退',
  `certificateCard` tinyint(1) DEFAULT '0' COMMENT '是否有鉴定证书',
  `authorAuth` tinyint(1) DEFAULT '0' COMMENT '作者授权',
  `artistID` int(11) DEFAULT NULL COMMENT '艺术家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`productID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Product
-- ----------------------------

-- ----------------------------
-- Table structure for `ProductCategory`
-- ----------------------------
DROP TABLE IF EXISTS `ProductCategory`;
CREATE TABLE `ProductCategory` (
  `productCategoryID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品分类ID',
  `categoryName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '分类名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`productCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ProductCategory
-- ----------------------------

-- ----------------------------
-- Table structure for `ProductCategoryFilter`
-- ----------------------------
DROP TABLE IF EXISTS `ProductCategoryFilter`;
CREATE TABLE `ProductCategoryFilter` (
  `productCategoryFilterID` int(11) NOT NULL AUTO_INCREMENT COMMENT '拍品分类筛选ID',
  `productCategoryID` int(11) DEFAULT NULL COMMENT '产品分类ID',
  `filterName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '筛选名称',
  PRIMARY KEY (`productCategoryFilterID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ProductCategoryFilter
-- ----------------------------

-- ----------------------------
-- Table structure for `ProductCategoryFilterOption`
-- ----------------------------
DROP TABLE IF EXISTS `ProductCategoryFilterOption`;
CREATE TABLE `ProductCategoryFilterOption` (
  `productCategoryFilterOptionID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品分类筛选选项ID',
  `productCategoryFilterID` int(11) DEFAULT NULL COMMENT '产品分类筛选ID',
  `optionName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '筛选选项名称',
  PRIMARY KEY (`productCategoryFilterOptionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ProductCategoryFilterOption
-- ----------------------------

-- ----------------------------
-- Table structure for `ProductFilterOption`
-- ----------------------------
DROP TABLE IF EXISTS `ProductFilterOption`;
CREATE TABLE `ProductFilterOption` (
  `productFilterOptionID` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品筛选选项ID',
  `productID` int(11) DEFAULT NULL,
  `productCategoryFilterID` int(11) DEFAULT NULL COMMENT '产品分类筛选ID',
  `productCategoryFilterOptionID` int(11) DEFAULT NULL COMMENT '产品分类选项ID',
  PRIMARY KEY (`productFilterOptionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of ProductFilterOption
-- ----------------------------

-- ----------------------------
-- Table structure for `SiteSetting`
-- ----------------------------
DROP TABLE IF EXISTS `SiteSetting`;
CREATE TABLE `SiteSetting` (
  `settingID` int(11) NOT NULL,
  `settingName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '设置名称',
  `settingValue` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '设置的值',
  PRIMARY KEY (`settingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of SiteSetting
-- ----------------------------

-- ----------------------------
-- Table structure for `Special`
-- ----------------------------
DROP TABLE IF EXISTS `Special`;
CREATE TABLE `Special` (
  `specialID` int(11) NOT NULL AUTO_INCREMENT COMMENT '专场id',
  `specialName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '专场名称',
  `specialProductCategoryID` int(11) DEFAULT NULL COMMENT '专场拍品分类ID',
  `specialImg` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '拍品图片',
  `productCount` int(11) DEFAULT NULL COMMENT '拍品数量',
  `startTime` int(11) DEFAULT NULL COMMENT '开始时间',
  `endTime` int(11) DEFAULT NULL COMMENT '结束时间',
  `storeID` int(11) DEFAULT NULL COMMENT '选送商家ID',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`specialID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Special
-- ----------------------------

-- ----------------------------
-- Table structure for `SpecialProduct`
-- ----------------------------
DROP TABLE IF EXISTS `SpecialProduct`;
CREATE TABLE `SpecialProduct` (
  `specialProductID` int(11) NOT NULL AUTO_INCREMENT COMMENT '专场拍品记录ID',
  `specialID` int(11) DEFAULT NULL COMMENT '活动ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  `specialPrice` decimal(9,2) DEFAULT NULL COMMENT '活动价',
  `isRecommend` tinyint(4) DEFAULT '0' COMMENT '是否是本场推荐',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`specialProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of SpecialProduct
-- ----------------------------

-- ----------------------------
-- Table structure for `Store`
-- ----------------------------
DROP TABLE IF EXISTS `Store`;
CREATE TABLE `Store` (
  `storeID` int(11) NOT NULL AUTO_INCREMENT COMMENT '商家ID',
  `storeName` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '商家名称',
  `storeSignature` varchar(255) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '店铺签名',
  `storeDesc` varchar(512) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '商家介绍',
  `storeLogo` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '店铺LOGO',
  `storeBanner` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '店铺抬头',
  `recommendImg` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '店铺推荐图',
  `isRecommend` tinyint(4) DEFAULT '0' COMMENT '是否是推荐店铺',
  `instime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`storeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of Store
-- ----------------------------

-- ----------------------------
-- Table structure for `StoreCategory`
-- ----------------------------
DROP TABLE IF EXISTS `StoreCategory`;
CREATE TABLE `StoreCategory` (
  `storeCategoryID` int(11) NOT NULL,
  `storeCategoryName` varchar(50) COLLATE utf8_general_mysql500_ci DEFAULT NULL COMMENT '店铺拍品分类',
  PRIMARY KEY (`storeCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of StoreCategory
-- ----------------------------

-- ----------------------------
-- Table structure for `StoreCategoryProduct`
-- ----------------------------
DROP TABLE IF EXISTS `StoreCategoryProduct`;
CREATE TABLE `StoreCategoryProduct` (
  `storeCategoryProductID` int(11) NOT NULL,
  `storeCategoryID` int(11) DEFAULT NULL COMMENT '店铺拍品分类ID',
  `productID` int(11) DEFAULT NULL COMMENT '拍品ID',
  PRIMARY KEY (`storeCategoryProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- ----------------------------
-- Records of StoreCategoryProduct
-- ----------------------------
