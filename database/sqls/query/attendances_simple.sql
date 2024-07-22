SELECT ep.employee_id,
    ep.name,
    ep.cloud_id,
    ep.pin,
    DATE_FORMAT(af.scan_date, "%Y-%m-%d") as date
FROM attendance_fingerspots af
    LEFT JOIN(
        SELECT em.id as employee_id,
            em.name,
            ft.cloud_id,
            fi.id_finger as pin
        FROM employees em
            LEFT JOIN fingers fi on fi.employee_id = em.id
            LEFT JOIN finger_tools ft on fi.finger_tool_id = ft.id
        WHERE fi.id_finger IS NOT NULL
        GROUP BY em.id,
            ft.cloud_id,
            pin
    ) as ep ON ep.cloud_id = af.cloud_id
    AND ep.pin = af.pin
WHERE (
        ep.name LIKE '%s%' -- OR ep.name LIKE '%Ahmad Ardiansyah%'
    )
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = '2023-08-09'
GROUP BY ep.employee_id,
    ep.name,
    ep.cloud_id,
    ep.pin,
    DATE_FORMAT(af.scan_date, "%Y-%m-%d")
ORDER BY date desc;
--
SELECT pin,
fi.finger_tool_id,
ft.cloud_id,
em.name,
DATE_FORMAT(af.scan_date, "%Y-%m-%d")
FROM attendance_fingerspots af
    LEFT JOIN fingers fi ON af.pin = fi.id_finger
    LEFT JOIN finger_tools ft on fi.finger_tool_id = ft.id
    LEFT JOIN employees em ON fi.employee_id = em.id
    AND em.employee_status = 'aktif'
    AND em.deleted_at IS NULL
WHERE af.pin = 11
    AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = '2023-08-09' -- and cloud_id like '%425%'
    -- AND af.cloud_id like '%2dd%'
GROUP BY pin,
    fi.employee_id,
    fi.finger_tool_id,
    ft.id,
    af.cloud_id,
    em.id,
    DATE_FORMAT(af.scan_date, "%Y-%m-%d");
SELECT *
FROM fingers
WHERE id_finger = 11;
SELECT fi.id_finger,
    em.name,
    ft.cloud_id,
    -- af.scan_date,
    DATE_FORMAT(af.scan_date, "%Y-%m-%d") as date
FROM attendance_fingerspots af
    LEFT JOIN finger_tools ft ON af.cloud_id = ft.cloud_id
    LEFT JOIN fingers fi ON ft.id = fi.finger_tool_id
    LEFT JOIN employees em ON fi.employee_id = em.id
WHERE (
        em.name LIKE '%ahmad%'
        OR em.id = 28
    ) -- fi.id_finger = 11
    -- AND DATE_FORMAT(af.scan_date, "%Y-%m-%d") = '2023-08-09'
GROUP BY fi.id_finger,
    ft.cloud_id,
    em.name,
    DATE_FORMAT(af.scan_date, "%Y-%m-%d")
ORDER by date desc;