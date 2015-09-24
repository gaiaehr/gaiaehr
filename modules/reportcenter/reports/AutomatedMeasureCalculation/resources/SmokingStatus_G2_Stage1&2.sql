## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
## Number of patients in the denominator with smoking status recorded as structured data
##
SELECT count(distinct(patient.pid)) as NUME
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
INNER JOIN patient_smoke_status ON patient.pid = patient_smoke_status.pid
WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) >= 13;
  
##
## Extract the Denominator :: Denominator
## Number of unique patients age 13 or older seen by the EP during the EHR reporting period
##
SELECT count(distinct(patient.pid)) AS DENOM
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) >= 13;

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
	FROM 
    (SELECT count(distinct(patient.pid)) AS DENOM
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3
		WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) >= 13) AS UNIQUEPATIENTS,
	(SELECT count(distinct(patient.pid)) as NUME
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3
		INNER JOIN patient_smoke_status ON patient.pid = patient_smoke_status.pid
		WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) >= 13) AS HAVINGSMOKE;