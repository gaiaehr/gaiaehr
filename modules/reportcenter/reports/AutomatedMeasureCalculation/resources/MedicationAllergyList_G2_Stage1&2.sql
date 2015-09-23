##
## Ambulatory
##

##
## Extract the Numerator
## Numerator:  Number of unique patients in the denominator who have at least one entry 
## (or an indication of no known medication allergies) recorded as structured data in their medication allergy list
##
SELECT count(distinct(patient.pid)) as NUME
FROM 
    patient
INNER JOIN encounters ON patient.pid = encounters.pid
INNER JOIN patient_allergies ON patient.pid = patient_allergies.pid
WHERE 
	encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30';
  
##
## Extract the Denominator
## Denominator:  Number of unique patients seen by the EP during the EHR reporting period
##
SELECT count(distinct(patient.pid)) AS DENOM
FROM 
    patient
INEER JOIN encounters ON patient.pid = encounters.pid
WHERE 
	encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30';
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
FROM (SELECT count(distinct(patient.pid)) AS DENOM
		FROM 
			patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		WHERE encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS UNIQUEPATIENTS,
	(SELECT count(distinct(patient.pid)) As NUME
		FROM 
			patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		INNER JOIN patient_allergies ON patient.pid = patient_allergies.pid
		WHERE encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS HAVINGMEDALLERGIES;