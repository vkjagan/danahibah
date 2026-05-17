-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for danahibah
CREATE DATABASE IF NOT EXISTS `danahibah` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `danahibah`;

-- Dumping structure for table danahibah.audit_logs
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `branch_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `record_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`),
  KEY `branch_id` (`branch_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.audit_logs: ~14 rows (approximately)
DELETE FROM `audit_logs`;
INSERT INTO `audit_logs` (`id`, `user_id`, `branch_id`, `action`, `module`, `description`, `record_id`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 1, NULL, 'update', 'settings', 'Updated system settings', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 00:51:41'),
	(2, 1, NULL, 'create', 'branches', 'Added branch: Masjid Tun Abdul Aziz (Masjid Bulat)', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 01:00:51'),
	(3, 1, NULL, 'update', 'branches', 'Updated branch: Masjid Tun Abdul Aziz (Masjid Bulat)', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 01:06:44'),
	(4, 1, NULL, 'update', 'branches', 'Updated branch: Masjid Tun Abdul Aziz (Masjid Bulat)', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 01:08:00'),
	(5, 1, NULL, 'update', 'branches', 'Updated branch: Masjid Tun Abdul Aziz (Masjid Bulat)', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 02:09:17'),
	(6, 1, NULL, 'update', 'settings', 'Updated system settings', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 02:12:42'),
	(7, 1, NULL, 'update', 'branches', 'Changed status of Masjid Tun Abdul Aziz (Masjid Bulat) to active', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 02:27:58'),
	(8, 1, NULL, 'create', 'branches', 'Added branch: Test Branch E2E', 2, '::1', '', '2026-05-17 02:29:53'),
	(9, 1, NULL, 'create', 'collections', 'Added collection TXN-6D496EFC', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 03:51:13'),
	(10, 1, NULL, 'create', 'devices', 'Registered device: WER', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 03:52:53'),
	(11, 1, NULL, 'update', 'devices', 'Updated device: WER', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 03:52:59'),
	(12, 1, NULL, 'create', 'users', 'Created user: comm', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:00:08'),
	(13, 1, NULL, 'update', 'users', 'Changed status of comm to active', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:00:19'),
	(14, 1, NULL, 'create', 'users', 'Created user: suadmin', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:01:20'),
	(15, 1, NULL, 'update', 'users', 'Changed status of admin to active', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:03:21'),
	(16, 1, NULL, 'create', 'users', 'Created user: mgmt', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:04:14'),
	(17, 1, NULL, 'update', 'users', 'Updated user: mgmt', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:04:29'),
	(18, 1, NULL, 'update', 'users', 'Updated user: mgmt', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:05:24'),
	(19, 3, NULL, 'update', 'users', 'Updated user: comm', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:21:17'),
	(20, 3, NULL, 'update', 'users', 'Updated user: admin', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:21:23'),
	(21, 2, NULL, 'create', 'collections', 'Added collection TXN-F1E40CD2', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:28:22'),
	(22, 3, 1, 'update', 'collections', 'Approval action: verified on collection #1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:35:48'),
	(23, 3, 1, 'update', 'collections', 'Approval action: verified on collection #2', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:36:30'),
	(24, 3, 1, 'update', 'collections', 'Approval action: approved on collection #1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:36:54'),
	(25, 3, 1, 'update', 'collections', 'Approval action: approved on collection #2', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:38:48'),
	(26, 3, 1, 'update', 'collections', 'Approval action: banked on collection #1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:38:52'),
	(27, 3, 1, 'update', 'collections', 'Approval action: banked on collection #2', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:40:06'),
	(28, 2, 1, 'create', 'collections', 'Added collection TXN-C450B0C5', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:42:01'),
	(29, 3, 1, 'update', 'collections', 'Approval action: verified on collection #3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:42:15'),
	(30, 3, 1, 'update', 'collections', 'Approval action: verified on collection #3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:42:30'),
	(31, 3, 1, 'create', 'collections', 'Added collection TXN-7E00EAF5', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:44:07'),
	(32, 3, 1, 'update', 'collections', 'Approval action: verified on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:44:43'),
	(33, 3, 1, 'update', 'collections', 'Approval action: verified on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:45:32'),
	(34, 3, 1, 'update', 'collections', 'Approval action: verified on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:45:48'),
	(35, 3, 1, 'update', 'collections', 'Approval action: verified on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:45:53'),
	(36, 3, 1, 'update', 'collections', 'Approval action: verified on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:46:05'),
	(37, 3, 1, 'update', 'collections', 'Approval action: approved on collection #3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:46:46'),
	(38, 3, 1, 'update', 'collections', 'Approval action: banked on collection #3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:47:06'),
	(39, 3, 1, 'update', 'collections', 'Approval action: approved on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:47:27'),
	(40, 3, 1, 'update', 'collections', 'Approval action: banked on collection #4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 04:48:50'),
	(41, 3, 1, 'delete', 'collections', 'Deleted collection: TXN-7E00EAF5', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 12:31:12'),
	(42, 3, 1, 'delete', 'collections', 'Deleted collection: TXN-6D496EFC', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 12:31:29'),
	(43, 2, 1, 'create', 'expenses', 'Recorded expense: Maintenance', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 12:52:13'),
	(44, 1, NULL, 'update', 'expenses', 'Updated expense: Maintenance', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 14:41:53'),
	(45, 3, 1, 'create', 'collections', 'Added collection TXN-16EDD869', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 14:59:23'),
	(46, 3, 1, 'update', 'collections', 'Approval action: verified on collection #5', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 14:59:57'),
	(47, 3, 1, 'update', 'collections', 'Approval action: approved on collection #5', 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:00:19'),
	(48, 3, 1, 'create', 'bank_deposits', 'Recorded bank deposit of RM RM 500.00', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:01:45'),
	(49, 3, 1, 'create', 'expenses', 'Recorded expense: Test Cash Expense', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:02:57'),
	(50, 2, 1, 'create', 'collections', 'Added collection TXN-04C46777', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:40:55'),
	(51, 2, 1, 'create', 'expenses', 'Recorded expense: test', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:41:34'),
	(52, 2, 1, 'create', 'bank_deposits', 'Recorded bank deposit of RM RM 100.00', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 15:41:48'),
	(53, 2, 1, 'create', 'collections', 'Added collection TXN-F8EF4774', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 16:30:55'),
	(54, 3, 1, 'update', 'collections', 'Approval action: verified on collection #1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 16:31:48'),
	(55, 2, 1, 'create', 'expenses', 'Recorded expense: misc', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 16:34:12'),
	(56, 2, 1, 'create', 'expenses', 'Recorded expense: asd', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 16:51:11'),
	(57, 3, 1, 'delete', 'expenses', 'Deleted expense: asd', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:04:28'),
	(58, 3, 1, 'approve', 'expenses', 'Approved expense ID: 1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:07:29'),
	(59, 3, 1, 'update', 'expenses', 'Updated expense: misc', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:07:42'),
	(60, 3, 1, 'create', 'expenses', 'Recorded expense: adf', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:53:32'),
	(61, 3, 1, 'approve', 'expenses', 'Approved expense ID: 3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:53:35'),
	(62, 3, 1, 'create', 'expenses', 'Recorded expense: asd', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 17:53:56'),
	(63, 3, 1, 'update', 'expenses', 'Updated expense: asd', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:03:41'),
	(64, 3, 1, 'approve', 'expenses', 'Approved expense ID: 4', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:03:44'),
	(65, 3, 1, 'create', 'bank_deposits', 'Recorded bank deposit of RM RM 4.00', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:06:27'),
	(66, 3, 1, 'update', 'collections', 'Approval action: approved on collection #1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:06:44'),
	(67, 2, 1, 'update', 'devices', 'Updated device: WER', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:40:42'),
	(68, 2, 1, 'update', 'devices', 'Updated device: MTAA-DEV001', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:41:22'),
	(69, 2, 1, 'create', 'collections', 'Added collection TXN-63EA7A8E', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:42:38'),
	(70, 2, 1, 'create', 'expenses', 'Recorded expense: qwqwe', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 18:42:52'),
	(71, 2, 1, 'create', 'collections', 'Added collection TXN-780484E8', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 22:28:00'),
	(72, 3, 1, 'update', 'collections', 'Approval action: verified on collection #3', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 22:30:34'),
	(73, 2, 1, 'create', 'expenses', 'Recorded expense: test', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 22:31:48'),
	(74, 3, 1, 'approve', 'expenses', 'Approved expense ID: 6', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 22:32:13'),
	(75, 2, 1, 'create', 'bank_deposits', 'Recorded bank deposit of RM RM 2,000.00', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 22:33:09');

-- Dumping structure for table danahibah.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `type` enum('masjid','surau','wakaf','ngo','other') DEFAULT 'masjid',
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `pic_name` varchar(150) DEFAULT NULL,
  `pic_phone` varchar(20) DEFAULT NULL,
  `pic_email` varchar(150) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.branches: ~1 rows (approximately)
DELETE FROM `branches`;
INSERT INTO `branches` (`id`, `name`, `code`, `type`, `address`, `city`, `state`, `postcode`, `pic_name`, `pic_phone`, `pic_email`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Masjid Tun Abdul Aziz (Masjid Bulat)', 'MSJ-001', 'masjid', 'Aras Bawah, Jalan Professor Khoo Kay Kim, Seksyen 14, 46100 Petaling Jaya, Selangor, Malaysia', 'Petaling Jaya', 'Selangor', '46100', 'Masjid Tun Abdul Aziz', '03-7931 0022', '', 'active', 1, 1, '2026-05-17 01:00:51', '2026-05-17 02:27:58', NULL),
	(2, 'Test Branch E2E', 'TEST-001', 'masjid', '', '', '', '', '', '', '', 'active', 1, NULL, '2026-05-17 02:29:52', '2026-05-17 02:29:52', NULL);

-- Dumping structure for table danahibah.collections
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(10) unsigned NOT NULL,
  `device_id` int(10) unsigned DEFAULT NULL,
  `txn_ref` varchar(50) DEFAULT NULL,
  `channel` enum('cash','qr','manual','online') DEFAULT 'cash',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(5) DEFAULT 'MYR',
  `donor_name` varchar(150) DEFAULT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `category` enum('general','friday','zakat','wakaf','special','sadaqah') DEFAULT 'general',
  `notes` text DEFAULT NULL,
  `receipt_no` varchar(50) DEFAULT NULL,
  `bank_receipt_file` varchar(255) DEFAULT NULL,
  `bank_ref_no` varchar(100) DEFAULT NULL,
  `status` enum('collected','verified','approved','banked','rejected') DEFAULT 'collected',
  `collected_at` datetime DEFAULT current_timestamp(),
  `collected_by` int(10) unsigned DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `txn_ref` (`txn_ref`),
  KEY `device_id` (`device_id`),
  KEY `idx_branch` (`branch_id`),
  KEY `idx_status` (`status`),
  KEY `idx_channel` (`channel`),
  KEY `idx_date` (`collected_at`),
  CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `collections_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.collections: ~1 rows (approximately)
DELETE FROM `collections`;
INSERT INTO `collections` (`id`, `branch_id`, `device_id`, `txn_ref`, `channel`, `amount`, `currency`, `donor_name`, `donor_phone`, `category`, `notes`, `receipt_no`, `bank_receipt_file`, `bank_ref_no`, `status`, `collected_at`, `collected_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, NULL, 'TXN-F8EF4774', 'cash', 100.00, 'MYR', '', '', 'general', '', 'RCP-20260517-6267', NULL, NULL, 'approved', '2026-05-17 16:30:00', 2, 2, 3, '2026-05-17 16:30:55', '2026-05-17 18:06:44', NULL),
	(2, 1, 1, 'TXN-63EA7A8E', 'cash', 5000.00, 'MYR', '', '', 'general', '', 'RCP-20260517-4260', NULL, NULL, 'collected', '2026-05-17 18:42:00', 2, 2, NULL, '2026-05-17 18:42:38', '2026-05-17 18:42:38', NULL),
	(3, 1, 1, 'TXN-780484E8', 'cash', 2500.00, 'MYR', '', '', 'general', 'test', 'RCP-20260517-4484', NULL, NULL, 'verified', '2026-05-17 22:27:00', 2, 2, 3, '2026-05-17 22:28:00', '2026-05-17 22:30:34', NULL);

-- Dumping structure for table danahibah.collection_approvals
CREATE TABLE IF NOT EXISTS `collection_approvals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `collection_id` int(10) unsigned NOT NULL,
  `step` enum('verified','approved','banked','rejected') NOT NULL,
  `remarks` text DEFAULT NULL,
  `actioned_by` int(10) unsigned NOT NULL,
  `actioned_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `collection_id` (`collection_id`),
  KEY `actioned_by` (`actioned_by`),
  CONSTRAINT `collection_approvals_ibfk_1` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_approvals_ibfk_2` FOREIGN KEY (`actioned_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.collection_approvals: ~0 rows (approximately)
DELETE FROM `collection_approvals`;
INSERT INTO `collection_approvals` (`id`, `collection_id`, `step`, `remarks`, `actioned_by`, `actioned_at`) VALUES
	(1, 1, 'verified', '', 3, '2026-05-17 04:35:48'),
	(2, 2, 'verified', 'Testing via JS', 3, '2026-05-17 04:36:30'),
	(3, 1, 'approved', '', 3, '2026-05-17 04:36:54'),
	(4, 2, 'approved', '', 3, '2026-05-17 04:38:48'),
	(5, 1, 'banked', '', 3, '2026-05-17 04:38:52'),
	(6, 2, 'banked', '', 3, '2026-05-17 04:40:06'),
	(7, 3, 'verified', '', 3, '2026-05-17 04:42:15'),
	(8, 3, 'verified', '', 3, '2026-05-17 04:42:30'),
	(9, 4, 'verified', '', 3, '2026-05-17 04:44:43'),
	(10, 4, 'verified', '', 3, '2026-05-17 04:45:32'),
	(11, 4, 'verified', '', 3, '2026-05-17 04:45:48'),
	(12, 4, 'verified', '', 3, '2026-05-17 04:45:53'),
	(13, 4, 'verified', '', 3, '2026-05-17 04:46:05'),
	(14, 3, 'approved', '', 3, '2026-05-17 04:46:46'),
	(15, 3, 'banked', '445', 3, '2026-05-17 04:47:06'),
	(16, 4, 'approved', '', 3, '2026-05-17 04:47:27'),
	(17, 4, 'banked', 'wer', 3, '2026-05-17 04:48:50'),
	(18, 5, 'verified', '', 3, '2026-05-17 14:59:57'),
	(19, 5, 'approved', '', 3, '2026-05-17 15:00:19'),
	(20, 1, 'verified', '', 3, '2026-05-17 16:31:48'),
	(21, 1, 'approved', '', 3, '2026-05-17 18:06:44'),
	(22, 3, 'verified', '', 3, '2026-05-17 22:30:34');

-- Dumping structure for table danahibah.devices
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(10) unsigned NOT NULL,
  `serial_no` varchar(100) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `type` enum('cash_box','qr_terminal','hybrid') DEFAULT 'hybrid',
  `status` enum('online','offline','tampered','maintenance') DEFAULT 'offline',
  `last_sync` datetime DEFAULT NULL,
  `firmware_ver` varchar(50) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_no` (`serial_no`),
  KEY `branch_id` (`branch_id`),
  CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.devices: ~1 rows (approximately)
DELETE FROM `devices`;
INSERT INTO `devices` (`id`, `branch_id`, `serial_no`, `model`, `type`, `status`, `last_sync`, `firmware_ver`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'MTAA-DEV001', 'Donation Box', 'cash_box', 'online', NULL, '001', 1, 2, '2026-05-17 03:52:53', '2026-05-17 18:41:22', NULL);

-- Dumping structure for table danahibah.login_attempts
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(150) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempted_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.login_attempts: ~0 rows (approximately)
DELETE FROM `login_attempts`;

-- Dumping structure for table danahibah.login_logs
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.login_logs: ~14 rows (approximately)
DELETE FROM `login_logs`;
INSERT INTO `login_logs` (`id`, `user_id`, `ip_address`, `user_agent`, `status`, `created_at`) VALUES
	(1, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:40:32'),
	(2, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:40:51'),
	(3, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:41:22'),
	(4, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:42:31'),
	(5, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:42:37'),
	(6, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:45:45'),
	(7, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 00:45:57'),
	(8, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 00:47:21'),
	(9, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 02:04:03'),
	(10, 1, '::1', '', 'success', '2026-05-17 02:29:52'),
	(11, 1, '::1', '', 'success', '2026-05-17 02:31:00'),
	(12, 1, '::1', '', 'success', '2026-05-17 02:31:51'),
	(13, 1, '::1', '', 'success', '2026-05-17 02:35:45'),
	(14, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 03:46:38'),
	(15, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 04:12:37'),
	(16, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:12:46'),
	(17, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:13:12'),
	(18, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 04:15:37'),
	(19, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:15:54'),
	(20, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:17:41'),
	(21, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:17:58'),
	(22, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:18:15'),
	(23, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:21:51'),
	(24, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:28:53'),
	(25, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:41:17'),
	(26, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 04:42:08'),
	(27, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 12:16:01'),
	(28, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 12:16:06'),
	(29, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 12:46:34'),
	(30, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 12:47:37'),
	(31, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 14:33:42'),
	(32, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 14:58:50'),
	(33, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 15:40:24'),
	(34, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 15:42:01'),
	(35, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 15:42:06'),
	(36, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 16:20:45'),
	(37, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 16:31:41'),
	(38, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 16:32:02'),
	(39, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 17:02:57'),
	(40, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 18:28:35'),
	(41, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 18:38:43'),
	(42, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 22:25:09'),
	(43, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:25:18'),
	(44, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:30:06'),
	(45, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:31:12'),
	(46, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:32:01'),
	(47, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:32:31'),
	(48, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:35:41'),
	(49, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:36:57'),
	(50, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 22:41:06'),
	(51, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'failed', '2026-05-17 23:54:09'),
	(52, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 23:54:13'),
	(53, 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-17 23:56:26'),
	(54, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'success', '2026-05-18 00:52:48');

-- Dumping structure for table danahibah.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_module_action` (`module`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.permissions: ~0 rows (approximately)
DELETE FROM `permissions`;

-- Dumping structure for table danahibah.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.roles: ~4 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `label`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Administrator', 'Full system access', '2026-05-17 00:29:01', '2026-05-17 00:29:01'),
	(2, 'committee', 'Committee Member', 'Collection management and reports', '2026-05-17 00:29:01', '2026-05-17 00:29:01'),
	(3, 'viewer', 'Viewer', 'Read-only access to reports', '2026-05-17 00:29:01', '2026-05-17 00:29:01'),
	(4, 'management', 'Management', 'Full system access', '2026-05-17 04:05:07', '2026-05-17 04:05:11');

-- Dumping structure for table danahibah.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_perm` (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.role_permissions: ~0 rows (approximately)
DELETE FROM `role_permissions`;

-- Dumping structure for table danahibah.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(50) DEFAULT 'general',
  `label` varchar(150) DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.settings: ~16 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `group`, `label`, `updated_by`, `updated_at`) VALUES
	(1, 'app_name', 'DanaHibah', 'general', 'Application Name', 1, '2026-05-17 02:12:40'),
	(2, 'app_tagline', 'Secure. Transparent. Amanah.', 'general', 'Tagline', 1, '2026-05-17 02:12:41'),
	(3, 'app_logo', '', 'general', 'Logo Path', NULL, '2026-05-17 00:29:15'),
	(4, 'contact_email', 'hello@danahibah.com', 'general', 'Contact Email', 1, '2026-05-17 02:12:41'),
	(5, 'contact_phone', '+60 12-345 6789', 'general', 'Contact Phone', 1, '2026-05-17 02:12:41'),
	(6, 'smtp_host', 'smtp.mailtrap.io', 'email', 'SMTP Host', 1, '2026-05-17 02:12:41'),
	(7, 'smtp_port', '587', 'email', 'SMTP Port', 1, '2026-05-17 02:12:41'),
	(8, 'smtp_user', '', 'email', 'SMTP Username', 1, '2026-05-17 02:12:41'),
	(9, 'smtp_pass', '', 'email', 'SMTP Password', 1, '2026-05-17 02:12:41'),
	(10, 'smtp_from_name', 'DanaHibahÔäó', 'email', 'From Name', 1, '2026-05-17 02:12:41'),
	(11, 'smtp_from_email', 'noreply@danahibah.com', 'email', 'From Email', 1, '2026-05-17 02:12:41'),
	(12, 'meta_title', 'DanaHibah | Sistem Tadbir Derma', 'seo', 'Meta Title', 1, '2026-05-17 02:12:41'),
	(13, 'meta_desc', 'Sistem kutipan derma digital masjid', 'seo', 'Meta Description', 1, '2026-05-17 02:12:41'),
	(14, 'per_page', '25', 'general', 'Records Per Page', 1, '2026-05-17 02:12:41'),
	(15, 'session_timeout', '1800', 'security', 'Session Timeout (seconds)', 1, '2026-05-17 02:12:41'),
	(16, 'max_attempts', '5', 'security', 'Max Login Attempts', 1, '2026-05-17 02:12:42');

-- Dumping structure for table danahibah.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT 2,
  `branch_id` int(10) unsigned DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `fk_user_branch` (`branch_id`),
  CONSTRAINT `fk_user_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.users: ~4 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `role_id`, `branch_id`, `full_name`, `username`, `email`, `phone`, `password`, `avatar`, `status`, `last_login`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, NULL, 'System Administrator', 'suadmin', 'suadmin@mail.com', NULL, '$2y$12$Vnyh3q.LDxmwQfeOyZwDlexd884fG7wzlWJHrMAAy9pHtYwwsZwS2', NULL, 'active', '2026-05-18 00:52:48', NULL, NULL, '2026-05-17 00:29:03', '2026-05-18 00:52:48', NULL),
	(2, 2, 1, 'ali_committee', 'comm', 'comm@mail.com', '123123', '$2y$12$7q2Agvdjp38xfwSKli2u3eLgrc8x/neQXnDJpTlInr0WtGsrki/o6', NULL, 'active', '2026-05-17 22:41:06', 1, 3, '2026-05-17 04:00:08', '2026-05-17 22:41:06', NULL),
	(3, 1, 1, 'admin', 'admin', 'admin@mail.com', '', '$2y$12$L.5A5vigAWOkDVoj2iWNn.g96mZOeyS7RtAfBOfZEUczyAkPIqAIG', NULL, 'active', '2026-05-17 23:56:26', 1, 3, '2026-05-17 04:01:20', '2026-05-17 23:56:26', NULL),
	(4, 4, NULL, 'ceo_monitor', 'mgmt', 'mgmt@mail.com', '', '$2y$12$JTyI.V7P7nMhjfzJJUTxH.yExnh24/.zn4bs1fTOlpGbJNVJS.EGy', NULL, 'active', '2026-05-17 22:35:41', 1, 1, '2026-05-17 04:04:14', '2026-05-17 22:35:41', NULL);

-- Dumping structure for table danahibah.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_role` (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table danahibah.user_roles: ~0 rows (approximately)
DELETE FROM `user_roles`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
