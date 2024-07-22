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

-- Dumping structure for procedure hris_kpt.SP_ATTENDANCE_HAS_EMPLOYEES
DROP PROCEDURE IF EXISTS `SP_ATTENDANCE_HAS_EMPLOYEES`;
DELIMITER //
CREATE PROCEDURE `SP_ATTENDANCE_HAS_EMPLOYEES`(IN `DATE_FILTER` 
VARCHAR(25))
BEGIN 
	DELETE FROM
	    attendance_has_employees
	WHERE
	    DATE_FORMAT(`date`, '%Y-%m-%d') = DATE_FILTER;
	INSERT INTO
	    attendance_has_employees (
	        pin,
	        employee_id,
	        cloud_id,
	        date,
	        hour_start,
	        hour_end,
	        duration_work,
	        hour_rest_start,
	        hour_rest_end,
	        duration_rest,
	        hour_overtime_start,
	        hour_overtime_end,
	        duration_overtime,
	        hour_overtime_job_order_start,
	        hour_overtime_job_order_end,
	        duration_overtime_job_order
	    )
	SELECT
	    pin,
	    employee_id,
	    cloud_id,
	    date,
	    hour_start,
	    hour_end,
	    duration_work,
	    hour_rest_start,
	    hour_rest_end,
	    duration_rest, (
	        CASE
	            WHEN hour_overtime_job_order_start IS NULL THEN NULL
	            ELSE hour_overtime_start
	        END
	    ) as hour_overtime_start, (
	        CASE
	            WHEN hour_overtime_job_order_start IS NULL THEN NULL
	            ELSE hour_overtime_end
	        END
	    ) as hour_overtime_end, (
	        CASE
	            WHEN hour_overtime_job_order_start IS NULL THEN NULL
	            ELSE duration_overtime
	        END
	    ) as duration_overtime,
	    hour_overtime_job_order_start,
	    hour_overtime_job_order_end,
	    duration_overtime_job_order
	FROM vw_attendance
	WHERE
	    DATE_FORMAT(`date`, '%Y-%m-%d') = DATE_FILTER;
	END//
DELIMITER ;

