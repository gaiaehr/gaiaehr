-- Set all the variables
-- SET @Provider = 6;
SET @Provider = null;
SET @StartDate = '2016-01-01';
-- SET @StartDate = null;
-- SET @EndDate = '2010-12-31';
SET @EndDate = null;
-- SET @ProblemCode = '195967001';
-- SET @ProblemCode = null;

-- Display all the patient fields
SELECT service_date, patient.* 
FROM patient

-- Join the Encounters
LEFT JOIN (
SELECT pid, provider_uid, service_date
	FROM encounters
) encounters ON patient.pid = encounters.pid
-- Filter by Encounter Service Date
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
-- Filter by Provider
AND CASE 
	WHEN @Provider IS NOT NULL 
	THEN encounters.provider_uid = @Provider 
	ELSE 1=1 
END

--
-- WHERE CLAUSE
--

WHERE 
-- Filter by Encounter Service Date
CASE
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
-- Filter by Provider
AND CASE 
	WHEN @Provider IS NOT NULL 
	THEN encounters.provider_uid = @Provider 
	ELSE 1=1 
END
