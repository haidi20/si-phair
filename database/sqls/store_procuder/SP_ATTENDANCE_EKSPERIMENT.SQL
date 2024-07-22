-- Active: 1685931372338@@127.0.0.1@3306@hris_kpt

-- DROP PROCEDURE IF EXISTS `SP_ATTENDANCE`;

-- DELIMITER /

-- CREATE PROCEDURE SP_ATTENDANCE(IN `DATE_FILTER` VARCHAR

-- (25)) BEGIN

SET @DATE_FILTER = "2023-07-20";

SELECT
    date,
    employee_id,
    hour_start,
    hour_end,
    max_hour_end,
    duration_work,
    hour_rest_start,
    hour_rest_end,
    duration_rest,
    hour_overtime_start,
    hour_overtime_end,
    duration_overtime,
    hour_overtime_job_order_start,
    hour_overtime_job_order_end,
    date_overtime_job_order_start,
    date_overtime_job_order_end,
    duration_overtime_job_order
FROM (
        SELECT
            DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
            em.id as employee_id,
            -- af.pin,
            -- af.cloud_id,
            af_hour_start.datetime_start AS hour_start,
            af_hour_end.datetime_end AS hour_end,
            af_hour_end.max_hour_end AS max_hour_end,
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
            ) as duration_rest, (
                CASE
                    WHEN af_hour_overtime_start.datetime_overtime_start = af_hour_end.datetime_end THEN NULL
                    ELSE af_hour_overtime_start.datetime_overtime_start
                END
            ) as hour_overtime_start, (
                CASE
                    WHEN af_hour_overtime_start.datetime_overtime_start = af_hour_end.datetime_end THEN NULL
                    ELSE af_hour_overtime_end.datetime_overtime_end
                END
            ) as hour_overtime_end, (
                CASE
                    WHEN af_hour_overtime_start.datetime_overtime_start = af_hour_end.datetime_end THEN NULL
                    ELSE TIMESTAMPDIFF(
                        MINUTE,
                        af_hour_overtime_start.datetime_overtime_start,
                        af_hour_overtime_end.datetime_overtime_end
                    )
                END
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
            ) as duration_overtime_job_order -- em.name
        FROM
            attendance_fingerspots af
            LEFT JOIN fingers fi ON af.pin = fi.id_finger
            LEFT JOIN employees em ON fi.employee_id = em.id
            AND em.employee_status = 'aktif'
            AND em.deleted_at IS NULL
            LEFT JOIN job_status_has_parents jt ON fi.employee_id = jt.employee_id
            AND jt.deleted_at IS NULL
            AND DATE_FORMAT(jt.datetime_start, "%Y-%m-%d") = DATE_FORMAT(af.scan_date, "%Y-%m-%d")
            AND jt.status = "overtime"
            LEFT JOIN (
                SELECT *
                FROM
                    working_hours
                LIMIT
                    1
            ) as wh ON 1 = 1
            LEFT JOIN (
                SELECT
                    af.pin,
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
                        SELECT
                            *
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
                    date
            ) AS af_hour_rest ON af.pin = af_hour_rest.pin
            AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_rest.date
            LEFT JOIN (
                SELECT
                    af.pin,
                    DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
                    DATE_FORMAT(min(af.scan_date), "%H:%i") AS hour_start,
                    min(af.scan_date) AS datetime_start
                FROM
                    attendance_fingerspots af
                    LEFT JOIN (
                        SELECT
                            *
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
                    date
            ) AS af_hour_start ON af.pin = af_hour_start.pin
            AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_start.date
            LEFT JOIN (
                SELECT
                    af.pin,
                    DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
                    DATE_FORMAT(max(af.scan_date), "%H:%i") AS hour_end,
                    max(af.scan_date) AS datetime_end,
                    DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(
                                CONCAT(
                                    DATE_FORMAT(af.scan_date, '%Y-%m-%d '),
                                    DATE_FORMAT(wh.start_time, '%H:%i')
                                ),
                                '%Y-%m-%d %H:%i'
                            ),
                            INTERVAL 22 HOUR
                        ),
                        "%Y-%m-%d %H:%i"
                    ) as max_hour_end
                FROM
                    attendance_fingerspots af
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            working_hours
                        LIMIT
                            1
                    ) as wh ON 1 = 1
                WHERE
                    -- DATE_FORMAT(wh.fastest_time, "%H:%i") <= DATE_FORMAT(af.scan_date, "%H:%i") AND
                    DATE_FORMAT(
                        af.scan_date,
                        "%Y-%m-%d %H:%i"
                    ) <= DATE_FORMAT(
                        DATE_ADD(
                            STR_TO_DATE(
                                CONCAT(
                                    DATE_FORMAT(af.scan_date, '%Y-%m-%d '),
                                    DATE_FORMAT(wh.start_time, '%H:%i')
                                ),
                                '%Y-%m-%d %H:%i'
                            ),
                            INTERVAL 22 HOUR
                        ),
                        "%Y-%m-%d %H:%i"
                    )
                GROUP BY
                    af.pin,
                    max_hour_end,
                    date
            ) AS af_hour_end ON af.pin = af_hour_end.pin
            AND DATE_FORMAT(
                af_hour_end.datetime_end,
                "%Y-%m-%d %H:%i"
            ) >= '2023-07-20 06:00'
            AND DATE_FORMAT(
                af_hour_end.datetime_end,
                "%Y-%m-%d %H:%i"
            ) <= '2023-07-21 04:00'
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
                        SELECT
                            *
                        FROM
                            working_hours
                        LIMIT
                            1
                    ) as wh ON 1 = 1
                WHERE
                    DATE_FORMAT(wh.overtime_work, "%H:%i") <= DATE_FORMAT(af.scan_date, "%H:%i")
                    AND DATE_FORMAT(af.scan_date, "%H:%i") <= TIME_FORMAT(
                        DATE_ADD(
                            wh.overtime_work,
                            INTERVAL 3 HOUR
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
                    DATE_FORMAT(
                        af.scan_date,
                        "%Y-%m-%d %H:%i"
                    ) AS "datetime", (
                        CASE
                            WHEN afNext.max_date IS NOT NULL THEN afNext.max_date
                            WHEN max(af.scan_date) = min(af.scan_date) THEN NULL
                            ELSE DATE_FORMAT(max(af.scan_date), "%H:%i")
                        END
                    ) AS datetime_overtime_end -- max(af.scan_date) AS datetime_overtime_end
                FROM
                    attendance_fingerspots af
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            working_hours
                        LIMIT
                            1
                    ) as wh ON 1 = 1
                    LEFT JOIN (
                        SELECT
                            DATE_FORMAT(afNext.scan_date, "%Y-%m-%d") date,
                            DATE_FORMAT(
                                DATE_ADD(MAX(scan_date), INTERVAL 1 DAY),
                                '%Y-%m-%d %H:%i'
                            ) AS max_date
                        FROM
                            attendance_fingerspots afNext
                        GROUP BY
                            date
                    ) afNext ON afNext.date = DATE_FORMAT(af.scan_date, "%Y-%m-%d")
                    AND DATE_FORMAT(
                        DATE_ADD(afNext.max_date, INTERVAL 1 DAY),
                        '%H:%i'
                    ) < wh.start_time
                WHERE
                    DATE_FORMAT(af.scan_date, "%H:%i") >= DATE_FORMAT(wh.overtime_work, "%H:%i")
                GROUP BY
                    af.pin,
                    af.cloud_id,
                    afNext.max_date,
                    datetime
            ) AS af_hour_overtime_end ON af.pin = af_hour_overtime_end.pin
            AND af.cloud_id = af_hour_overtime_end.cloud_id
            AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") <= DATE_FORMAT(
                af_hour_overtime_end.datetime,
                "%Y-%m-%d"
            )
            AND af_hour_overtime_end.datetime <= DATE_FORMAT (
                DATE_ADD(af.scan_date, INTERVAL 1 DAY),
                "%Y-%m-%d 06:00"
            )
        WHERE
            -- af.pin = 23
            -- AND
            DATE_FORMAT(af.scan_date, "%H:%i") >= wh.start_time
            AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = @DATE_FILTER
        GROUP BY
            -- af.pin,
            DATE_FORMAT(af.scan_date, "%Y-%m-%d"),
            employee_id,
            af_hour_start.datetime_start,
            af_hour_end.datetime_end,
            af_hour_end.max_hour_end,
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
            duration_overtime_job_order -- af.cloud_id,
            -- em.name
    ) AS ahe
WHERE employee_id = 4;

END / 

DELIMITER ;