-- Dumping structure for view hris_kpt.vw_attendance
DROP VIEW IF EXISTS `vw_attendance`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `vw_attendance` (
	`date` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci',
	`pin` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`cloud_id` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`hour_start` DATETIME NULL,
	`hour_end` DATETIME NULL,
	`duration_work` BIGINT(21) NULL,
	`hour_rest_start` DATETIME NULL,
	`hour_rest_end` DATETIME NULL,
	`duration_rest` BIGINT(21) NULL,
	`hour_overtime_start` DATETIME NULL,
	`hour_overtime_end` DATETIME NULL,
	`duration_overtime` BIGINT(21) NULL,
	`hour_overtime_job_order_start` VARCHAR(24) NULL COLLATE 'utf8mb4_general_ci',
	`hour_overtime_job_order_end` VARCHAR(24) NULL COLLATE 'utf8mb4_general_ci',
	`date_overtime_job_order_start` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci',
	`date_overtime_job_order_end` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci',
	`duration_overtime_job_order` BIGINT(21) NULL,
	`employee_id` BIGINT(20) UNSIGNED NULL,
	`name` VARCHAR(255) NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view hris_kpt.vw_attendance
DROP VIEW IF EXISTS `vw_attendance`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `vw_attendance`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_attendance` AS SELECT
	    DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
	    af.pin,
	    af.cloud_id,
	    af_hour_start.datetime_start AS hour_start,
	    af_hour_end.datetime_end AS hour_end,
	    TIMESTAMPDIFF(
	        MINUTE,
	        af_hour_start.datetime_start,
	        af_hour_end.datetime_end
	    ) as duration_work,
	    af_hour_rest.datetime_start AS hour_rest_start,
	    af_hour_rest.datetime_end AS hour_rest_end,
	    TIMESTAMPDIFF(
	        MINUTE,
	        af_hour_rest.datetime_start,
	        af_hour_rest.datetime_end
	    ) as duration_rest,
	    af_hour_overtime_start.datetime_overtime_start as hour_overtime_start,
	    af_hour_overtime_end.datetime_overtime_end as hour_overtime_end,
	    TIMESTAMPDIFF(
	        MINUTE,
	        af_hour_overtime_start.datetime_overtime_start,
	        af_hour_overtime_end.datetime_overtime_end
	    ) as duration_overtime,
	    DATE_FORMAT(
	        jt.datetime_start,
	        "%Y-%m-%d %H:%i:%s"
	    ) AS hour_overtime_job_order_start,
	    DATE_FORMAT(
	        jt.datetime_end,
	        "%Y-%m-%d %H:%i:%s"
	    ) AS hour_overtime_job_order_end,
	    DATE_FORMAT(jt.datetime_start, "%Y-%m-%d") AS date_overtime_job_order_start,
	    DATE_FORMAT(jt.datetime_end, "%Y-%m-%d") AS date_overtime_job_order_end,
	    TIMESTAMPDIFF(
	        MINUTE,
	        jt.datetime_start,
	        jt.datetime_end
	    ) as duration_overtime_job_order,
	    em.id as employee_id,
	    em.name
	FROM
	    attendance_fingerspots af
	    LEFT JOIN fingers fi ON af.pin = fi.id_finger
	    LEFT JOIN employees em ON fi.employee_id = em.id
	    LEFT JOIN job_status_has_parents jt ON fi.employee_id = jt.employee_id
	    AND jt.deleted_at IS NULL
	    AND DATE_FORMAT(jt.datetime_start, "%Y-%m-%d") = DATE_FORMAT(af.scan_date, "%Y-%m-%d")
	    AND jt.status = "overtime"
	    LEFT JOIN (
	        SELECT
	            af.pin,
	            af.cloud_id,
	            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
	            DATE_FORMAT(min(af.scan_date), "%H:%i") as hour_rest_start,
	            min(af.scan_date) as datetime_start, (
	                CASE
	                    WHEN max(af.scan_date) = min(af.scan_date) THEN NULL
	                    ELSE DATE_FORMAT(max(af.scan_date), "%H:%i")
	                END
	            ) as hour_rest_end, (
	                CASE
	                    WHEN max(af.scan_date) = min(af.scan_date) THEN NULL
	                    ELSE max(af.scan_date)
	                END
	            ) as datetime_end
	        FROM
	            attendance_fingerspots af
	            LEFT JOIN (
	                SELECT *
	                FROM
	                    working_hours
	                LIMIT
	                    1
	            ) as wh ON 1 = 1
	        WHERE
	            DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.start_rest, "%H:%i")
	            AND DATE_FORMAT(af.scan_date, "%H:%i") <= DATE_FORMAT(wh.end_rest, "%H:%i")
	        GROUP BY
	            af.pin,
	            af.cloud_id,
	            date
	    ) AS af_hour_rest ON af.pin = af_hour_rest.pin
	    AND af.cloud_id = af_hour_rest.cloud_id
	    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_rest.date
	    LEFT JOIN (
	        SELECT
	            af.pin,
	            af.cloud_id,
	            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
	            DATE_FORMAT(min(af.scan_date), "%H:%i") AS hour_start,
	            min(af.scan_date) AS datetime_start
	        FROM
	            attendance_fingerspots af
	            LEFT JOIN (
	                SELECT *
	                FROM
	                    working_hours
	                LIMIT
	                    1
	            ) as wh ON 1 = 1
	        WHERE
	            DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.start_time, "%H:%i")
	            AND DATE_FORMAT(af.scan_date, "%H:%i") <= DATE_FORMAT(wh.start_rest, "%H:%i")
	        GROUP BY
	            af.pin,
	            af.cloud_id,
	            date
	    ) AS af_hour_start ON af.pin = af_hour_start.pin
	    AND af.cloud_id = af_hour_start.cloud_id
	    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_start.date
	    LEFT JOIN (
	        SELECT
	            af.pin,
	            af.cloud_id,
	            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
	            DATE_FORMAT(max(af.scan_date), "%H:%i") AS hour_end,
	            max(af.scan_date) AS datetime_end
	        FROM
	            attendance_fingerspots af
	            LEFT JOIN (
	                SELECT *
	                FROM
	                    working_hours
	                LIMIT
	                    1
	            ) as wh ON 1 = 1
	        WHERE
	            DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.end_rest, "%H:%i")
	            AND DATE_FORMAT(af.scan_date, "%H:%i") <= DATE_FORMAT(wh.after_work_limit, "%H:%i")
	        GROUP BY
	            af.pin,
	            af.cloud_id,
	            date
	    ) AS af_hour_end ON af.pin = af_hour_end.pin
	    AND af.cloud_id = af_hour_end.cloud_id
	    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_end.date
	    LEFT JOIN (
	        SELECT
	            af.pin,
	            af.cloud_id,
	            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
	            DATE_FORMAT(min(af.scan_date), "%H:%i") AS hour_overtime_start,
	            min(af.scan_date) AS datetime_overtime_start
	        FROM
	            attendance_fingerspots af
	            LEFT JOIN (
	                SELECT *
	                FROM
	                    working_hours
	                LIMIT
	                    1
	            ) as wh ON 1 = 1
	        WHERE
	            DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.overtime_work, "%H:%i")
	            AND DATE_FORMAT(af.scan_date, "%H:%i") <= TIME_FORMAT(
	                DATE_ADD(
	                    wh.overtime_work,
	                    INTERVAL 7 HOUR
	                ),
	                "%H:%i"
	            )
	        GROUP BY
	            af.pin,
	            af.cloud_id,
	            date
	    ) AS af_hour_overtime_start ON af.pin = af_hour_overtime_start.pin
	    AND af.cloud_id = af_hour_overtime_start.cloud_id
	    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_overtime_start.date
	    LEFT JOIN (
	        SELECT
	            af.pin,
	            af.cloud_id,
	            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date", (
	                CASE
	                    WHEN max(af.scan_date) = min(af.scan_date) THEN NULL
	                    ELSE DATE_FORMAT(max(af.scan_date), "%H:%i")
	                END
	            ) AS hour_overtime_end,
	            max(af.scan_date) AS datetime_overtime_end
	        FROM
	            attendance_fingerspots af
	            LEFT JOIN (
	                SELECT *
	                FROM
	                    working_hours
	                LIMIT
	                    1
	            ) as wh ON 1 = 1
	        WHERE
	            DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.overtime_work, "%H:%i")
	        GROUP BY
	            af.pin,
	            af.cloud_id,
	            date
	    ) AS af_hour_overtime_end ON af.pin = af_hour_overtime_end.pin
	    AND af.cloud_id = af_hour_overtime_end.cloud_id
	    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") <= af_hour_overtime_end.date
	    AND af_hour_overtime_end.date <= DATE_FORMAT(
	        DATE_ADD(af.scan_date, INTERVAL 1 DAY),
	        "%Y-%m-%d"
	    )
	GROUP BY
	    DATE_FORMAT(af.scan_date, "%Y-%m-%d"),
	    af.pin,
	    af_hour_start.datetime_start,
	    af_hour_end.datetime_end,
	    duration_work,
	    af_hour_rest.datetime_start,
	    af_hour_rest.datetime_end,
	    duration_rest,
	    af_hour_overtime_start.datetime_overtime_start,
	    af_hour_overtime_end.datetime_overtime_end,
	    duration_overtime,
	    DATE_FORMAT(
	        jt.datetime_start,
	        "%Y-%m-%d %H:%i:%s"
	    ),
	    DATE_FORMAT(
	        jt.datetime_end,
	        "%Y-%m-%d %H:%i:%s"
	    ),
	    DATE_FORMAT(jt.datetime_start, "%Y-%m-%d"),
	    DATE_FORMAT(jt.datetime_end, "%Y-%m-%d"),
	    duration_overtime_job_order,
	    af.cloud_id,
	    employee_id,
	    em.name ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
