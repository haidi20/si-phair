-- Active: 1685931372338@@127.0.0.1@3306@hris_kpt
-- check absen masuk atau tidak
select *
from attendance_fingerspots af
where -- pin = 1
    pin = 1 --
    -- and cloud_id like '%425%' --
    and cloud_id like '%22d%'
order by scan_date desc;
--
select *
from attendance_has_employees ahe -- where ahe.employee_id = 62
where ahe.employee_id = 30
order by date desc;
--
-- hapus data
DELETE a1
FROM attendance_has_employees a1
    JOIN (
        SELECT hour_start,
            MIN(id) AS min_id
        FROM attendance_has_employees
        GROUP BY hour_start
        HAVING COUNT(hour_start) > 1
    ) a2 ON a1.hour_start = a2.hour_start
    AND a1.id > a2.min_id;
-- lihat data
SELECT hour_start,
    COUNT(hour_start)
FROM attendance_has_employees
GROUP BY hour_start
HAVING COUNT(hour_start) > 1;
select -- em.name,
    -- ahe.*
    COUNT(date),
    date,
    employee_id
from attendance_has_employees ahe
    LEFT JOIN employees em ON ahe.employee_id = em.id
where employee_id IS NOT NULL -- and date = '2023-04-01'
    -- and employee_id = 25;
group by date,
    employee_id
having COUNT(date) > 1;
delete from attendance_has_employees
where MONTH(date) = '08'
    AND YEAR(date) = '2023';
select *
from employees -- where name like '%deddy%';
where name like '%sudarto%';
-- where name like '%ahmad%';
--  id = 21;
--
select *
from fingers
where employee_id = 34;