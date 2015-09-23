## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
## Numerator:  Number of medication orders in the denominator recorded using CPOE
##
SELECT count(distinct(patient_medications.pid)) as NUME
FROM 
    patient_medications
WHERE 
	patient_medications.date_ordered IS NOT NULL;
  
##
## Extract the Denominator :: Denominator
## Denominator:  Number of medication orders created by an EP during the EHR reporting period
##
SELECT count(distinct(patient_medications.pid)) AS DENOM
FROM 
    patient_medications
WHERE 
	patient_medications.date_ordered BETWEEN '2010-01-01' AND '2015-12-30';
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
FROM (SELECT count(distinct(patient_medications.pid)) AS DENOM
		FROM 
			patient_medications
		WHERE 
			(patient_medications.date_ordered BETWEEN '2010-01-01' AND '2015-12-30')
            AND patient_medications.uid = 1) AS UNIQUEMEDICATIONS,
	(SELECT count(distinct(patient_medications.pid)) as NUME
		FROM 
			patient_medications
		WHERE patient_medications.date_ordered IS NOT NULL) AS HAVINGMEDORDERS;