-- Active: 1685931372338@@127.0.0.1@3306@hris_kpt
select *
from (
        SELECT employee_id,
            DATE_FORMAT(datetime_start, '%Y-%m-%d') AS DATE,
            min(datetime_start) as date_start,
            max(datetime_end) as datetime_end,
            CONCAT(
                LPAD(
                    HOUR(TIMEDIFF(MAX(datetime_end), MIN(datetime_start))),
                    2,
                    '0'
                ),
                ':',
                LPAD(
                    MINUTE(TIMEDIFF(MAX(datetime_end), MIN(datetime_start))),
                    2,
                    '0'
                )
            ) AS duration,
            status
        FROM job_status_has_parents
        WHERE STATUS = 'overtime'
            AND employee_id is NOT null
            AND deleted_at IS null
        GROUP BY employee_id,
            DATE_FORMAT(datetime_start, '%Y-%m-%d'),
            STATUS
        HAVING COUNT(DATE) > 1
        ORDER BY employee_id asc,
            DATE desc
    ) as tbl_overtime
order by duration desc;
SELECT employee_id,
    DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS DATE,
    count(DATE_FORMAT(datetime_start, '%Y-%m-%d'))
FROM job_status_has_parents
WHERE STATUS = 'overtime'
    AND employee_id is NOT null
    AND deleted_at IS null
GROUP BY employee_id,
    DATE_FORMAT(datetime_start, '%Y-%m-%d %H'),
    STATUS
HAVING COUNT(DATE) > 1;
select employee_id,
    datetime_start,
    deleted_at,
    created_at
from job_status_has_parents
where status = 'overtime'
    and employee_id = 4
order by datetime_start desc;
--
--
--
--
SELECT employee_id,
    em.name,
    DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS DATE,
    jshp.deleted_at,
    jshp.created_by
FROM job_status_has_parents jshp
    LEFT JOIN employees em ON jshp.employee_id = em.id
WHERE STATUS = 'overtime'
    AND employee_id IS NOT NULL
    AND jshp.deleted_at IS NULL
GROUP BY DATE_FORMAT(datetime_start, '%Y-%m-%d %H'),
    employee_id,
    jshp.created_by,
    deleted_at
HAVING COUNT(DATE) > 1
ORDER BY employee_id desc,
    date desc;
select employee_id,
    datetime_start,
    datetime_end,
    deleted_at,
    created_at
from job_status_has_parents
where status = 'overtime'
    and employee_id = 4
order by datetime_start desc;
--
--
--
-- UPDATE job_status_has_parents AS target
-- JOIN (
SELECT a1.id,
    a1.employee_id,
    a1.datetime_start
FROM job_status_has_parents AS a1
    JOIN (
        SELECT DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS date_hour,
            employee_id,
            deleted_at,
            status,
            MIN(id) AS min_id
        FROM job_status_has_parents
        where deleted_at is null
            and status = 'overtime'
        GROUP BY date_hour,
            employee_id,
            status,
            deleted_at
        HAVING COUNT(date_hour) > 1 -- and COUNT(employee_id) > 1
    ) AS a2 ON DATE_FORMAT(a1.datetime_start, '%Y-%m-%d %H') = DATE_FORMAT(a2.date_hour, '%Y-%m-%d %H')
WHERE a1.id > a2.min_id
    AND a1.employee_id = a2.employee_id -- and a1.employee_id = 73
    AND a1.employee_id IS NOT NULL
order by datetime_start desc,
    employee_id asc;
-- ) AS subquery ON target.id = subquery.id
-- SET target.deleted_at = NOW();
--
--
-- CREATE TEMPORARY TABLE temp_updates AS
-- SELECT employee_id,
--     DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS DATE
-- FROM job_status_has_parents
-- WHERE STATUS = 'overtime'
--     AND employee_id IS NOT NULL
--     AND deleted_at IS NULL
-- GROUP BY employee_id,
--     DATE_FORMAT(datetime_start, '%Y-%m-%d %H')
-- HAVING COUNT(DATE) >= 2;
-- -- Update the main table based on the temporary table
-- UPDATE job_status_has_parents
-- SET deleted_at = NOW()
-- WHERE (
--         employee_id,
--         DATE_FORMAT(datetime_start, '%Y-%m-%d %H')
--     ) IN (
--         SELECT employee_id,
--             DATE
--         FROM temp_updates
--     );
-- -- Drop the temporary table
-- DROP TEMPORARY TABLE IF EXISTS temp_updates;
SELECT employee_id,
    DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS datetime,
    min(deleted_at),
    -- deleted_at,
    -- deleted_by,
    COUNT(DATE_FORMAT(datetime_start, '%Y-%m-%d %H')) as count,
    status
