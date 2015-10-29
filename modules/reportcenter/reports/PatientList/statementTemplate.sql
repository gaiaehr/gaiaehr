-- Set all the variables
-- SET @Provider = '6';
-- SET @StartDate = '2015-01-01';
-- SET @EndDate = '2015-12-31';
SET @ProblemCode = '195967001';

-- Display all the patient fields
SELECT patient.* 
FROM patient

-- Filter by Patient Active Problems
LEFT JOIN (
SELECT distinct(pid) AS pid, code
	FROM patient_active_problems
	LIMIT 1
) patient_active_problems ON patient.pid = patient_active_problems.pid
WHERE CASE 
	WHEN @ProblemCode IS NOT NULL 
	THEN patient_active_problems.code = @ProblemCode 
	ELSE 1=1 
END