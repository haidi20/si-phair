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

-- Dumping data for table hris_kpt.job_status_has_parents: ~8 rows (approximately)
INSERT INTO `job_status_has_parents` (`id`, `parent_id`, `parent_model`, `job_order_id`, `employee_id`, `status`, `datetime_start`, `datetime_end`, `note_start`, `note_end`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'App\\Models\\JobOrder', 1, NULL, 'active', '2023-06-05 14:07:00', NULL, NULL, NULL, 5, NULL, NULL, '2023-06-05 06:08:57', '2023-06-05 06:08:57', NULL),
	(2, 1, 'App\\Models\\JobOrderHasEmployee', 1, 26, 'active', '2023-06-05 14:07:00', NULL, NULL, NULL, 5, NULL, NULL, '2023-06-05 06:08:57', '2023-06-05 06:08:57', NULL),
	(3, 2, 'App\\Models\\JobOrderHasEmployee', 1, 15, 'active', '2023-06-05 14:07:00', NULL, NULL, NULL, 5, NULL, NULL, '2023-06-05 06:08:57', '2023-06-05 06:08:57', NULL),
	(4, 3, 'App\\Models\\JobOrderHasEmployee', 1, 27, 'active', '2023-06-05 14:07:00', NULL, NULL, NULL, 5, NULL, NULL, '2023-06-05 06:08:57', '2023-06-05 06:08:57', NULL),
	(5, 1, 'App\\Models\\JobOrder', 1, NULL, 'overtime', '2023-06-02 17:00:00', '2023-06-02 22:00:00', NULL, NULL, 5, 5, NULL, '2023-06-05 06:09:14', '2023-06-05 06:09:27', NULL),
	(6, 1, 'App\\Models\\JobOrderHasEmployee', 1, 26, 'overtime', '2023-06-02 17:00:00', '2023-06-02 22:00:00', NULL, NULL, 5, 5, NULL, '2023-06-05 06:09:14', '2023-06-05 06:09:27', NULL),
	(7, 2, 'App\\Models\\JobOrderHasEmployee', 1, 15, 'overtime', '2023-06-02 17:00:00', '2023-06-02 22:00:00', NULL, NULL, 5, 5, NULL, '2023-06-05 06:09:14', '2023-06-05 06:09:27', NULL),
	(8, 3, 'App\\Models\\JobOrderHasEmployee', 1, 27, 'overtime', '2023-06-02 17:00:00', '2023-06-02 22:00:00', NULL, NULL, 5, 5, NULL, '2023-06-05 06:09:14', '2023-06-05 06:09:27', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
