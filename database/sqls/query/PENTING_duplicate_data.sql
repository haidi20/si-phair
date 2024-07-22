SELECT *
FROM (
        SELECT employee_id,
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
where number_row > 1