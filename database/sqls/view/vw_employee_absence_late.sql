-- Active: 1685931372338@@127.0.0.1@3306@hris_kpt
DROP VIEW IF EXISTS `vw_employee_absence_late`;
CREATE VIEW vw_employee_absence_late AS
SELECT ahp.employee_id,
    em.name as employee_name,
    p.name as position_name,
    ahp.date,
    ahp.hour_start,
    (
        CASE
            WHEN em.working_hour = "6,1" THEN wh.late_six_one
            ELSE wh.late_five_two
        END
    ) as working_hour_late,
    ahp.hour_end
FROM attendance_has_employees ahp
    LEFT JOIN employees em ON ahp.employee_id = em.id
    AND em.employee_status = 'aktif'
    AND em.deleted_at IS NULL
    LEFT JOIN positions p ON em.position_id = p.id
    AND p.deleted_at IS NULL
    LEFT JOIN (
        SELECT *
        FROM working_hours
        LIMIT 1
    ) as wh ON 1 = 1
WHERE ahp.employee_id IS NOT NULL
    AND (
        CASE
            WHEN em.working_hour = "6,1" THEN (
                DATE_FORMAT(ahp.hour_start, "%H:%i") > wh.late_six_one
            )
            ELSE (
                DATE_FORMAT(ahp.hour_start, "%H:%i") > wh.late_five_two
            )
        END
    )
GROUP BY ahp.employee_id,
    ahp.date,
    working_hour_late,
    ahp.hour_start,
    ahp.hour_end;