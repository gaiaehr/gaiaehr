## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
##
## Ambulatory--Stage 1 (2013 Only)
## Number of patients in the denominator who have entries of height/length, weight, and blood pressure recorded as structured data
##
SELECT count(distinct(patient.pid)) as NUME
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
INNER JOIN encounters_vitals ON patient.pid = encounters_vitals.pid
AND encounters_vitals.height_in IS NOT NULL
AND encounters_vitals.height_cm IS NOT NULL
AND encounters_vitals.weight_kg IS NOT NULL
AND encounters_vitals.weight_lbs IS NOT NULL
AND encounters_vitals.bp_systolic IS NOT NULL
AND encounters_vitals.bp_diastolic IS NOT NULL
WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2;
  
##
## Extract the Denominator :: Denominator
##
## Ambulatory--Stage 1 (2013 Only)
## Number of unique patients 2 years of age or older seen by the EP during the EHR reporting period
##
SELECT count(distinct(patient.pid)) AS DENOM
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2;

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
		WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2) AS UNIQUEMEDICATIONS,
	(SELECT count(distinct(patient.pid)) as NUME
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3
		INNER JOIN encounters_vitals ON patient.pid = encounters_vitals.pid
		AND encounters_vitals.height_in IS NOT NULL
		AND encounters_vitals.height_cm IS NOT NULL
		AND encounters_vitals.weight_kg IS NOT NULL
		AND encounters_vitals.weight_lbs IS NOT NULL
		AND encounters_vitals.bp_systolic IS NOT NULL
		AND encounters_vitals.bp_diastolic IS NOT NULL
		WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2) AS HAVINGMEDORDERS;