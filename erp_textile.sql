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

 Date: 04/08/2026 11:18:53
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
INSERT INTO `auth_identities` VALUES (1, 1, 'email_password', NULL, 'admin@erp-textile.local', '$2y$12$3Td6Ec/unlo443/o1xGsceLJgHKZMCQoOct0kowDANV83/SGrijXO', NULL, NULL, 0, '2026-08-04 04:12:27', '2026-06-08 06:04:01', '2026-08-04 04:12:27');
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
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `idx_cat_code`(`category_code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_categories
-- ----------------------------
INSERT INTO `chemical_categories` VALUES (1, 'AUX', 'Auxiliaries', 'Kimia pembantu dyeing', 'Active', 1, NULL, NULL, '2026-07-09 07:59:11', '2026-07-09 07:59:11', NULL);
INSERT INTO `chemical_categories` VALUES (2, 'DYE', 'Dyestuff', NULL, 'Archived', 1, 1, 1, '2026-07-09 08:11:12', '2026-07-11 04:25:52', '2026-07-11 04:25:52');
INSERT INTO `chemical_categories` VALUES (3, 'FIN', 'Finishing', NULL, 'Active', 1, NULL, NULL, '2026-07-09 08:11:23', '2026-07-09 08:11:23', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_category_map
-- ----------------------------
INSERT INTO `chemical_category_map` VALUES (20, 3, 3, '2026-07-10 06:40:10');
INSERT INTO `chemical_category_map` VALUES (21, 5, 3, NULL);
INSERT INTO `chemical_category_map` VALUES (23, 2, 1, NULL);
INSERT INTO `chemical_category_map` VALUES (24, 2, 3, NULL);
INSERT INTO `chemical_category_map` VALUES (25, 6, 3, NULL);

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
  UNIQUE INDEX `uniq_period_warehouse_chemical`(`period_id` ASC, `warehouse_id` ASC, `chemical_id` ASC) USING BTREE,
  INDEX `chemical_stock_openings_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `chemical_stock_openings_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `period_id`(`period_id` ASC) USING BTREE,
  INDEX `warehouse_id`(`warehouse_id` ASC) USING BTREE,
  INDEX `chemical_id`(`chemical_id` ASC) USING BTREE,
  CONSTRAINT `chemical_stock_openings_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chemical_stock_openings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `chemical_stock_openings_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `chemical_stock_openings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `chemical_stock_openings_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_stock_openings
-- ----------------------------
INSERT INTO `chemical_stock_openings` VALUES (1, 4, 1, 2, 2.250, 'kg', NULL, 1, 1, '2026-08-03 07:07:20', '2026-08-03 08:09:36');
INSERT INTO `chemical_stock_openings` VALUES (2, 4, 1, 3, 25.000, 'kg', NULL, 1, 1, '2026-08-03 07:07:20', '2026-08-03 08:09:36');
INSERT INTO `chemical_stock_openings` VALUES (3, 4, 1, 6, 1000.000, 'kg', NULL, 1, 1, '2026-08-03 07:07:20', '2026-08-03 08:09:36');
INSERT INTO `chemical_stock_openings` VALUES (4, 4, 1, 5, 625.000, 'kg', NULL, 1, 1, '2026-08-03 07:07:20', '2026-08-03 08:09:36');

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
  INDEX `chemical_id`(`chemical_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  CONSTRAINT `chemical_variants_chemical_id_foreign` FOREIGN KEY (`chemical_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemical_variants
-- ----------------------------
INSERT INTO `chemical_variants` VALUES (3, 5, 'P-MHC @200', 'Drum', 200.00, 'Kg', NULL, 1, 'Active', 1, 1, '2026-07-13 02:59:20', '2026-07-13 03:05:34');
INSERT INTO `chemical_variants` VALUES (4, 5, 'p-MHC @1000', 'Kempu', 1000.00, 'Kg', NULL, 0, 'Active', 1, 1, '2026-07-13 03:05:22', '2026-07-13 03:05:34');
INSERT INTO `chemical_variants` VALUES (5, 5, 'P-MHC 100', NULL, 100.00, NULL, NULL, 0, 'Active', 1, 1, '2026-07-13 03:20:37', '2026-07-13 03:20:37');
INSERT INTO `chemical_variants` VALUES (6, 5, 'p-MHC 400', NULL, 400.00, NULL, NULL, 0, 'Active', 1, 1, '2026-07-13 03:20:54', '2026-07-13 03:20:54');
INSERT INTO `chemical_variants` VALUES (7, 5, 'sd', NULL, 200.00, NULL, NULL, 0, 'Active', 1, 1, '2026-07-13 03:21:06', '2026-07-13 03:21:06');
INSERT INTO `chemical_variants` VALUES (8, 5, 'sdasd', NULL, 50.00, NULL, NULL, 0, 'Active', 1, 1, '2026-07-13 03:21:17', '2026-07-13 03:21:17');

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
  UNIQUE INDEX `uniq_chemical_code`(`chemical_code` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `idx_chem_code`(`chemical_code` ASC) USING BTREE,
  INDEX `idx_chem_name`(`chemical_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chemicals
-- ----------------------------
INSERT INTO `chemicals` VALUES (2, 'CH-00002', 'Acetic Acid', NULL, 'Active', 1, 1, NULL, '2026-07-09 09:15:55', '2026-07-13 02:48:42', NULL);
INSERT INTO `chemicals` VALUES (3, 'CH-00003', 'Aica Aibon RA-940', NULL, 'Active', 1, 1, NULL, '2026-07-09 09:31:49', '2026-07-10 06:40:10', NULL);
INSERT INTO `chemicals` VALUES (5, 'CH-00004', 'Binder P-MHC', NULL, 'Active', 1, 1, NULL, '2026-07-13 02:36:16', '2026-07-13 02:43:00', NULL);
INSERT INTO `chemicals` VALUES (6, 'CH-00005', 'Binder', NULL, 'Active', 1, 1, NULL, '2026-07-13 02:43:20', '2026-07-13 02:48:47', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
  CONSTRAINT `periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `periods_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `periods_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `periods_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of periods
-- ----------------------------
INSERT INTO `periods` VALUES (1, '2026-01', 'Jan 2026', '2025-12-31', '2026-01-30', 'Closed', 0, NULL, '2026-07-10 07:38:44', 1, 1, NULL, NULL, '2026-07-10 07:34:32', '2026-07-10 07:38:44', NULL);
INSERT INTO `periods` VALUES (2, '2026-02', 'Feb 2026', '2026-01-31', '2026-02-27', 'Closed', 0, NULL, '2026-07-11 02:05:53', 1, 1, NULL, NULL, '2026-07-10 07:45:24', '2026-07-11 02:05:53', NULL);
INSERT INTO `periods` VALUES (4, '2026-03', 'Maret 2026', '2026-02-28', '2026-03-30', 'Open', 1, NULL, NULL, NULL, 1, 1, NULL, '2026-07-11 03:16:55', '2026-07-11 03:18:03', NULL);

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
INSERT INTO `users` VALUES (1, 'superadmin', 3, NULL, NULL, 1, '2026-08-04 04:15:59', '2026-06-08 06:04:01', '2026-06-16 06:51:56', NULL);
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
  INDEX `warehouses_created_by_foreign`(`created_by` ASC) USING BTREE,
  INDEX `warehouses_updated_by_foreign`(`updated_by` ASC) USING BTREE,
  INDEX `warehouses_deleted_by_foreign`(`deleted_by` ASC) USING BTREE,
  INDEX `warehouse_code`(`warehouse_code` ASC) USING BTREE,
  INDEX `warehouse_name`(`warehouse_name` ASC) USING BTREE,
  INDEX `department_id`(`department_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `deleted_at`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `warehouses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `warehouses_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `warehouses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `warehouses_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of warehouses
-- ----------------------------
INSERT INTO `warehouses` VALUES (1, 'G-DYE', 'Gudang Dyestuff', 1, NULL, NULL, 'Active', 1, 1, NULL, '2026-07-08 09:12:55', '2026-07-11 03:07:34', NULL);

SET FOREIGN_KEY_CHECKS = 1;
