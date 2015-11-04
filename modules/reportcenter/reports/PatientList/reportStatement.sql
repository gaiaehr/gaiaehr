-- Set all the variables
SET @Provider = :provider_id;
SET @StartDate = :begin_date;
SET @EndDate = :end_date;
SET @ProblemCode = :problem_code;
SET @MedicationCode = :medication_code;
SET @MedicationAllergyCode = :allergy_code;

-- Display all the patient fields
SELECT patient.* FROM patient

--
-- JOIN CLAUSE
--
-- Join the Active Problems
LEFT JOIN (
SELECT distinct(pid) AS pid, code as problem_code
	FROM patient_active_problems
    -- Filter by Patient Active Problems
    WHERE CASE 
		WHEN @ProblemCode IS NOT NULL 
		THEN patient_active_problems.code :problem_code_operator @ProblemCode
		ELSE 1=1 
	END
    LIMIT 1
) patient_active_problems ON patient.pid = patient_active_problems.pid

-- Join the Encounters
LEFT JOIN (
SELECT distinct(pid) as pid, provider_uid, service_date
	FROM encounters
    -- Filter by Encounter Service Date
	WHERE CASE
		WHEN (@StartDate IS NOT NULL AND @EndDate IS NULL)
		THEN (encounters.service_date BETWEEN @StartDate AND NOW())
		ELSE 1=1
	END
	AND CASE
		WHEN (@StartDate IS NOT NULL AND @EndDate IS NOT NULL)
		THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
		ELSE 1=1
	END
	AND CASE
		WHEN (@StartDate IS NULL AND @EndDate IS NOT NULL)
		THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
		ELSE 1=1
	END
    -- Filter by Provider
	AND CASE 
		WHEN @Provider IS NOT NULL 
		THEN encounters.provider_uid :provider_id_operator @Provider
		ELSE 1=1 
	END
    LIMIT 1
) encounters ON patient.pid = encounters.pid

-- Join Medications
LEFT JOIN (
SELECT distinct(pid) AS pid, CODE as medication_code
	FROM patient_medications
    -- Filter by Medication
    WHERE CASE
		WHEN @MedicationCode IS NOT NULL
		THEN patient_medications.code :medication_code_operator @MedicationCode
    ELSE 1=1
	END
    LIMIT 1
) patient_medications ON patient.pid = patient_medications.pid

-- Join Medication Allergies
LEFT JOIN (
SELECT distinct(pid) AS pid, allergy_code
	FROM patient_allergies
    -- Filter by Medication Allergy
    WHERE CASE
		WHEN @MedicationAllergyCode IS NOT NULL
		THEN patient_allergies.allergy_code :allergy_code_operator @MedicationAllergyCode
    ELSE 1=1
	END
    LIMIT 1
) patient_allergies ON patient_allergies.pid = patient.pid


--
-- WHERE CLAUSE
--
-- Filter by Medication
WHERE CASE
	WHEN @MedicationCode IS NOT NULL
	THEN patient_medications.medication_code :medication_code_operator @MedicationCode
	ELSE 1=1
END

-- Filter by Provider
AND CASE 
	WHEN @Provider IS NOT NULL 
	THEN encounters.provider_uid :provider_id_operator @Provider
	ELSE 1=1 
END

-- Filter by Encounter Service Date
AND CASE
	WHEN (@StartDate IS NOT NULL AND @EndDate IS NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND NOW())
	ELSE 1=1
END
AND CASE
	WHEN (@StartDate IS NOT NULL AND @EndDate IS NOT NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
	ELSE 1=1
END
AND CASE
	WHEN (@StartDate IS NULL AND @EndDate IS NOT NULL)
	THEN (encounters.service_date BETWEEN @StartDate AND @EndDate)
	ELSE 1=1
END

-- Filter by Patient Active Problems
AND CASE 
	WHEN @ProblemCode IS NOT NULL 
	THEN patient_active_problems.problem_code :problem_code_operator @ProblemCode
	ELSE 1=1 
END

-- Filter by Medication Allergy
AND CASE
	WHEN @MedicationAllergyCode IS NOT NULL
	THEN patient_allergies.allergy_code :allergy_code_operator @MedicationAllergyCode
	ELSE 1=1
END;