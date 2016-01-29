(SELECT *, "Problem List Measure Stage 1" AS DESCRIPTION, ROUND((NUME/DENOM*100)) AS PERCENT
FROM
(SELECT count(distinct(patient.pid)) AS DENOM
    FROM patient
    INNER JOIN encounters ON patient.pid = encounters.pid
    AND encounters.service_date BETWEEN :begin_date AND :end_date
    AND encounters.provider_uid = :provider_id) AS UNIQUEPATIENTS,
(SELECT count(distinct(patient.pid)) AS NUME
    FROM patient
    INNER JOIN encounters ON patient.pid = encounters.pid
    AND encounters.service_date BETWEEN :begin_date AND :end_date
    AND encounters.provider_uid = :provider_id
    INNER JOIN patient_active_problems ON patient.pid = patient_active_problems.pid) AS HAVINGPPROBLEMS)