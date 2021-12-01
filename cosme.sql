/*
 Navicat Premium Data Transfer

 Source Server         : 140.127.74.131_3306
 Source Server Type    : MySQL
 Source Server Version : 100148
 Source Host           : 140.127.74.131:3306
 Source Schema         : cosme

 Target Server Type    : MySQL
 Target Server Version : 100148
 File Encoding         : 65001

 Date: 01/12/2021 17:00:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for creation
-- ----------------------------
DROP TABLE IF EXISTS `creation`;
CREATE TABLE `creation`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `user_key` int NOT NULL COMMENT '使用者主鍵',
  `photo_reference_key` int NOT NULL COMMENT '上妝圖片主鍵',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述內容',
  `created_at` datetime(0) NOT NULL COMMENT '上傳時間',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '編輯時間',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '刪除時間',
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `photo_reference_key`(`photo_reference_key`) USING BTREE,
  INDEX `user_key`(`user_key`) USING BTREE,
  CONSTRAINT `creation_ibfk_1` FOREIGN KEY (`photo_reference_key`) REFERENCES `photo_reference` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `creation_ibfk_2` FOREIGN KEY (`user_key`) REFERENCES `user` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 29348 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for creation_products
-- ----------------------------
DROP TABLE IF EXISTS `creation_products`;
CREATE TABLE `creation_products`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `creation_key` int NOT NULL COMMENT '貼文主鍵',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '產品名稱',
  `imgpath` longblob NOT NULL COMMENT '圖片檔案名稱',
  `url` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '產品網址',
  `intro` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '產品介紹',
  `created_at` datetime(6) NOT NULL COMMENT '建立時間',
  `updated_at` datetime(6) NOT NULL COMMENT '更新時間',
  `deleted_at` datetime(6) NULL DEFAULT NULL COMMENT '刪除時間',
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `creation_key`(`creation_key`) USING BTREE,
  CONSTRAINT `creation_products_ibfk_1` FOREIGN KEY (`creation_key`) REFERENCES `creation` (`key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for creation_tags
-- ----------------------------
DROP TABLE IF EXISTS `creation_tags`;
CREATE TABLE `creation_tags`  (
  `creation_key` int NOT NULL COMMENT '完妝照貼文主鍵',
  `tag_key` int NOT NULL COMMENT '完妝照標籤',
  PRIMARY KEY (`creation_key`, `tag_key`) USING BTREE,
  INDEX `tag_key`(`tag_key`) USING BTREE,
  CONSTRAINT `creation_tags_ibfk_1` FOREIGN KEY (`creation_key`) REFERENCES `creation` (`key`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `creation_tags_ibfk_2` FOREIGN KEY (`tag_key`) REFERENCES `tag` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for photo_reference
-- ----------------------------
DROP TABLE IF EXISTS `photo_reference`;
CREATE TABLE `photo_reference`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `user_key` int NOT NULL COMMENT '使用者主鍵',
  `tumbnial` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '縮圖圖片名稱',
  `full` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '完整圖片名稱',
  `score` int NOT NULL COMMENT '妝容成績1~5',
  `created_at` datetime(0) NOT NULL COMMENT '建立時間',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '更新時間',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '刪除時間',
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `user_key`(`user_key`) USING BTREE,
  CONSTRAINT `photo_reference_ibfk_1` FOREIGN KEY (`user_key`) REFERENCES `user` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 29352 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for photo_synthesize
-- ----------------------------
DROP TABLE IF EXISTS `photo_synthesize`;
CREATE TABLE `photo_synthesize`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `user_key` int NOT NULL COMMENT '使用者主鍵',
  `reference_key` int NOT NULL COMMENT '參考圖主鍵',
  `without_key` int NOT NULL COMMENT '素顏照主鍵',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '產出檔案名稱',
  `score` int NOT NULL COMMENT '評分結果',
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `reference_key`(`reference_key`) USING BTREE,
  INDEX `without_key`(`without_key`) USING BTREE,
  INDEX `user_key`(`user_key`) USING BTREE,
  CONSTRAINT `photo_synthesize_ibfk_1` FOREIGN KEY (`reference_key`) REFERENCES `photo_reference` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `photo_synthesize_ibfk_2` FOREIGN KEY (`without_key`) REFERENCES `photo_without` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `photo_synthesize_ibfk_4` FOREIGN KEY (`user_key`) REFERENCES `user` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 330 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for photo_without
-- ----------------------------
DROP TABLE IF EXISTS `photo_without`;
CREATE TABLE `photo_without`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `user_key` int NOT NULL COMMENT '使用者主鍵',
  `tumbnial` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '縮圖圖片名稱',
  `full` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '完整圖片名稱',
  `is_default` int NOT NULL COMMENT '1or0 是否為預設圖片',
  `created_at` datetime(0) NOT NULL COMMENT '建立時間',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '更新時間',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '刪除時間',
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `user_key`(`user_key`) USING BTREE,
  CONSTRAINT `photo_without_ibfk_1` FOREIGN KEY (`user_key`) REFERENCES `user` (`key`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 223 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
  `created_at` datetime(0) NOT NULL COMMENT '建立時間',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '更新時間',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '刪除時間',
  PRIMARY KEY (`key`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '信箱',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `sex` int NOT NULL COMMENT '性別',
  `birth` date NOT NULL COMMENT '生日',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '使用者圖片檔案名稱(可為空)',
  `business` int NOT NULL,
  `created_at` datetime(0) NOT NULL COMMENT '建立日期',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '最後更改時間',
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for user_auth
-- ----------------------------
DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE `user_auth`  (
  `key` int NOT NULL AUTO_INCREMENT COMMENT '驗證主鍵',
  `user_key` int NOT NULL COMMENT '使用者表外來鍵',
  `access_token` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '令牌',
  `refresh_token` varchar(800) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '刷新判斷用令牌',
  `user_agent` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '使用者裝置資訊',
  `user_ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP',
  `created_at` datetime(0) NOT NULL COMMENT '建立時間',
  `updated_at` datetime(0) NOT NULL COMMENT '更新時間',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '登出時間',
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `user_key`(`user_key`) USING BTREE,
  CONSTRAINT `user_auth_ibfk_1` FOREIGN KEY (`user_key`) REFERENCES `user` (`key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 672 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
