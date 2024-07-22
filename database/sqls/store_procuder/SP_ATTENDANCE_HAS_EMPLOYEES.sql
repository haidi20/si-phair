DROP PROCEDURE IF EXISTS `SP_ATTENDANCE_HAS_EMPLOYEES`;

DELIMITER //

CREATE PROCEDURE `SP_ATTENDANCE_HAS_EMPLOYEES`(IN `DATE_FILTER` 
VARCHAR(25)) BEGIN 
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
	        duration_overtime_job_order,
	        created_at
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
	    duration_overtime_job_order,
	    NOW()
	FROM VW_ATTENDANCE
	WHERE
	    DATE_FORMAT(`date`, '%Y-%m-%d') = DATE_FILTER;
	END// 


DELIMITER ;