-- Set all the variables
SET @Provider = 6;
SET @StartDate = null;
SET @EndDate = null;
SET @ProblemCode = null;
SET @MedicationCode = null;
SET @MedicationAllergyCode = null;

-- Display all the patient fields
SELECT patient.*, Race.option_name as Race, Ethnicity.option_name as Ethnicity
FROM patient

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
		THEN patient_active_problems.code = @ProblemCode
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
		THEN encounters.provider_uid = @Provider
		ELSE 1=1 
	END
) encounters ON patient.pid = encounters.pid

-- Join Medications
LEFT JOIN (
SELECT distinct(pid) AS pid, CODE as medication_code
	FROM patient_medications
    -- Filter by Medication
    WHERE CASE
		WHEN @MedicationCode IS NOT NULL
		THEN patient_medications.code = @MedicationCode
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
		THEN patient_allergies.allergy_code = @MedicationAllergyCode
    ELSE 1=1
	END
    LIMIT 1
) patient_allergies ON patient_allergies.pid = patient.pid


-- Join Combo List Options for Race
LEFT JOIN combo_lists_options as Race ON Race.option_value = patient.race
AND Race.list_id = 14

-- Join Combo List Options for Ethnicity
LEFT JOIN combo_lists_options as Ethnicity ON Ethnicity.option_value = patient.ethnicity
AND Ethnicity.list_id = 59

--
-- WHERE CLAUSE
--
-- Filter by Medication
WHERE CASE
	WHEN @MedicationCode IS NOT NULL
	THEN patient_medications.medication_code = @MedicationCode
	ELSE 1=1
END

-- Filter by Provider
AND CASE 
	WHEN @Provider IS NOT NULL 
	THEN encounters.provider_uid = @Provider
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
	THEN patient_active_problems.problem_code = @ProblemCode
	ELSE 1=1 
END

-- Filter by Medication Allergy
AND CASE
	WHEN @MedicationAllergyCode IS NOT NULL
	THEN patient_allergies.allergy_code = @MedicationAllergyCode
	ELSE 1=1
END