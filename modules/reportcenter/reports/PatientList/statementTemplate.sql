-- Display all the patient fields
SELECT patient.* 
FROM patient

-- Look for encounters
INNER JOIN encounters 
ON encounters.pid = patient.pid

-- Look for providers
INNER JOIN users 
ON users.id = encounters.provider_uid
AND encounters.provider_uid = 6;
