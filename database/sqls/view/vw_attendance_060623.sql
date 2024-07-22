-- Active: 1685931372338@@127.0.0.1@3306@hris_kpt

DROP VIEW
    IF EXISTS `VW_ATTENDANCE`;

CREATE VIEW VW_ATTENDANCE AS
SELECT
    DATE_FORMAT(af.scan_date, "%Y-%m-%d") AS "date",
    af.pin,
    af_hour_start.hour_start,
    af_hour_end.hour_end,
    TIMESTAMPDIFF(
        MINUTE,
        af_hour_start.datetime_start,
        af_hour_end.datetime_end
    ) as duration_work,
    af_hour_rest.hour_rest_start,
    af_hour_rest.hour_rest_end,
    -- af_hour_rest.date as date_rest,
    TIMESTAMPDIFF(
        MINUTE,
        af_hour_rest.datetime_start,
        af_hour_rest.datetime_end
    ) as duration_rest,
    af_hour_overtime_start.hour_overtime_start as hour_overtime_start,
    af_hour_overtime_end.hour_overtime_end as hour_overtime_end,
    TIMESTAMPDIFF(
        MINUTE,
        af_hour_overtime_start.datetime_overtime_start,
        af_hour_overtime_end.datetime_overtime_end
    ) as duration_overtime,
    DATE_FORMAT(jt.datetime_start, "%H:%i") AS hour_overtime_job_order_start,
    DATE_FORMAT(jt.datetime_end, "%H:%i") AS hour_overtime_job_order_end,
    DATE_FORMAT(jt.datetime_start, "%Y-%m-%d") AS date_overtime_job_order_start,
    DATE_FORMAT(jt.datetime_end, "%Y-%m-%d") AS date_overtime_job_order_end,
    TIMESTAMPDIFF(
        MINUTE,
        jt.datetime_start,
        jt.datetime_end
    ) as duration_overtime_job_order,
    af.cloud_id,
    em.id as employee_id,
    em.name
FROM attendance_fingerspots af
    LEFT JOIN fingers fi ON af.pin = fi.id_finger
    LEFT JOIN employees em ON fi.employee_id = em.id
    LEFT JOIN job_status_has_parents jt ON fi.employee_id = jt.employee_id AND DATE_FORMAT(jt.datetime_start, "%Y-%m-%d") = DATE_FORMAT(af.scan_date, "%Y-%m-%d") AND jt.status = "overtime"
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
            date
    ) AS af_hour_start ON af.pin = af_hour_start.pin
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_start.date
    LEFT JOIN (
        SELECT
            af.pin,
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
            date
    ) AS af_hour_end ON af.pin = af_hour_end.pin
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_end.date
    LEFT JOIN (
        SELECT
            af.pin,
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
            date
    ) AS af_hour_overtime_start ON af.pin = af_hour_overtime_start.pin
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = af_hour_overtime_start.date
    LEFT JOIN (
        SELECT
            af.pin,
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
            date
    ) AS af_hour_overtime_end ON af.pin = af_hour_overtime_end.pin
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") <= af_hour_overtime_end.date
    AND af_hour_overtime_end.date <= DATE_FORMAT(
        DATE_ADD(af.scan_date, INTERVAL 1 DAY),
        "%Y-%m-%d"
    )
GROUP BY
    DATE_FORMAT(af.scan_date, "%Y-%m-%d"),
    af.pin,
    af_hour_start.hour_start,
    af_hour_end.hour_end,
    duration_work,
    af_hour_rest.hour_rest_start,
    af_hour_rest.hour_rest_end,
    duration_rest,
    af_hour_overtime_start.hour_overtime_start,
    af_hour_overtime_end.hour_overtime_end,
    duration_overtime,
    DATE_FORMAT(jt.datetime_start, "%H:%i"),
    DATE_FORMAT(jt.datetime_end, "%H:%i"),
    DATE_FORMAT(jt.datetime_start, "%Y-%m-%d"),
    DATE_FORMAT(jt.datetime_end, "%Y-%m-%d"),
    duration_overtime_job_order,
    af.cloud_id,
    employee_id,
    em.name