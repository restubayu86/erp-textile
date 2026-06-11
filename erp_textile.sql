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

 Date: 11/06/2026 07:28:43
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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of auth_groups_users
-- ----------------------------
INSERT INTO `auth_groups_users` VALUES (1, 1, 'superadmin', '2026-06-08 06:04:01');

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of auth_identities
-- ----------------------------
INSERT INTO `auth_identities` VALUES (1, 1, 'email_password', NULL, 'admin@erp-textile.local', '$2y$12$3Td6Ec/unlo443/o1xGsceLJgHKZMCQoOct0kowDANV83/SGrijXO', NULL, NULL, 0, '2026-06-10 22:34:13', '2026-06-08 06:04:01', '2026-06-10 22:34:13');

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
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of auth_logins
-- ----------------------------
INSERT INTO `auth_logins` VALUES (1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 07:57:23', 1);
INSERT INTO `auth_logins` VALUES (2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', NULL, '2026-06-08 08:50:30', 0);
INSERT INTO `auth_logins` VALUES (3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 09:07:24', 1);
INSERT INTO `auth_logins` VALUES (4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-08 09:14:18', 1);
INSERT INTO `auth_logins` VALUES (5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-10 01:58:45', 1);
INSERT INTO `auth_logins` VALUES (6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'username', 'superadmin', 1, '2026-06-10 22:34:13', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of auth_token_logins
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES (1, 'DYE', 'DYEING', NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:32:57', '2026-06-10 02:32:57', NULL);
INSERT INTO `departments` VALUES (2, 'FIN', 'FINISHING', NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:33:13', '2026-06-10 02:33:13', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of employees
-- ----------------------------
INSERT INTO `employees` VALUES (3, '03.42.24.2576', 'Bagus Adhi N', 'Bagus', 'L', NULL, '', 1, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, '2026-06-10 09:26:04', NULL, NULL, NULL);
INSERT INTO `employees` VALUES (4, '06.70.90.0214', 'Jumar', 'Jumar', 'L', '', '', 2, 'Lab', 'NS', 'kontrak', NULL, 'inactive', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (5, '03.42.93.0381', 'Solihin', 'Solihin', 'L', '', '', 3, 'Office', 'NS', 'magang', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (6, '03.42.90.0200', 'Sujana', 'Sujana', 'L', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (7, '16.060.18', 'Rendi Oktiardi', 'Rendi', 'L', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (8, '16.05.066', 'Sinta Dewi', 'Sinta', 'P', '', '', 3, 'Office', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `employees` VALUES (9, '14.12.010', 'Alex Supriatna', 'Alex', 'L', '', '', 3, 'Gd Dyestuff', 'NS', 'tetap', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL);
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
INSERT INTO `employees` VALUES (23, '02.30.18.2274', 'Bayu Restu Fauzia', 'Bayu', 'L', NULL, NULL, 2, NULL, 'NS', 'tetap', '2026-02-26', 'active', '2026-06-10 22:43:42', 1, '2026-06-11 00:13:39', 1, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1780898628, 1);
INSERT INTO `migrations` VALUES (2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1780898628, 1);
INSERT INTO `migrations` VALUES (3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1780898628, 1);
INSERT INTO `migrations` VALUES (4, '2026-06-09-084705', 'App\\Database\\Migrations\\CreateDepartmentsTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);
INSERT INTO `migrations` VALUES (5, '2026-06-09-150318', 'App\\Modules\\HRM\\Database\\Migrations\\CreatePositionsTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);
INSERT INTO `migrations` VALUES (6, '2026-06-09-231443', 'App\\Modules\\HRM\\Database\\Migrations\\CreateEmployeesTable', 'default', 'App\\Modules\\HRM', 1781056781, 2);

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
  INDEX `department_id`(`department_id` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE,
  INDEX `idx_code_dept`(`position_code` ASC, `department_id` ASC) USING BTREE,
  INDEX `idx_name_dept`(`position_name` ASC, `department_id` ASC) USING BTREE,
  CONSTRAINT `positions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of positions
-- ----------------------------
INSERT INTO `positions` VALUES (1, 'MNG', 'Manager', 1, NULL, NULL, 'Active', 1, 1, NULL, '2026-06-10 02:32:11', '2026-06-10 23:07:09', NULL);
INSERT INTO `positions` VALUES (2, 'K-DYE', 'Kabag Dyeing', 2, 1, NULL, 'Active', 1, 1, NULL, '2026-06-10 02:32:40', '2026-06-10 02:33:27', NULL);
INSERT INTO `positions` VALUES (3, 'OP', 'Operator', 3, 1, NULL, 'Active', 1, NULL, NULL, '2026-06-10 02:34:12', '2026-06-10 02:34:12', NULL);
INSERT INTO `positions` VALUES (4, 'OPFIN', 'Operator', 3, 2, NULL, 'Active', 1, 1, NULL, '2026-06-10 06:34:16', '2026-06-10 23:22:24', NULL);
INSERT INTO `positions` VALUES (5, 'KABAG', 'Kabag Dyeing', 2, 2, NULL, 'Active', 1, 1, NULL, '2026-06-10 23:01:31', '2026-06-10 23:30:59', NULL);
INSERT INTO `positions` VALUES (6, 'SPV-DY', 'Supervisor', 3, 1, NULL, 'Active', 1, NULL, NULL, '2026-06-10 23:34:22', '2026-06-10 23:34:22', NULL);
INSERT INTO `positions` VALUES (7, 'SPV-FIN', 'Supervisor', 3, 2, NULL, 'Active', 1, NULL, NULL, '2026-06-10 23:36:29', '2026-06-10 23:36:29', NULL);
INSERT INTO `positions` VALUES (8, 'OP-A', 'Operator A', 4, 1, 'vdasdkas', 'Active', 1, NULL, NULL, '2026-06-10 23:45:25', '2026-06-10 23:45:25', NULL);
INSERT INTO `positions` VALUES (10, 'OP-A', 'Operator A', 4, 2, NULL, 'Active', 1, NULL, NULL, '2026-06-10 23:59:30', '2026-06-10 23:59:30', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'superadmin', NULL, NULL, 1, '2026-06-11 00:23:30', '2026-06-08 06:04:01', '2026-06-08 06:04:01', NULL);

SET FOREIGN_KEY_CHECKS = 1;
