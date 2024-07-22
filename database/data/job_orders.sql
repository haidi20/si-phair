-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table hris_kpt.job_orders: ~1 rows (approximately)
DELETE FROM `job_orders`;
INSERT INTO `job_orders` (`id`, `project_id`, `job_id`, `job_level`, `job_another_name`, `job_note`, `datetime_start`, `datetime_end`, `datetime_estimation_end`, `estimation`, `time_type`, `category`, `status`, `status_note`, `note`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 512, 117, 'easy', NULL, 'sdfsdf', '2023-06-20 11:59:00', NULL, '2023-06-20 12:59:00', 1, 'hours', 'reguler', 'active', NULL, NULL, 1, 1, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:54', NULL);

-- Dumping data for table hris_kpt.job_order_has_employees: ~3 rows (approximately)
DELETE FROM `job_order_has_employees`;
INSERT INTO `job_order_has_employees` (`id`, `employee_id`, `project_id`, `job_order_id`, `status`, `datetime_start`, `datetime_end`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 79, 512, 1, 'active', '2023-07-04 12:00:39', NULL, 1, 1, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:54', NULL),
	(2, 14, 512, 1, 'active', '2023-07-04 12:00:39', NULL, 1, 1, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:54', NULL),
	(3, 64, 512, 1, 'active', '2023-07-04 12:00:39', NULL, 1, 1, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:54', NULL);

-- Dumping data for table hris_kpt.job_status_has_parents: ~8 rows (approximately)
DELETE FROM `job_status_has_parents`;
INSERT INTO `job_status_has_parents` (`id`, `parent_id`, `parent_model`, `job_order_id`, `employee_id`, `status`, `datetime_start`, `datetime_end`, `note_start`, `note_end`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'App\\Models\\JobOrder', 1, NULL, 'active', '2023-07-04 11:59:00', NULL, NULL, NULL, 1, NULL, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:39', NULL),
	(2, 1, 'App\\Models\\JobOrderHasEmployee', 1, 79, 'active', '2023-07-04 12:00:39', NULL, NULL, NULL, 1, NULL, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:39', NULL),
	(3, 2, 'App\\Models\\JobOrderHasEmployee', 1, 14, 'active', '2023-07-04 12:00:39', NULL, NULL, NULL, 1, NULL, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:39', NULL),
	(4, 3, 'App\\Models\\JobOrderHasEmployee', 1, 64, 'active', '2023-07-04 12:00:39', NULL, NULL, NULL, 1, NULL, NULL, '2023-07-04 04:00:39', '2023-07-04 04:00:39', NULL),
	(5, 1, 'App\\Models\\JobOrderHasEmployee', 1, 79, 'overtime', '2023-06-20 17:00:00', '2023-06-20 22:30:00', NULL, NULL, 1, 1, NULL, '2023-07-04 04:00:46', '2023-07-04 04:00:54', NULL),
	(6, 2, 'App\\Models\\JobOrderHasEmployee', 1, 14, 'overtime', '2023-06-20 17:00:00', '2023-06-20 22:30:00', NULL, NULL, 1, 1, NULL, '2023-07-04 04:00:46', '2023-07-04 04:00:54', NULL),
	(7, 3, 'App\\Models\\JobOrderHasEmployee', 1, 64, 'overtime', '2023-06-20 17:00:00', '2023-06-20 22:30:00', NULL, NULL, 1, 1, NULL, '2023-07-04 04:00:46', '2023-07-04 04:00:54', NULL),
	(8, 1, 'App\\Models\\JobOrder', 1, NULL, 'overtime', '2023-06-20 17:00:00', '2023-06-20 22:30:00', NULL, NULL, 1, 1, NULL, '2023-07-04 04:00:46', '2023-07-04 04:00:54', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
