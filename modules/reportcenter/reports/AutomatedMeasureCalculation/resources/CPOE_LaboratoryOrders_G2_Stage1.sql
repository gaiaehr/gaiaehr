## Ambulatory 	  	  	  	  	  	  	  	  	  	  	 

##
## Extract the Numerator :: Numerator
## Numerator:  Number of laboratory orders in the denominator recorded using CPOE
##
SELECT count(patient_orders.pid) as NUME
FROM 
    patient_orders
WHERE 
	patient_orders.order_type = 'lab';
  
##
## Extract the Denominator :: Denominator
## Denominator: Number of laboratory orders created by an EP during the EHR reporting period
##
SELECT count(patient_orders.pid) AS DENOM
FROM 
    patient_orders
WHERE 
	patient_orders.date_ordered BETWEEN '2010-01-01' AND '2015-12-30'
    AND patient_orders.uid = 1;
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
FROM (SELECT count(patient_orders.pid) AS DENOM
		FROM 
			patient_orders
		WHERE patient_orders.date_ordered BETWEEN '2010-01-01' AND '2015-12-30'
		AND patient_orders.uid = 1) AS UNIQUEMEDICATIONS,
	(SELECT count(patient_orders.pid) as NUME
		FROM 
			patient_orders
		WHERE 
			patient_orders.order_type = 'lab') AS HAVINGLABORDERS;