-- Set all the variables
SET @StartDate = '2015-10-01';
-- SET @StartDate = null;
-- SET @EndDate = '2010-12-31';
SET @EndDate = null;

-- Display all the patient fields
SELECT service_date, patient.* 
FROM patient

-- Join the Encounters
INNER JOIN (
SELECT distinct(pid) as pid, provider_uid, service_date
	FROM encounters
) encounters ON patient.pid = encounters.pid
-- Filter by Encounter Service Date @StartDate Only
AND CASE
	WHEN (@StartDate IS NOT NULL AND @EndDate IS NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND NOW())
    ELSE 1=1
END
AND CASE
	WHEN (@StartDate IS NOT NULL AND @EndDate IS NOT NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
    ELSE 1=1
END
AND CASE
	WHEN (@StartDate IS NULL AND @EndDate IS NOT NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
    ELSE 1=1
END