FROM job_status_has_parents
where employee_id IS NOT NULL
    and status = 'overtime'
    and employee_id = 1
    and deleted_at IS NULL -- and deleted_at IS NOT NULL
    -- and DATE_FORMAT(deleted_at, '%Y-%m-%d') = '2023-08-29' -- and deleted_by IS NULL
GROUP BY datetime,
    employee_id,
    status -- deleted_at,
    -- deleted_by
HAVING COUNT(DATETIME) = 1
ORDER BY employee_id asc,
    -- COUNT(datetime) asc,
    datetime desc;
SELECT *
FROM (
        SELECT id,
            employee_id,
            DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS datetime,
            DATE_FORMAT(@prev_datetime_start, '%Y-%m-%d %H') AS prev_datetime,
            @row_number := CASE
                WHEN @prev_employee_id = employee_id THEN CASE
                    WHEN @prev_datetime_start = DATE_FORMAT(datetime_start, '%Y-%m-%d %H') THEN @row_number + 1
                    ELSE 1
                END
                ELSE 1
            END AS number_row,
            @prev_employee_id := employee_id,
            @prev_datetime_start := DATE_FORMAT(datetime_start, '%Y-%m-%d %H')
        FROM (
                SELECT @row_number := 0,
                    @prev_employee_id := NULL,
                    @prev_datetime_start := NULL
            ) AS vars,
            job_status_has_parents
        WHERE employee_id IS NOT NULL
            AND status = 'overtime' -- AND employee_id = 1
            AND deleted_at IS NULL -- HAVING number_row = 2
        ORDER BY employee_id ASC,
            datetime DESC
    ) AS tbl_overtime
where number_row > 1;
--
--
--
SELECT employee_id,
datetime_start,
deleted_at
from job_status_has_parents
where status = 'overtime'
    AND employee_id IS NOT null
    and employee_id = 73
order BY employee_id ASC,
    datetime_start desc;
--
--
--
-- UPDATE job_status_has_parents AS target
--     JOIN (
-- SELECT DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS datetime,
--     employee_id,
--     -- min(deleted_at),
--     -- deleted_by,
--     status,
--     COUNT(DATE_FORMAT(datetime_start, '%Y-%m-%d %H')) as count
-- FROM job_status_has_parents
-- where employee_id IS NOT NULL
--     and status = 'overtime' -- and employee_id = 4
--     and deleted_at IS NULL -- and DATE_FORMAT(deleted_at, '%Y-%m-%d') = '2023-08-29' -- and deleted_by IS NULL
-- GROUP BY datetime,
--     employee_id,
--     status -- deleted_at,
--     -- deleted_by
-- HAVING COUNT(datetime) > 1
-- ORDER BY employee_id asc,
--     datetime desc;
--     ) AS subquery ON DATE_FORMAT(target.datetime_start, '%Y-%m-%d %H') = subquery.datetime
--     AND target.employee_id = subquery.employee_id
-- -- SET target.deleted_at = NOW();
--
--
--
--
--
UPDATE job_status_has_parents AS target
JOIN (
    SELECT id
    FROM (
            SELECT id,
                employee_id,
                DATE_FORMAT(datetime_start, '%Y-%m-%d %H') AS datetime,
                DATE_FORMAT(@prev_datetime_start, '%Y-%m-%d %H') AS prev_datetime,
                @row_number := CASE
                    WHEN @prev_employee_id = employee_id THEN CASE
                        WHEN @prev_datetime_start = DATE_FORMAT(datetime_start, '%Y-%m-%d %H') THEN @row_number + 1
                        ELSE 1
                    END
                    ELSE 1
                END AS number_row,
                @prev_employee_id := employee_id,
                @prev_datetime_start := DATE_FORMAT(datetime_start, '%Y-%m-%d %H')
            FROM (
                    SELECT @row_number := 0,
                        @prev_employee_id := NULL,
                        @prev_datetime_start := NULL
                ) AS vars,
                job_status_has_parents
            WHERE employee_id IS NOT NULL
                AND status = 'overtime' -- AND employee_id = 1
                AND deleted_at IS NULL
            ORDER BY employee_id ASC,
                datetime DESC
        ) AS subquery
    WHERE number_row > 1 -- AND employee_id = 1
) AS filtered_records ON target.id = filtered_records.id
SET target.deleted_at = NOW();