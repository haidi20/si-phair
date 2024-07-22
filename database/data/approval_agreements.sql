-- --------------------------------------------------------

-- Host:                         127.0.0.1

-- Server version:               5.7.33 - MySQL Community Server (GPL)

-- Server OS:                    Win64

-- HeidiSQL Version:             12.0.0.6468

-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */

;

/*!40101 SET NAMES utf8 */

;

/*!50503 SET NAMES utf8mb4 */

;

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */

;

/*!40103 SET TIME_ZONE='+00:00' */

;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */

;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */

;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */

;

-- Dumping data for table hris_kpt.approval_agreements: ~3 rows (approximately)

DELETE FROM `approval_agreements`;

INSERT INTO
    `approval_agreements` (
        `id`,
        `approval_level_id`,
        `user_id`,
        `user_behalf_id`,
        `model_id`,
        `name_model`,
        `status_approval`,
        `level_approval`,
        `note`,
        `created_by`,
        `updated_by`,
        `deleted_by`,
        `created_at`,
        `updated_at`,
        `deleted_at`
    )
VALUES (
        1,
        1,
        3,
        NULL,
        1,
        'App\\Models\\SalaryAdvance',
        'review',
        1,
        NULL,
        1,
        NULL,
        NULL,
        '2023-05-23 05:49:04',
        '2023-05-23 05:49:04',
        NULL
    ), (
        2,
        1,
        2,
        NULL,
        1,
        'App\\Models\\SalaryAdvance',
        'not yet',
        2,
        NULL,
        1,
        NULL,
        NULL,
        '2023-05-23 05:49:04',
        '2023-05-23 05:49:04',
        NULL
    ), (
        3,
        1,
        4,
        NULL,
        1,
        'App\\Models\\SalaryAdvance',
        'not yet',
        3,
        NULL,
        1,
        NULL,
        NULL,
        '2023-05-23 05:49:04',
        '2023-05-23 05:49:04',
        NULL
    );

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */

;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */

;

/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */

;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */

;

/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */

;