<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace modules\reportcenter\dataProvider;

class AutomatedMeasureCalculation extends Reports{

    /**
     * getProblemListMeasure
     * Method to generate the data for Problem List
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1) : Default is 1
     * No Stage 2 Measure - Same as 1
     */
    function getProblemListMeasure($Parameters = null, $Stage = '1'){
        try{
            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for Problem List Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for Problem List Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for Problem List Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for Problem List Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for  Problem List Measure');
            // Consume Parameters
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];
            // Stage selector
            switch($Stage){
                default:
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
                        FROM
                        (SELECT count(distinct(patient.pid)) AS DENOM
                            FROM patient
                            INNER JOIN encounters ON patient.pid = encounters.pid
                            AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
                            AND encounters.provider_uid = $provider_id) AS UNIQUEPATIENTS,
                        (SELECT count(distinct(patient.pid)) AS NUME
                            FROM patient
                            INNER JOIN encounters ON patient.pid = encounters.pid
                            AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
                            AND encounters.provider_uid = $provider_id
                            INNER JOIN patient_active_problems ON patient.pid = patient_active_problems.pid) AS HAVINGPPROBLEMS";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getMedicationListMeasure
     * Method to generate the data for Medication List
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1) : Default is 1
     * No Stage 2 Measure - Same as 1
     */
    function getMedicationListMeasure($Parameters = null, $Stage = '1'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for Medication List Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for Medication List Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for Medication List Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for Medication List Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for  Medication List Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                default:
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
                        FROM
	                    (SELECT count(distinct(patient.pid)) AS DENOM
		                    FROM patient
		                    INNER JOIN encounters ON patient.pid = encounters.pid AND encounters.provider_uid = 3
		                    WHERE encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS UNIQUEPATIENTS,
	                    (SELECT count(distinct(patient.pid)) as NUME
		                    FROM patient
		                    INNER JOIN patient_medications ON patient.pid = patient_medications.pid
		                    INNER JOIN encounters ON patient.pid = encounters.pid AND encounters.provider_uid = 3
		                    AND encounters.service_date BETWEEN '2010-01-01' AND '2015-12-30') AS HAVINGMEDICATIONS;";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getMedicationAllergyListMeasure
     * Method to generate the data for Medication Allergy List
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1) : Default is 1
     * No Stage 2 Measure - Same as 1
     */
    function getMedicationAllergyListMeasure($Parameters = null, $Stage = '1'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for Medication Allergies Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for Medication Allergies Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for Medication Allergies Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for Medication Allergies Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for  Medication Allergies Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                default:
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
	                    FROM
                        (SELECT count(distinct(patient.pid)) AS DENOM
		                    FROM patient
		                    INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.provider_uid = $provider_id
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date') AS UNIQUEPATIENTS,
	                    (SELECT count(distinct(patient.pid)) as NUME
		                    FROM patient
                            INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id
		                    INNER JOIN patient_allergies ON patient.pid = patient_allergies.pid) AS HAVINGMEDALLERGIES";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getCPOEMeasure_Medications
     * Method to generate the data for CPOE Medication Orders
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    // TODO: Revise the SQL Statement, is wrong.
    function getCPOEMeasure_Medications($Parameters = null, $Stage = '2'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for CPOE Medications Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for CPOE Medications Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for CPOE Medications Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for CPOE Medications Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for CPOE Medications Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                case '1':
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
	                    FROM
                        (SELECT count(distinct(patient.pid)) AS DENOM
		                    FROM patient
		                    INNER JOIN patient_medications ON patient.pid = patient_medications.pid
		                    INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id) AS UNIQUEPATIENTS,
	                    (SELECT count(distinct(patient.pid)) as NUME
		                    FROM patient
		                    INNER JOIN  encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id
		                    INNER JOIN patient_medications ON patient.pid = patient_medications.pid
		                    AND patient_medications.date_ordered IS NOT NULL) AS HAVINGMEDORDERS;";
                    break;
                case '2':
                case '2A':
                case '1A':
                    $SQL ="SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
                        FROM
                        (SELECT count(distinct(patient_medications.pid)) AS DENOM
		                    FROM patient_medications
		                    WHERE (patient_medications.date_ordered BETWEEN '$begin_date' AND '$end_date')
		                    AND patient_medications.uid = $provider_id) AS UNIQUEMEDICATIONS,
	                    (SELECT count(distinct(patient_medications.pid)) as NUME
		                    FROM patient_medications
		                    WHERE patient_medications.date_ordered IS NOT NULL) AS HAVINGMEDORDERS;";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getCPOEMeasure_Laboratory
     * Method to generate the data for CPOE Laboratory Orders
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getCPOEMeasure_Laboratory($Parameters = null, $Stage = '2'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for CPOE Laboratory Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for CPOE Laboratory Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for CPOE Laboratory Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for CPOE Laboratory Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for CPOE Laboratory Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                default:
                    $SQL ="SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
                        FROM
                            (SELECT count(patient_orders.pid) AS DENOM
		                        FROM patient_orders
		                        WHERE patient_orders.date_ordered BETWEEN '$begin_date' AND '$end_date'
		                        AND patient_orders.uid = $provider_id) AS UNIQUEMEDICATIONS,
	                        (SELECT count(patient_orders.pid) as NUME
		                        FROM patient_orders
		                        WHERE patient_orders.order_type = 'lab') AS HAVINGLABORDERS";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getCPOEMeasure_Radiology
     * Method to generate the data for CPOE Radiology Orders
     * @param null $Parameters
     * @return \Exception
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getCPOEMeasure_Radiology($Parameters = null, $Stage = '2'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for CPOE Laboratory Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for CPOE Laboratory Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for CPOE Laboratory Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for CPOE Laboratory Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for CPOE Laboratory Measure');

            // Consume Parameters
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                default:
                    $SQL ="SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
                        FROM
                            (SELECT count(patient_orders.pid) AS DENOM
		                        FROM patient_orders
		                        WHERE patient_orders.date_ordered BETWEEN '$begin_date' AND '$end_date'
		                        AND patient_orders.uid = $provider_id) AS UNIQUEMEDICATIONS,
	                        (SELECT count(patient_orders.pid) as NUME
		                        FROM patient_orders
		                        WHERE patient_orders.order_type = 'rad') AS HAVINGRADORDERS";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * geteRXMeasure
     * Method to generate the data for eRX
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function geteRXMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getDemographicsMeasure
     * Method to generate the data for Demographics
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     * @return \Exception
     */
    function getDemographicsMeasure($Parameters = null, $Stage = '2'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for Demographics Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for Demographics Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for Demographics Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for Demographics Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for Demographics Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                case '1':
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
	                    FROM
                        (SELECT count(distinct(patient.pid)) AS DENOM
		                    FROM patient
		                    INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id) AS UNIQUEMEDICATIONS,
	                    (SELECT count(distinct(patient.pid)) as NUME
		                    FROM patient
                            INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id
		                    WHERE patient.race IS NOT NULL
		                    AND patient.ethnicity IS NOT NULL
		                    AND patient.language IS NOT NULL
		                    AND (patient.DOB IS NOT NULL AND patient.DOB != '0000-00-00')
		                    AND patient.sex IS NOT NULL) AS HAVINGMEDORDERS";
                    break;
                case '2':
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
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
		                    AND (patient.DOB IS NOT NULL AND patient.DOB != '0000-00-00')) AS HAVINGMEDORDERS";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getVitalSignsMeasure
     * Method to generate the data for Vital Signs
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     * @return \Exception
     */
    function getVitalSignsMeasure($Parameters = null, $Stage = '2'){
        try{

            // Validation
            if(!isset($Parameters))
                throw new \Exception('No parameters provided for Demographics Measure');
            if(!isset($Stage))
                throw new \Exception('No Stage provided for Demographics Measure');
            if(!isset($Parameters['begin_date']))
                throw new \Exception('No [begin_date] parameter provided for Demographics Measure');
            if(!isset($Parameters['end_date']))
                throw new \Exception('No [end_date] parameter provided for Demographics Measure');
            if(!isset($Parameters['provider_id']))
                throw new \Exception('No [provider_id] parameter provided for Demographics Measure');

            // Consume Parameters
            $SQL = '';
            $begin_date = $Parameters['begin_date'];
            $end_date = $Parameters['end_date'];
            $provider_id = $Parameters['provider_id'];

            // Stage selector
            switch($Stage){
                case '1':
                    $SQL = "SELECT *, ROUND((NUME/DENOM*100)) AS PERCENT
	                    FROM
                        (SELECT count(distinct(patient.pid)) AS DENOM
		                    FROM patient
		                    INNER JOIN encounters ON patient.pid = encounters.pid
		                    AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id
		                    WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2) AS UNIQUEPATIENT,
	                    (SELECT count(distinct(patient.pid)) as NUME
		                    FROM patient
		                    INNER JOIN encounters ON patient.pid = encounters.pid
                            AND encounters.service_date BETWEEN '$begin_date' AND '$end_date'
		                    AND encounters.provider_uid = $provider_id
		                    INNER JOIN encounters_vitals ON patient.pid = encounters_vitals.pid
                            AND encounters_vitals.height_in IS NOT NULL
		                    AND encounters_vitals.height_cm IS NOT NULL
		                    AND encounters_vitals.weight_kg IS NOT NULL
		                    AND encounters_vitals.weight_lbs IS NOT NULL
		                    AND encounters_vitals.bp_systolic IS NOT NULL
		                    AND encounters_vitals.bp_diastolic IS NOT NULL
		                    WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(patient.DOB)), '%Y') + 0) > 2) AS HAVINGRESULTS";
                    break;
                case '2':
                    $SQL = "";
                    break;
            }
        } catch(\Exception $Error) {
            return $Error;
        }
    }

    /**
     * getSmokingStatusMeasure
     * Method to generate the data for Smoking Status
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getSmokingStatusMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getLabResultsMeasure
     * Method to generate the data for Lab Results
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getLabResultsMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getProblemList
     * Method to generate the data for Problem List
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getPatientRemindersMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getViewDownloadTransmitMeasure
     * Method to generate the data for View Download & Transmit
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getViewDownloadTransmitMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getClinicalSummaryMeasure
     * Method to generate the data for Clinical Summary
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getClinicalSummaryMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getPatientEducationMeasure
     * Method to generate the data for Patient Education
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getPatientEducationMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getMedicationReconciliationMeasure
     * Method to generate the data for Medication Reconciliation
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getMedicationReconciliationMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getSummaryOfCareMeasure
     * Method to generate the data for Summary Of Care
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getSummaryOfCareMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getSecureMessagingMeasure
     * Method to generate the data for Secure Messaging
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getSecureMessagingMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getImagingMeasure
     * Method to generate the data for Imaging
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getImagingMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getFamilyHistoryMeasure
     * Method to generate the data for Family History
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getFamilyHistoryMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * geteNotesMeasure
     * Method to generate the data for eNotes
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function geteNotesMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getAdvanceDirectivesMeasure
     * Method to generate the data for Advance Directives
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getAdvanceDirectivesMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * getLabEHtoEPMeasure
     * Method to generate the data for Lab EH to EP
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function getLabEHtoEPMeasure($Parameters = null, $Stage = '2'){

    }

    /**
     * eMarMeasure
     * Method to generate the data for eMar
     * @param null $Parameters
     * @param string $Stage : Selection of the stage data to generate (1 or 2) : Default is 2
     */
    function eMarMeasure($Parameters = null, $Stage = '2'){

    }

}