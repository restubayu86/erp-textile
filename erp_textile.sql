/*
 Navicat Premium Data Transfer

 Source Server         : Local
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : erp_textile

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 05/08/2026 17:01:50
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_groups_users
-- ----------------------------
DROP TABLE IF EXISTS `auth_groups_users`;
CREATE TABLE `auth_groups_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `auth_groups_users_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_groups_users
-- ----------------------------
INSERT INTO `auth_groups_users` VALUES (1, 1, 'superadmin', '2026-06-08 06:04:01');
INSERT INTO `auth_groups_users` VALUES (3, 2, 'warehouse_operator', '2026-06-16 09:12:38');

-- ----------------------------
-- Table structure for auth_identities
-- ----------------------------
DROP TABLE IF EXISTS `auth_identities`;
CREATE TABLE `auth_identities`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `expires` datetime NULL DEFAULT NULL,
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `type_secret`(`type` ASC, `secret` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_identities
-- ----------------------------
INSERT INTO `auth_identities` VALUES (1, 1, 'email_password', NULL, 'admin@erp-textile.local', '$2y$12$3Td6Ec/unlo443/o1xGsceLJgHKZMCQoOct0kowDANV83/SGrijXO', NULL, NULL, 0, '2026-08-05 07:31:53', '2026-06-08 06:04:01', '2026-08-05 07:31:53');
INSERT INTO `auth_identities` VALUES (2, 2, 'email_password', NULL, 'mbcregency.3a@gmail.com', '$2y$12$0cDSVrs6.10gdjFf1TVIUOMAc/g2vRG0cPB.Lv27Do/yCcQXIlPmG', NULL, NULL, 0, '2026-07-08 07:21:57', '2026-06-16 09:09:51', '2026-07-08 07:21:57');

-- ----------------------------
-- Table structure for auth_logins
-- ----------------------------
DROP TABLE IF EXISTS `auth_logins`;
CREATE TABLE `auth_logins`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NULL DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_type_identifier`(`id_type` ASC, `identifier` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_logins
-- ----------------------------
INSERT INTO `auth_logins` VALUES (1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 07:57:23', 1);
INSERT INTO `auth_logins` VALUES (2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2026-06-08 08:50:30', 0);
INSERT INTO `auth_logins` VALUES (3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 09:07:24', 1);
INSERT INTO `auth_logins` VALUES (4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 09:14:18', 1);
INSERT INTO `auth_logins` VALUES (5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-10 01:58:45', 1);
INSERT INTO `auth_logins` VALUES (6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-11 01:52:26', 1);
INSERT INTO `auth_logins` VALUES (7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-15 03:39:20', 1);
INSERT INTO `auth_logins` VALUES (8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-16 04:08:52', 1);
INSERT INTO `auth_logins` VALUES (9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-16 09:10:33', 1);
INSERT INTO `auth_logins` VALUES (10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'Alex', 2, '2026-06-16 09:12:58', 1);
INSERT INTO `auth_logins` VALUES (11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-16 09:18:10', 1);
INSERT INTO `auth_logins` VALUES (12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-18 08:55:33', 1);
INSERT INTO `auth_logins` VALUES (13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-22 02:05:15', 1);
INSERT INTO `auth_logins` VALUES (14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-22 05:34:43', 1);
INSERT INTO `auth_logins` VALUES (15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-29 06:44:25', 1);
INSERT INTO `auth_logins` VALUES (16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-08 07:17:09', 1);
INSERT INTO `auth_logins` VALUES (17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'Alex', 2, '2026-07-08 07:21:57', 1);
INSERT INTO `auth_logins` VALUES (18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'administrator', NULL, '2026-07-08 07:23:33', 0);
INSERT INTO `auth_logins` VALUES (19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-08 07:23:39', 1);
INSERT INTO `auth_logins` VALUES (20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-09 07:38:50', 1);
INSERT INTO `auth_logins` VALUES (21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'administrator', NULL, '2026-07-09 08:04:36', 0);
INSERT INTO `auth_logins` VALUES (22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'administrator', NULL, '2026-07-09 08:05:05', 0);
INSERT INTO `auth_logins` VALUES (23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-09 08:05:34', 1);
INSERT INTO `auth_logins` VALUES (24, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-10 03:42:43', 1);
INSERT INTO `auth_logins` VALUES (25, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-10 05:56:39', 1);
INSERT INTO `auth_logins` VALUES (26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-11 02:02:57', 1);
INSERT INTO `auth_logins` VALUES (27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-13 01:48:07', 1);
INSERT INTO `auth_logins` VALUES (28, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-07-13 06:42:03', 1);
INSERT INTO `auth_logins` VALUES (29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-08-03 06:38:41', 1);
INSERT INTO `auth_logins` VALUES (30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-08-04 04:12:27', 1);
INSERT INTO `auth_logins` VALUES (31, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-08-05 06:48:12', 1);
INSERT INTO `auth_logins` VALUES (32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-08-05 07:31:53', 1);

-- ----------------------------
-- Table structure for auth_permissions_users
-- ----------------------------
DROP TABLE IF EXISTS `auth_permissions_users`;
CREATE TABLE `auth_permissions_users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `auth_permissions_users_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_permissions_users
-- ----------------------------

-- ----------------------------
-- Table structure for auth_remember_tokens
-- ----------------------------
DROP TABLE IF EXISTS `auth_remember_tokens`;
CREATE TABLE `auth_remember_tokens`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashedValidator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `selector`(`selector` ASC) USING BTREE,
  INDEX `auth_remember_tokens_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_remember_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for auth_token_logins
-- ----------------------------
DROP TABLE IF EXISTS `auth_token_logins`;
CREATE TABLE `auth_token_logins`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NULL DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_type_identifier`(`id_type` ASC, `identifier` ASC) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_token_logins
-- ----------------------------

-- ----------------------------
-- Table structure for chemical_categories
-- ----------------------------
DROP TABLE IF EXISTS `chemical_categories`;
CREATE TABLE `chemical_categories`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chemical_categories_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `chemical_categories_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `chemical_categories_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  INDEX `category_code`(`category_code` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `chemical_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemical_categories_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemical_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_categories
-- ----------------------------

-- ----------------------------
-- Table structure for chemical_category_map
-- ----------------------------
DROP TABLE IF EXISTS `chemical_category_map`;
CREATE TABLE `chemical_category_map`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `chemical_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `chemical_id_category_id`(`chemical_id` ASC, `category_id` ASC) USING BTREE,
  INDEX `chemical_category_map_category_id_foreign`(`category_id` ASC) USING BTREE,
  CONSTRAINT `chemical_category_map_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `chemical_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chemical_category_map_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_category_map
-- ----------------------------

-- ----------------------------
-- Table structure for chemical_stock_openings
-- ----------------------------
DROP TABLE IF EXISTS `chemical_stock_openings`;
CREATE TABLE `chemical_stock_openings`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `period_id` int UNSIGNED NOT NULL,
  `warehouse_id` int UNSIGNED NOT NULL,
  `chemical_id` int UNSIGNED NOT NULL,
  `quantity` decimal(15, 3) NOT NULL DEFAULT 0.000,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `period_id_warehouse_id_chemical_id`(`period_id` ASC, `warehouse_id` ASC, `chemical_id` ASC) USING BTREE,
  INDEX `chemical_stock_openings_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `chemical_stock_openings_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `period_id`(`period_id` ASC) USING BTREE,
  INDEX `warehouse_id`(`warehouse_id` ASC) USING BTREE,
  INDEX `chemical_id`(`chemical_id` ASC) USING BTREE,
  CONSTRAINT `chemical_stock_openings_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chemical_stock_openings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemical_stock_openings_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chemical_stock_openings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemical_stock_openings_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_stock_openings
-- ----------------------------

-- ----------------------------
-- Table structure for chemical_variants
-- ----------------------------
DROP TABLE IF EXISTS `chemical_variants`;
CREATE TABLE `chemical_variants`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `chemical_id` int UNSIGNED NOT NULL,
  `variant_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'cth: Drum 200L, Jerigen 20L, Karung 25kg',
  `packaging` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Jenis kemasan: Drum, Jerigen, Karung, Botol, dll',
  `packaging_size` decimal(12, 2) NULL DEFAULT NULL COMMENT 'Ukuran per kemasan (isi bersih)',
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Satuan: kg, liter, gram, ml, pcs',
  `price` decimal(15, 2) NULL DEFAULT NULL COMMENT 'Harga per kemasan (Rupiah)',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = varian utama/default untuk chemical ini',
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chemical_variants_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `chemical_variants_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `chemical_id`(`chemical_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `chemical_variants_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chemical_variants_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemical_variants_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_variants
-- ----------------------------

-- ----------------------------
-- Table structure for chemicals
-- ----------------------------
DROP TABLE IF EXISTS `chemicals`;
CREATE TABLE `chemicals`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `chemical_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `chemical_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `chemical_code`(`chemical_code` ASC) USING BTREE,
  INDEX `chemicals_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `chemicals_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `chemicals_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `chemical_name`(`chemical_name` ASC) USING BTREE,
  CONSTRAINT `chemicals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemicals_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chemicals_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemicals
-- ----------------------------
INSERT INTO `chemicals` VALUES (1, 'CHM-00001', 'Acetic Acid', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (5, 'CHM-00002', 'Aica Aibon RA 940-1 ID', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (6, 'CHM-00003', 'Bayguard BCS', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (7, 'CHM-00004', 'Bayguard Easy', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (8, 'CHM-00005', 'Baypret Nano PU', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (9, 'CHM-00006', 'Bayscent Neutralizer', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (10, 'CHM-00007', 'Binder FB WB', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (11, 'CHM-00008', 'Binder P-MHC', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (12, 'CHM-00009', 'Binder TB WB', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (13, 'CHM-00010', 'Booster', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (14, 'CHM-00011', 'Celessence Aloevera Aloha', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (15, 'CHM-00012', 'CF-05', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (16, 'CHM-00013', 'CF-06', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (17, 'CHM-00014', 'Citric Acid', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (18, 'CHM-00015', 'Dyasoft CTG', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (19, 'CHM-00016', 'Eucalyptus', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (20, 'CHM-00017', 'Eulan Spa', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (21, 'CHM-00018', 'Fixapret Net Liq', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (22, 'CHM-00019', 'IGNITex F 003', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (23, 'CHM-00020', 'IGNITex F 003 - White', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (24, 'CHM-00021', 'IGNITex IF 004', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (25, 'CHM-00022', 'Ingenus MBX', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (26, 'CHM-00023', 'Jintex Eco APY', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (27, 'CHM-00024', 'JW AT 500', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (28, 'CHM-00025', 'JW HISOFTER CN 500', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (29, 'CHM-00026', 'Kasesol ES 9', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (30, 'CHM-00027', 'Kirakuru DA-12', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (31, 'CHM-00028', 'Megasoft Win', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (32, 'CHM-00029', 'Mowilith DM 60', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (33, 'CHM-00030', 'Myprint 160A', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (34, 'CHM-00031', 'Neocrystal NK 1500', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (35, 'CHM-00032', 'Neostecker HF 9189', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (36, 'CHM-00033', 'Neostecker HF 9432', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (37, 'CHM-00034', 'Nicca Fi-None CN 563', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (38, 'CHM-00035', 'Nicca Fi-None P 205', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (39, 'CHM-00036', 'Nikka Guard S33', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (40, 'CHM-00037', 'Nikkosolt 209', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (41, 'CHM-00038', 'Nuva N 2155 Liq', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (42, 'CHM-00039', 'Pekoflam TC 303 P', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (43, 'CHM-00040', 'Petra Aminon', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (44, 'CHM-00041', 'R/W Black RD Conc', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (45, 'CHM-00042', 'Reapret SR New', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (46, 'CHM-00043', 'Repellan ETP New', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (47, 'CHM-00044', 'Repellan NC6 New', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (48, 'CHM-00045', 'Repellan NFC 0', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (49, 'CHM-00046', 'Repellan NFS', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (50, 'CHM-00047', 'Repellan XLN', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (51, 'CHM-00048', 'Ruco Acid ASC', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (52, 'CHM-00049', 'Rucocoat FRC 9107 EVO-ID', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (53, 'CHM-00050', 'Rucocoat HDSC PLUS-ID', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (54, 'CHM-00051', 'Rucocoat HDSG DOFF', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (55, 'CHM-00052', 'Ruco-Coat LM 7725-ID', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (56, 'CHM-00053', 'Rucocryl FA 8492', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (57, 'CHM-00054', 'Rucocryl VA 1090', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (58, 'CHM-00055', 'Ruco-Dry Eco Plus', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (59, 'CHM-00056', 'Rucofan ECO', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (60, 'CHM-00057', 'Rucofin SIQ New', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (61, 'CHM-00058', 'Rucoflam PSY', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (62, 'CHM-00059', 'Ruco-Guard AFCN6-ID', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (63, 'CHM-00060', 'Rucolink TIE New', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (64, 'CHM-00061', 'Rucolink XCN', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (65, 'CHM-00062', 'Rucowet FN', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (66, 'CHM-00063', 'Sanitized TH14-14', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (67, 'CHM-00064', 'Shearlon P - 92', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (68, 'CHM-00065', 'Silvadur 930', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (69, 'CHM-00066', 'Smartsoft SS 21', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (70, 'CHM-00067', 'Soda Ash', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (71, 'CHM-00068', 'SP 100', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (72, 'CHM-00069', 'Stabiform Foam-9', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (73, 'CHM-00070', 'Sunmarina 155 GT', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (74, 'CHM-00071', 'Synthetic 9200', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (75, 'CHM-00072', 'Tastex Med', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (76, 'CHM-00073', 'Textport AB-964', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (77, 'CHM-00074', 'Textport BG-183', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `chemicals` VALUES (78, 'CHM-00075', 'Thickener TJS', NULL, 'Active', NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `department_code`(`department_code` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES (1, 'DYE', 'DYEING', 's', 'Active', 1, 1, NULL, '2026-06-10 02:32:57', '2026-06-16 08:57:52', NULL);
INSERT INTO `departments` VALUES (2, 'FIN', 'FINISHING', NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:33:13', '2026-06-10 02:33:13', NULL);

-- ----------------------------
-- Table structure for design_master
-- ----------------------------
DROP TABLE IF EXISTS `design_master`;
CREATE TABLE `design_master`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `design_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `design_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `design_code`(`design_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of design_master
-- ----------------------------
INSERT INTO `design_master` VALUES (1, 'ARCO-YD', 'Arco (YD)', NULL, 'Active', 1, 1, NULL, '2026-06-22 06:40:00', '2026-06-22 09:40:06', NULL);
INSERT INTO `design_master` VALUES (2, 'ARDEX', 'Ardex', NULL, 'Active', 1, 1, NULL, '2026-06-22 09:34:13', '2026-06-22 09:40:16', NULL);
INSERT INTO `design_master` VALUES (3, 'ARSENAL', 'Arsenal', NULL, 'Active', 1, 1, NULL, '2026-06-22 09:34:52', '2026-06-22 09:40:23', NULL);
INSERT INTO `design_master` VALUES (4, 'ASHTON', 'Ashton', NULL, 'Active', 1, 1, NULL, '2026-06-22 09:35:03', '2026-06-22 09:40:31', NULL);
INSERT INTO `design_master` VALUES (5, 'BALOS', 'Balos', NULL, 'Active', 1, 1, NULL, '2026-06-22 09:36:05', '2026-06-22 09:41:07', NULL);

-- ----------------------------
-- Table structure for employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gender` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position_id` int UNSIGNED NOT NULL,
  `work_area` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Area kerja fisik, misal: Dyeing Floor, Finishing, QC Lab',
  `shift` enum('NS','A','B','C','D','E') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NS' COMMENT 'NS=Non-Shift, A/B/C=3-shift, D/E=extended',
  `employment_status` enum('tetap','kontrak','magang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tetap',
  `join_date` date NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL COMMENT 'FK → users.id (Shield)',
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL COMMENT 'FK → users.id (Shield)',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT 'Soft delete — null = aktif',
  `deleted_by` int UNSIGNED NULL DEFAULT NULL COMMENT 'FK → users.id (Shield)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nik`(`nik` ASC) USING BTREE,
  INDEX `position_id`(`position_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `shift`(`shift` ASC) USING BTREE,
  CONSTRAINT `employees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of employees
-- ----------------------------
INSERT INTO `employees` VALUES (3, '03.42.24.2576', 'Bagus Adhi N', 'Bagus', 'L', NULL, 'EMP_03.42.24.2576_1781596328.webp', 1, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, '2026-06-16 07:52:08', 1, NULL, NULL);
INSERT INTO `employees` VALUES (4, '06.70.90.0214', 'Jumar', 'Jumar', 'L', NULL, 'EMP_06.70.90.0214_1781597802.webp', 2, 'Lab', 'NS', 'kontrak', NULL, 'inactive', NULL, NULL, '2026-06-16 08:16:42', 1, NULL, NULL);
INSERT INTO `employees` VALUES (5, '03.42.93.0381', 'Solihin', 'Solihin', 'L', '', '', 3, 'Office', 'NS', 'magang', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (6, '03.42.90.0200', 'Sujana', 'Sujana', 'L', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (7, '16.060.18', 'Rendi Oktiardi', 'Rendi', 'L', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (8, '16.05.066', 'Sinta Dewi', 'Sinta', 'P', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (9, '14.12.010', 'Alex Supriatna', 'Alex', 'L', '', '', 3, 'Gd Dyestuff', 'NS', 'tetap', NULL, 'active', NULL, NULL, '2026-06-16 08:48:53', NULL, NULL, NULL);
INSERT INTO `employees` VALUES (10, '03.42.01.1163', 'Moh.Rudi Supriadi', 'Moh', 'L', '', '', 3, 'Dyeing', 'A', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (11, '13.01.010', 'Safeul Ahmad', 'Safeul', 'L', '', '', 3, 'Dyeing', 'A', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (12, '12.11.007', 'Riki', 'Riki', 'L', '', '', 3, 'Dyeing', 'A', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (13, '16.02.013', 'Wildan Muh A.A', 'Wildan', 'L', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (14, '03.42.11.1783', 'Muhamad Eko F', 'Muhamad', 'L', '', '', 3, 'Dyeing', 'B', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (15, '03.42.18.2309', 'Sasa Sutarsa', 'Sasa', 'L', '', '', 4, 'Dyeing', 'B', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (16, '16.02.006', 'Abdul Hasan ', 'Abdul', 'L', '', '', 4, 'Dyeing', 'B', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (17, '03.42.97.0639', 'Jajang Mahpudin', 'Jajang', 'L', '', '', 4, 'Dyeing', 'C', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (18, '03.42.92.1216', 'Samsudin', 'Samsudin', 'L', '', '', 4, 'Dyeing', 'C', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (19, '03.42.18.2310', 'Wawan Gunawan', 'Wawan', 'L', '', '', 4, 'Dyeing', 'C', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (20, 'K 0971', 'Erik Resmana', 'Erik', 'L', '', '', 4, 'Dyeing', 'C', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (21, 'K 0836', 'Acep Jamaludin', 'Acep', 'L', '', '', 4, 'Gd Auxiliaries', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (22, '03.42.16.2131', 'Cecep Padilah H', 'Cecep', 'L', '', '', 4, 'Lab', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for flow_process_steps
-- ----------------------------
DROP TABLE IF EXISTS `flow_process_steps`;
CREATE TABLE `flow_process_steps`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `flow_process_id` int UNSIGNED NOT NULL,
  `step_no` int UNSIGNED NOT NULL,
  `process_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `step_type` enum('process','chemical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'process',
  `chemical_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `flow_process_id_step_no`(`flow_process_id` ASC, `step_no` ASC) USING BTREE,
  INDEX `flow_process_id`(`flow_process_id` ASC) USING BTREE,
  CONSTRAINT `flow_process_steps_flow_process_id_foreign` FOREIGN KEY (`flow_process_id`) REFERENCES `flow_processes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of flow_process_steps
-- ----------------------------
INSERT INTO `flow_process_steps` VALUES (5, 1, 1, 'Dyeing', 'process', NULL, '2026-06-22 09:33:08', '2026-06-22 09:33:08');
INSERT INTO `flow_process_steps` VALUES (6, 1, 2, 'Setting', 'process', NULL, '2026-06-22 09:33:08', '2026-06-22 09:33:08');
INSERT INTO `flow_process_steps` VALUES (7, 1, 3, 'Dipping', 'process', NULL, '2026-06-22 09:33:08', '2026-06-22 09:33:08');
INSERT INTO `flow_process_steps` VALUES (8, 1, 4, 'Coating', 'process', NULL, '2026-06-22 09:33:08', '2026-06-22 09:33:08');

-- ----------------------------
-- Table structure for flow_processes
-- ----------------------------
DROP TABLE IF EXISTS `flow_processes`;
CREATE TABLE `flow_processes`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `design_id` int UNSIGNED NOT NULL,
  `flow_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `segment` enum('Interior','Otomotif','Lain-Lain') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `design_id_flow_name`(`design_id` ASC, `flow_name` ASC) USING BTREE,
  INDEX `design_id`(`design_id` ASC) USING BTREE,
  CONSTRAINT `flow_processes_design_id_foreign` FOREIGN KEY (`design_id`) REFERENCES `design_master` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of flow_processes
-- ----------------------------
INSERT INTO `flow_processes` VALUES (1, 1, 'ARCO YD', 'Otomotif', NULL, 'Active', 1, 1, NULL, '2026-06-22 07:03:22', '2026-06-22 09:33:08', NULL);

-- ----------------------------
-- Table structure for formulation_groups
-- ----------------------------
DROP TABLE IF EXISTS `formulation_groups`;
CREATE TABLE `formulation_groups`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama kelompok/label khusus formulasi',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `group_name`(`group_name` ASC) USING BTREE,
  INDEX `formulation_groups_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `formulation_groups_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `formulation_groups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formulation_groups_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of formulation_groups
-- ----------------------------

-- ----------------------------
-- Table structure for formulation_items
-- ----------------------------
DROP TABLE IF EXISTS `formulation_items`;
CREATE TABLE `formulation_items`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `formulation_version_id` int UNSIGNED NOT NULL,
  `chemical_id` int UNSIGNED NULL DEFAULT NULL,
  `composition_type` enum('chemical','softener_water') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chemical' COMMENT 'chemical = konsumsi stok bahan kimia, softener_water = tanpa alur stok',
  `variant_id` int UNSIGNED NULL DEFAULT NULL,
  `custom_label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nama bebas untuk softener_water, mis. \"Air Proses/Softening\"',
  `percentage` decimal(8, 3) NOT NULL DEFAULT 0.000 COMMENT 'Dosis dalam % terhadap berat batch, tidak dibatasi total 100%',
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `formulation_version_id_chemical_id`(`formulation_version_id` ASC, `chemical_id` ASC) USING BTREE,
  INDEX `formulation_version_id`(`formulation_version_id` ASC) USING BTREE,
  INDEX `chemical_id`(`chemical_id` ASC) USING BTREE,
  INDEX `variant_id`(`variant_id` ASC) USING BTREE,
  INDEX `composition_type`(`composition_type` ASC) USING BTREE,
  CONSTRAINT `formulation_items_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `formulation_items_formulation_version_id_foreign` FOREIGN KEY (`formulation_version_id`) REFERENCES `formulation_versions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `formulation_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `chemical_variants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of formulation_items
-- ----------------------------
INSERT INTO `formulation_items` VALUES (1, 1, NULL, 'softener_water', NULL, 'Softener Water', 98.000, NULL, NULL, 0, '2026-08-05 09:57:19', '2026-08-05 09:57:19');
INSERT INTO `formulation_items` VALUES (2, 1, 29, 'chemical', NULL, NULL, 2.000, NULL, NULL, 1, '2026-08-05 09:57:19', '2026-08-05 09:57:19');
INSERT INTO `formulation_items` VALUES (3, 2, NULL, 'softener_water', NULL, 'Softener Water', 97.000, NULL, NULL, 0, '2026-08-05 09:59:45', '2026-08-05 09:59:45');
INSERT INTO `formulation_items` VALUES (4, 2, 29, 'chemical', NULL, NULL, 3.000, NULL, NULL, 1, '2026-08-05 09:59:45', '2026-08-05 09:59:45');
INSERT INTO `formulation_items` VALUES (5, 3, NULL, 'softener_water', NULL, 'Softener Water', 97.000, NULL, NULL, 0, '2026-08-05 10:00:34', '2026-08-05 10:00:34');
INSERT INTO `formulation_items` VALUES (6, 3, 29, 'chemical', NULL, NULL, 3.000, NULL, NULL, 1, '2026-08-05 10:00:34', '2026-08-05 10:00:34');

-- ----------------------------
-- Table structure for formulation_versions
-- ----------------------------
DROP TABLE IF EXISTS `formulation_versions`;
CREATE TABLE `formulation_versions`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `formulation_id` int UNSIGNED NOT NULL,
  `version_no` int UNSIGNED NOT NULL COMMENT 'Nomor urut versi per formulasi: 1, 2, 3, dst',
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `output_percentage` decimal(8, 3) NOT NULL DEFAULT 100.000 COMMENT 'Hasil/batch dalam %, tidak dibatasi harus 100 (boleh > 100%)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Catatan perubahan pada versi ini (changelog)',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `formulation_id_version_no`(`formulation_id` ASC, `version_no` ASC) USING BTREE,
  INDEX `formulation_versions_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `formulation_id`(`formulation_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `formulation_versions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formulation_versions_formulation_id_foreign` FOREIGN KEY (`formulation_id`) REFERENCES `formulations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of formulation_versions
-- ----------------------------
INSERT INTO `formulation_versions` VALUES (1, 1, 1, 'Active', 100.000, NULL, 1, '2026-08-05 09:57:19', '2026-08-05 09:57:19');
INSERT INTO `formulation_versions` VALUES (2, 2, 1, 'Archived', 100.000, NULL, 1, '2026-08-05 09:59:45', '2026-08-05 09:59:45');
INSERT INTO `formulation_versions` VALUES (3, 2, 2, 'Active', 100.000, NULL, 1, '2026-08-05 10:00:34', '2026-08-05 10:00:34');

-- ----------------------------
-- Table structure for formulations
-- ----------------------------
DROP TABLE IF EXISTS `formulations`;
CREATE TABLE `formulations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `formulation_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `formulation_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int UNSIGNED NULL DEFAULT NULL COMMENT 'Kelompok/label khusus formulasi (opsional)',
  `process_type` enum('Dyeing','Finishing','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dyeing',
  `process_sub_type` enum('Dyeing','Dipping','Coating','Spray','Coating_Foam','Finishing','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Other',
  `process_sub_type_label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Label custom untuk sub proses jika diperlukan',
  `current_version_id` int UNSIGNED NULL DEFAULT NULL COMMENT 'ID versi terakhir/aktif yang sedang dipakai',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `formulation_code`(`formulation_code` ASC) USING BTREE,
  INDEX `formulations_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `formulations_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `formulations_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  INDEX `group_id`(`group_id` ASC) USING BTREE,
  INDEX `process_type`(`process_type` ASC) USING BTREE,
  INDEX `process_sub_type`(`process_sub_type` ASC) USING BTREE,
  INDEX `current_version_id`(`current_version_id` ASC) USING BTREE,
  INDEX `deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `formulations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formulations_current_version_id_foreign` FOREIGN KEY (`current_version_id`) REFERENCES `formulation_versions` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `formulations_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `formulations_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `formulation_groups` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `formulations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of formulations
-- ----------------------------
INSERT INTO `formulations` VALUES (1, 'F08260001', 'D-ARCO', NULL, 'Finishing', 'Dipping', NULL, 1, NULL, 1, NULL, NULL, '2026-08-05 09:57:19', '2026-08-05 09:57:19', NULL);
INSERT INTO `formulations` VALUES (2, 'F08260002', 'D-ARCO', NULL, 'Finishing', 'Dipping', NULL, 3, NULL, 1, 1, NULL, '2026-08-05 09:59:45', '2026-08-05 10:00:34', NULL);

-- ----------------------------
-- Table structure for machines
-- ----------------------------
DROP TABLE IF EXISTS `machines`;
CREATE TABLE `machines`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `machine_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int UNSIGNED NULL DEFAULT NULL,
  `capacity` decimal(12, 2) NULL DEFAULT NULL COMMENT 'Kapasitas mesin (kg/jam, meter/menit, dll — satuan bebas sesuai jenis mesin)',
  `capacity_unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'cth: kg/jam, m/menit, pcs/jam',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Maintenance','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `department_id`(`department_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `idx_code_dept`(`machine_code` ASC, `department_id` ASC) USING BTREE,
  INDEX `idx_name_dept`(`machine_name` ASC, `department_id` ASC) USING BTREE,
  CONSTRAINT `machines_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of machines
-- ----------------------------
INSERT INTO `machines` VALUES (1, 'JD-01', 'Hisaka 1', 1, NULL, NULL, NULL, 'Active', 1, NULL, NULL, '2026-07-08 07:31:30', '2026-07-08 07:31:30', NULL);
INSERT INTO `machines` VALUES (2, 'JD-02', 'Hisaka 2', 1, NULL, NULL, NULL, 'Active', 1, NULL, NULL, '2026-07-08 07:31:57', '2026-07-08 07:31:57', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1780898628, 1);
INSERT INTO `migrations` VALUES (2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1780898628, 1);
INSERT INTO `migrations` VALUES (3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1780898628, 1);
INSERT INTO `migrations` VALUES (4, '2026-06-09-084705', 'App\\Database\\Migrations\\CreateDepartmentsTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);
INSERT INTO `migrations` VALUES (5, '2026-06-09-150318', 'App\\Modules\\HRM\\Database\\Migrations\\CreatePositionsTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);
INSERT INTO `migrations` VALUES (6, '2026-06-09-231443', 'App\\Modules\\HRM\\Database\\Migrations\\CreateEmployeesTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);
INSERT INTO `migrations` VALUES (7, '2026-06-18-000001', 'App\\Modules\\Production\\Database\\Migrations\\CreateMachinesTable', 'default', 'App\\Modules\\Production', 1782107225, 3);
INSERT INTO `migrations` VALUES (8, '2026-06-21-000001', 'App\\Database\\Migrations\\CreateDesignAndFlowProcessTables', 'default', 'App\\Modules\\Production', 1782107225, 3);
INSERT INTO `migrations` VALUES (9, '2026-06-22-000001', 'App\\Database\\Migrations\\CreateDesignAndFlowProcessTables', 'default', 'App\\Modules\\Production', 1782109639, 4);
INSERT INTO `migrations` VALUES (10, '2026-07-01-000002', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalCategoriesTable', 'default', 'App\\Modules\\Warehouse', 1783499128, 5);
INSERT INTO `migrations` VALUES (11, '2026-07-01-000003', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalsTable', 'default', 'App\\Modules\\Warehouse', 1783499128, 5);
INSERT INTO `migrations` VALUES (12, '2026-07-01-000004', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalVariantsTable', 'default', 'App\\Modules\\Warehouse', 1783499128, 5);
INSERT INTO `migrations` VALUES (13, '2026-07-08-083636', 'App\\Database\\Migrations\\CreateWarehousesTable', 'default', 'App\\Modules\\Warehouse', 1783499988, 6);
INSERT INTO `migrations` VALUES (14, '2026-07-09-000001', 'App\\Database\\Migrations\\ModifyChemicalsMultiCategory', 'default', 'App\\Modules\\Warehouse', 1783587105, 7);
INSERT INTO `migrations` VALUES (15, '2026-07-10-072230', 'App\\Database\\Migrations\\CreatePeriodsTable', 'default', 'App\\Modules\\Warehouse', 1783668605, 8);
INSERT INTO `migrations` VALUES (16, '2026-07-10-234132', 'App\\Database\\Migrations\\CreateChemicalStockOpeningsTable', 'default', 'App\\Modules\\Warehouse', 1783735350, 9);
INSERT INTO `migrations` VALUES (17, '2026-08-04-000001', 'App\\Database\\Migrations\\CreateFormulationsTable', 'default', 'App\\Modules\\Warehouse', 1785912328, 10);
INSERT INTO `migrations` VALUES (18, '2026-08-04-000002', 'App\\Database\\Migrations\\CreateFormulationItemsTable', 'default', 'App\\Modules\\Warehouse', 1785912328, 10);
INSERT INTO `migrations` VALUES (19, '2026-08-05-000001', 'App\\Database\\Migrations\\CreateFormulationGroupsTable', 'default', 'App\\Modules\\Warehouse', 1785915988, 11);
INSERT INTO `migrations` VALUES (20, '2026-08-05-000002', 'App\\Database\\Migrations\\AlterFormulationsAddGroup', 'default', 'App\\Modules\\Warehouse', 1785915988, 11);
INSERT INTO `migrations` VALUES (21, '2026-08-05-000003', 'App\\Database\\Migrations\\CreateFormulationVersionsTable', 'default', 'App\\Modules\\Warehouse', 1785915988, 11);
INSERT INTO `migrations` VALUES (22, '2026-08-05-000004', 'App\\Database\\Migrations\\AlterFormulationItemsAddVersioning', 'default', 'App\\Modules\\Warehouse', 1785915988, 11);
INSERT INTO `migrations` VALUES (23, '2026-08-05-000006', 'App\\Modules\\Warehouse\\Database\\Migrations\\AddProcessSubTypeToFormulations', 'default', 'App\\Modules\\Warehouse', 1785919341, 12);
INSERT INTO `migrations` VALUES (24, '2026-08-05-000004', 'App\\Modules\\Warehouse\\Database\\Migrations\\AlterFormulationItemsAddVersioning', 'default', 'App\\Modules\\Warehouse', 1785920012, 13);
INSERT INTO `migrations` VALUES (25, '2026-08-05-000007', 'App\\Modules\\Warehouse\\Database\\Migrations\\AddCurrentVersionIdToFormulations', 'default', 'App\\Modules\\Warehouse', 1785920012, 13);
INSERT INTO `migrations` VALUES (26, '2026-08-05-999999', 'App\\Modules\\Warehouse\\Database\\Migrations\\DropAllWarehouseTablesSimple', 'default', 'App\\Modules\\Warehouse', 1785920012, 13);
INSERT INTO `migrations` VALUES (27, '2026-08-05-000001', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateWarehousesTable', 'default', 'App\\Modules\\Warehouse', 1785920361, 14);
INSERT INTO `migrations` VALUES (28, '2026-08-05-000002', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreatePeriodsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (29, '2026-08-05-000003', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalCategoriesTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (30, '2026-08-05-000004', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (31, '2026-08-05-000005', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalCategoryMapTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (32, '2026-08-05-000006', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalVariantsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (33, '2026-08-05-000007', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateChemicalStockOpeningsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (34, '2026-08-05-000008', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateFormulationGroupsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (35, '2026-08-05-000009', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateFormulationsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (36, '2026-08-05-000010', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateFormulationVersionsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (37, '2026-08-05-000011', 'App\\Modules\\Warehouse\\Database\\Migrations\\CreateFormulationItemsTable', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);
INSERT INTO `migrations` VALUES (38, '2026-08-05-000012', 'App\\Modules\\Warehouse\\Database\\Migrations\\AddForeignKeyCurrentVersion', 'default', 'App\\Modules\\Warehouse', 1785920362, 14);

-- ----------------------------
-- Table structure for periods
-- ----------------------------
DROP TABLE IF EXISTS `periods`;
CREATE TABLE `periods`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `period_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('Open','Closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `closed_at` datetime NULL DEFAULT NULL,
  `closed_by` int UNSIGNED NULL DEFAULT NULL,
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `periods_closed_by_foreign`(`closed_by` ASC) USING BTREE,
  INDEX `periods_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `periods_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `periods_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  INDEX `period_code`(`period_code` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `is_current`(`is_current` ASC) USING BTREE,
  INDEX `start_date`(`start_date` ASC) USING BTREE,
  INDEX `end_date`(`end_date` ASC) USING BTREE,
  INDEX `deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `periods_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `periods_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `periods_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of periods
-- ----------------------------

-- ----------------------------
-- Table structure for positions
-- ----------------------------
DROP TABLE IF EXISTS `positions`;
CREATE TABLE `positions`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `position_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_level` int NULL DEFAULT 0,
  `department_id` int UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `position_code`(`position_code` ASC) USING BTREE,
  INDEX `department_id`(`department_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES (1, 'MNG', 'Manager', 1, NULL, NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:32:11', '2026-06-10 02:32:11', NULL);
INSERT INTO `positions` VALUES (2, 'K-DYE', 'Kabag Dyeing', 2, 1, NULL, 'Active', 1, 1, NULL, '2026-06-10 02:32:40', '2026-06-10 02:33:27', NULL);
INSERT INTO `positions` VALUES (3, 'OP', 'Operator', 3, 1, NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:34:12', '2026-06-10 02:34:12', NULL);
INSERT INTO `positions` VALUES (4, 'OPFN', 'OPERATOR FIN', 4, 2, NULL, 'Active', 1, NULL, NULL, '2026-06-10 06:34:16', '2026-06-10 06:34:16', NULL);

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `context` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of settings
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `employee_id` int UNSIGNED NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  INDEX `fk_employee_id`(`employee_id` ASC) USING BTREE,
  CONSTRAINT `fk_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'superadmin', 3, NULL, NULL, 1, '2026-08-05 10:01:10', '2026-06-08 06:04:01', '2026-06-16 06:51:56', NULL);
INSERT INTO `users` VALUES (2, 'Alex', 9, NULL, NULL, 1, '2026-07-08 07:21:58', '2026-06-16 09:09:51', '2026-06-18 09:01:38', NULL);

-- ----------------------------
-- Table structure for warehouses
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `warehouse_code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int UNSIGNED NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('Active','Draft','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` int UNSIGNED NULL DEFAULT NULL,
  `updated_by` int UNSIGNED NULL DEFAULT NULL,
  `deleted_by` int UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `warehouse_code`(`warehouse_code` ASC) USING BTREE,
  INDEX `warehouse_name`(`warehouse_name` ASC) USING BTREE,
  INDEX `department_id`(`department_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of warehouses
-- ----------------------------
INSERT INTO `warehouses` VALUES (1, 'GFIN', 'GD FINISHING', 2, 'Finishing', NULL, 'Active', 1, NULL, NULL, '2026-08-05 09:06:32', '2026-08-05 09:06:32', NULL);

SET FOREIGN_KEY_CHECKS = 1;
