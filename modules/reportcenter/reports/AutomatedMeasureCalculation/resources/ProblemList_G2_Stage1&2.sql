##
## Extract the Numerator
##
select *
from 
    patient
inner join encounters ON patient.pid = encounters.pid
inner join patient_active_problems ON patient.pid = patient_active_problems.pid
where 
	encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30';
  
##
## Extract the Denominator
##
select count(distinct(patient.pid)) AS DENOM
from 
    patient
inner join encounters ON patient.pid = encounters.pid
where 
	encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30';
    

##
## Compiled Numerator and Denominator
##
SELECT *, ROUND(DENOM/NUME,2) AS PERCENT
FROM (SELECT count(distinct(patient.pid)) AS DENOM
		FROM 
			patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		WHERE encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS UNIQUEPATIENTS,
	(SELECT count(distinct(patient.pid)) As NUME
		FROM 
			patient
		INNER JOIN encounters ON patient.pid = encounters.pid
		INNER JOIN patient_active_problems ON patient.pid = patient_active_problems.pid
		WHERE encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS HAVINGPPROBLEMS;