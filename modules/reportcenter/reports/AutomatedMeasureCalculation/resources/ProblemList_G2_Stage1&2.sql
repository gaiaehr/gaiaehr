##
## Ambulatory
##

##
## Extract teh Numerator
## Numerator:  Number of patients in the denominator who have at least one entry 
## (or an indication that no problems are known) recorded as structured data in their problem list
##
SELECT count(distinct(patient.pid)) AS NUME
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
INNER JOIN patient_active_problems ON patient.pid = patient_active_problems.pid;
  
##
## Extract the Denominator
## Denominator:  Number of unique patients seen by the EP during the EHR reporting period
##
SELECT count(distinct(patient.pid)) AS DENOM
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3;
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
FROM 
	(SELECT count(distinct(patient.pid)) AS DENOM
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3) AS UNIQUEPATIENTS,
	(SELECT count(distinct(patient.pid)) AS NUME
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3
		INNER JOIN patient_active_problems ON patient.pid = patient_active_problems.pid) AS HAVINGPPROBLEMS;