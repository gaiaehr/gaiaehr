## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
##
## Ambulatory--Alternate Stage 1 (2013 Only)/Stage 1 & Stage 2 (2014 Onward)--All Within Scope 	
## - Patients 3 years of age or older in the denominator for whom height/length, weight, and blood pressure are recorded; AND 	  	  	  	  	  	  	  	  	  	  	  	 
## - Patients younger than 3 years of age in the denominator for whom height/length and weight are recorded
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
WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 3;
  
##
## Extract the Denominator :: Denominator
##
## Ambulatory--Alternate Stage 1 (2013 Only)/Stage 1 & Stage 2 (2014 Onward)--All Within Scope 	
## Number of unique patients seen by the EP during the EHR reporting period
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