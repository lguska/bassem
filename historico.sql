/*
 Navicat Premium Data Transfer

 Source Server         : Local teste
 Source Server Type    : MySQL
 Source Server Version : 100109
 Source Host           : localhost:3306
 Source Schema         : bassem

 Target Server Type    : MySQL
 Target Server Version : 100109
 File Encoding         : 65001

 Date: 25/01/2018 14:33:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for historico
-- ----------------------------
DROP TABLE IF EXISTS `historico`;
CREATE TABLE `historico`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_conta` int(11) NULL DEFAULT NULL,
  `tipo` tinyint(1) NULL DEFAULT NULL,
  `valor` float NULL DEFAULT NULL,
  `data_operacao` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
