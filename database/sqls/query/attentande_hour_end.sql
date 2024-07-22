select *
from (
        select
            af.pin,
            DATE_FORMAT(scan_date, "%Y-%m-%d") as date,
            af_tomorrow.min_scan_date as date_tomorrow
        from
            attendance_fingerspots af
            LEFT JOIN (
                SELECT
                    af.pin,
                    DATE_FORMAT(af.scan_date, "%Y-%m-%d") as date,
                    min(scan_date) as min_scan_date
                from
                    attendance_fingerspots af
                GROUP BY
                    af.pin,
                    date
            ) af_tomorrow ON af.pin = af_tomorrow.pin
            and DATE_FORMAT(
                DATE_ADD(af.scan_date, INTERVAL 1 DAY),
                "%Y-%m-%d"
            ) = af_tomorrow.date
        where
            af.pin = 14 -- AND DATE_FORMAT(scan_date, "%Y-%m-%d") = '2023-07-20'
            -- AND scan_date <= DATE_FORMAT(DATE_ADD( scan_date, INTERVAL 1 DAY ) , "%Y-%m-%d 06:00")
        GROUP BY
            af.pin,
            DATE_FORMAT(af.scan_date, "%Y-%m-%d"),
            af_tomorrow.min_scan_date
        order by
            DATE_FORMAT(af.scan_date, "%Y-%m-%d") desc
    ) at;

SELECT DATE_ADD('2023-07-25 12:00:00', INTERVAL 22 HOUR);