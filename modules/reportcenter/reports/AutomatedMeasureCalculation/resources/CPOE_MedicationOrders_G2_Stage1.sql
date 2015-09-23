## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
## Numerator:  Number of patients in the denominator that have at least one medication order entered using CPOE
##
SELECT count(distinct(patient.pid)) as NUME
FROM 
    patient
INNER JOIN patient_medications ON patient.pid = patient_medications.pid 
AND patient_medications.date_ordered IS NOT NULL;
  
##
## Extract the Denominator :: Denominator
## Denominator:  Number of unique patients with at least one medication in their medication list seen by the EP during the EHR reporting period
##
SELECT count(distinct(patient.pid)) AS DENOM
FROM 
    patient
INNER JOIN  encounters ON patient.pid = encounters.pid
INNER JOIN patient_medications ON patient.pid = patient_medications.pid
WHERE 
	encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30';
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
FROM (SELECT count(distinct(patient.pid)) AS DENOM
		FROM 
			patient
		INNER JOIN  encounters ON patient.pid = encounters.pid
		INNER JOIN patient_medications ON patient.pid = patient_medications.pid
		WHERE 
			encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS UNIQUEPATIENTS,
	(SELECT count(distinct(patient.pid)) as NUME
		FROM 
			patient
		INNER JOIN patient_medications ON patient.pid = patient_medications.pid 
		AND patient_medications.date_ordered IS NOT NULL) AS HAVINGMEDORDERS;