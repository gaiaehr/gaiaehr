## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
## Numerator: Number of patients in the denominator who have all the elements of demographics 
## (or a specific exclusion if the patient declined to provide one or more elements or if 
## recording an element is contrary to state law) recorded as structured data
##
SELECT count(distinct(patient.pid)) as NUME
FROM patient
INNER JOIN encounters ON patient.pid = encounters.pid
AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
AND encounters.provider_uid = 3
WHERE patient.race IS NOT NULL
AND patient.ethnicity IS NOT NULL
AND patient.language IS NOT NULL
AND (patient.DOB IS NOT NULL AND patient.DOB != '0000-00-00')
AND patient.sex IS NOT NULL;
  
##
## Extract the Denominator :: Denominator
## Denominator: Number of unique patients seen by the EP during the EHR reporting period
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
		AND encounters.provider_uid = 3) AS UNIQUEMEDICATIONS,
	(SELECT count(distinct(patient.pid)) as NUME
		FROM patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30'
		AND encounters.provider_uid = 3
		WHERE patient.race IS NOT NULL
		AND patient.ethnicity IS NOT NULL
		AND patient.language IS NOT NULL
		AND (patient.DOB IS NOT NULL AND patient.DOB != '0000-00-00')
		AND patient.sex IS NOT NULL) AS HAVINGMEDORDERS;