-- Set all the variables
-- SET @Provider = 6;
SET @Provider = null;
-- SET @Provider = 3;
-- SET @StartDate = '2015-01-01';
-- SET @EndDate = '2015-12-31';
-- SET @ProblemCode = '195967001';
-- SET @ProblemCode = null;

-- Display all the patient fields
SELECT patient.* 
FROM patient

LEFT JOIN (
SELECT distinct(pid) AS pid, provider_uid
	FROM encounters
) encounters ON patient.pid = encounters.pid

WHERE CASE 
	WHEN @Provider IS NOT NULL 
	THEN encounters.provider_uid = @Provider 
	ELSE 1=1 
END