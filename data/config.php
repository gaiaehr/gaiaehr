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

$API = [

    'CCDDocumentParse' => [
        'methods' => [
            'getTestCCD' => [
                'len' => 1
            ]
        ]
    ],
    /**
     * Accounting Billing Functions
     */
    'AccVoucher' => [
        'methods' => [
            'getVoucher' => [
                'len' => 1
            ],
            'addVoucher' => [
                'len' => 1
            ],
            'updateVoucher' => [
                'len' => 1
            ],
            'destroyVoucher' => [
                'len' => 1
            ],
            'getVoucherLines' => [
                'len' => 1
            ],
            'addVoucherLine' => [
                'len' => 1
            ],
            'updateVoucherLine' => [
                'len' => 1
            ],
            'destroyVoucherLine' => [
                'len' => 1
            ],
	        'getVisitCheckOutCharges' => [
		        'len' => 1
            ]
        ]
    ],
    'TemplatePanels' => [
        'methods' => [
            'getTemplatePanels' => [
                'len' => 1
            ],
            'getTemplatePanel' => [
                'len' => 1
            ],
            'createTemplatePanel' => [
                'len' => 1
            ],
            'updateTemplatePanel' => [
                'len' => 1
            ],
            'deleteTemplatePanel' => [
                'len' => 1
            ],
            'getTemplatePanelTemplates' => [
                'len' => 1
            ],
            'getTemplatePanelTemplate' => [
                'len' => 1
            ],
            'createTemplatePanelTemplate' => [
                'len' => 1
            ],
            'updateTemplatePanelTemplate' => [
                'len' => 1
            ],
            'deleteTemplatePanelTemplate' => [
                'len' => 1
            ]
        ]
    ],
    'AdvanceDirective' => [
        'methods' => [
            'getPatientAdvanceDirectives' => [
                'len' => 1
            ],
            'getPatientAdvanceDirective' => [
                'len' => 1
            ],
            'addPatientAdvanceDirective' => [
                'len' => 1
            ],
            'updatePatientAdvanceDirective' => [
                'len' => 1
            ],
            'destroyPatientAdvanceDirective' => [
                'len' => 1
            ]
        ]
    ],
    'IpAccessRules' => [
        'methods' => [
            'getIpAccessRules' => [
                'len' => 1
            ],
            'getIpAccessRule' => [
                'len' => 1
            ],
            'createIpAccessRule' => [
                'len' => 1
            ],
            'updateIpAccessRule' => [
                'len' => 1
            ],
            'deleteIpAccessRule' => [
                'len' => 1
            ]
        ]
    ],
    'AppointmentRequest' => [
        'methods' => [
            'getAppointmentRequests' => [
                'len' => 1
            ],
            'getAppointmentRequest' => [
                'len' => 1
            ],
            'addAppointmentRequest' => [
                'len' => 1
            ],
            'updateAppointmentRequest' => [
                'len' => 1
            ],
            'deleteAppointmentRequest' => [
                'len' => 1
            ]
        ]
    ],
    'Amendments' => [
        'methods' => [
            'getAmendment' => [
                'len' => 1
            ],
            'getAmendments' => [
                'len' => 1
            ],
            'addAmendment' => [
                'len' => 1
            ],
            'updateAmendment' => [
                'len' => 1
            ],
            'destroyAmendment' => [
                'len' => 1
            ],
            'getUnreadAmendments' => [
                'len' => 1
            ],
            'getUnViewedAmendments' => [
                'len' => 1
            ]
        ]
    ],
    'Procedures' => [
        'methods' => [
            'loadProcedures' => [
                'len' => 1
            ],
            'saveProcedure' => [
                'len' => 1
            ],
            'destroyProcedure' => [
                'len' => 1
            ]
        ]
    ],
    'Providers' => [
        'methods' => [
            'getProviderCredentializations' => [
                'len' => 1
            ],
            'getProviderCredentialization' => [
                'len' => 1
            ],
            'addProviderCredentialization' => [
                'len' => 1
            ],
            'updateProviderCredentialization' => [
                'len' => 1
            ],
            'deleteProviderCredentialization' => [
                'len' => 1
            ],
            'getProviderCredentializationForDate' => [
                'len' => 3
            ]
        ]
    ],
    'DataPortability' => [
        'methods' => [
            'export' => [
                'len' => 1
            ]
        ]
    ],
    'CPT' => [
        'methods' => [
            'getCPTs' => [
                'len' => 1
            ],
            'getCPT' => [
                'len' => 1
            ],
            'addCPT' => [
                'len' => 1
            ],
            'updateCPT' => [
                'len' => 1
            ],
            'deleteCPT' => [
                'len' => 1
            ],
            'query' => [
                'len' => 1
            ]
        ]
    ],
    'Insurance' => [
        'methods' => [
            'getInsuranceCompanies' => [
                'len' => 1
            ],
            'getInsuranceCompany' => [
                'len' => 1
            ],
            'addInsuranceCompany' => [
                'len' => 1
            ],
            'updateInsuranceCompany' => [
                'len' => 1
            ],
            'destroyInsuranceCompany' => [
                'len' => 1
            ],
            'getInsuranceNumbers' => [
                'len' => 1
            ],
            'getInsuranceNumber' => [
                'len' => 1
            ],
            'addInsuranceNumber' => [
                'len' => 1
            ],
            'updateInsuranceNumber' => [
                'len' => 1
            ],
            'destroyInsuranceNumber' => [
                'len' => 1
            ],
            'getInsurances' => [
                'len' => 1
            ],
            'getInsurance' => [
                'len' => 1
            ],
            'addInsurance' => [
                'len' => 1
            ],
            'updateInsurance' => [
                'len' => 1
            ],
            'destroyInsurance' => [
                'len' => 1
            ],
            'getPatientPrimaryInsuranceByPid' => [
                'len' => 1
            ],
            'getPatientSecondaryInsuranceByPid' => [
                'len' => 1
            ],
            'getPatientComplementaryInsuranceByPid' => [
                'len' => 1
            ]
        ]
    ],
    'ReferringProviders' => [
        'methods' => [
            'getReferringProviders' => [
                'len' => 1
            ],
            'getReferringProvider' => [
                'len' => 1
            ],
            'addReferringProvider' => [
                'len' => 1
            ],
            'updateReferringProvider' => [
                'len' => 1
            ],
            'deleteReferringProvider' => [
                'len' => 1
            ],
            'getReferringProviderFacilities' => [
                'len' => 1
            ],
            'getReferringProviderFacility' => [
                'len' => 1
            ],
            'addReferringProviderFacility' => [
                'len' => 1
            ],
            'updateReferringProviderFacility' => [
                'len' => 1
            ],
            'deleteReferringProviderFacility' => [
                'len' => 1
            ]
        ]
    ],
    'Disclosure' => [
        'methods' => [
            'getDisclosures' => [
                'len' => 1
            ],
            'getDisclosure' => [
                'len' => 1
            ],
            'addDisclosure' => [
                'len' => 1
            ],
            'updateDisclosure' => [
                'len' => 1
            ],
            'destroyDisclosure' => [
                'len' => 1
            ]
        ]
    ],
    'PatientContacts' => [
        'methods' => [
            'getContacts' => [
                'len' => 1
            ],
            'getContact' => [
                'len' => 1
            ],
            'addContact' => [
                'len' => 1
            ],
            'updateContact' => [
                'len' => 1
            ],
            'destroyContact' => [
                'len' => 1
            ],
            'getSelfContact' => [
                'len' => 1
            ]
        ]
    ],
    'Reminders' => [
        'methods' => [
            'getReminders' => [
                'len' => 1
            ],
            'getReminder' => [
                'len' => 1
            ],
            'addReminder' => [
                'len' => 1
            ],
            'updateReminder' => [
                'len' => 1
            ],
            'destroyReminder' => [
                'len' => 1
            ]
        ]
    ],
    'Notes' => [
        'methods' => [
            'getNotes' => [
                'len' => 1
            ],
            'getNote' => [
                'len' => 1
            ],
            'addNote' => [
                'len' => 1
            ],
            'updateNote' => [
                'len' => 1
            ],
            'destroyNote' => [
                'len' => 1
            ]
        ]
    ],
    'CupTest' => [
        'methods' => [
            'cuptest' => [
                'len' => 1
            ]
        ]
    ],
    'AccAccount' => [
        'methods' => [

        ]
    ],

    'WebSearchCodes' => [
        'methods' => [
			'Search'=> [
				'len'=> 1
            ]
        ]
    ],

	'Modules' => [
		'methods' => [
			'getAllModules' => [
				'len' => 0
            ],
			'getActiveModules' => [
				'len' => 0
            ],
			'getEnabledModules' => [
				'len' => 0
            ],
			'getDisabledModules' => [
				'len' => 0
            ],
			'getModuleByName' => [
				'len' => 1
            ],
			'updateModule' => [
				'len' => 1
            ]
        ]
    ],

	'Emergency' => [
		'methods' => [
			'createNewEmergency' => [
				'len' => 0
            ]
        ]
    ],

	'Snippets' => [
		'methods' => [
			'getSoapSnippets' => [
				'len' => 1
            ],
			'getSoapSnippet' => [
				'len' => 1
            ],
			'addSoapSnippets' => [
				'len' => 1
            ],
			'updateSoapSnippets' => [
				'len' => 1
            ],
			'deleteSoapSnippets' => [
				'len' => 1
            ]
        ]
    ],

	'Orders' => [
		'methods' => [
			'getPatientOrders' => [
				'len' => 1
            ],
            'getPatientLabOrders' => [
                'len' => 1
            ],
			'addPatientOrder' => [
				'len' => 1
            ],
			'updatePatientOrder' => [
				'len' => 1
            ],
			'deletePatientOrder' => [
				'len' => 1
            ],
			'getOrderResults' => [
				'len' => 1
            ],
			'addOrderResults' => [
				'len' => 1
            ],
			'updateOrderResults' => [
				'len' => 1
            ],
			'deleteOrderResults' => [
				'len' => 1
            ],
			'getOrderResultObservations' => [
				'len' => 1
            ],
			'addOrderResultObservations' => [
				'len' => 1
            ],
			'updateOrderResultObservations' => [
				'len' => 1
            ],
			'deleteOrderResultObservations' => [
				'len' => 1
            ]
        ]
    ],
	'Referrals' => [
		'methods' => [
			'getPatientReferrals' => [
				'len' => 1
            ],
			'getPatientReferral' => [
				'len' => 1
            ],
			'addPatientReferral' => [
				'len' => 1
            ],
			'updatePatientReferral' => [
				'len' => 1
            ],
			'deletePatientReferral' => [
				'len' => 1
            ],
        ]
    ],

	'Specialties' => [
		'methods' => [
			'getSpecialties' => [
				'len' => 1
            ],
			'getSpecialty' => [
				'len' => 1
            ],
			'addSpecialty' => [
				'len' => 1
            ],
			'updateSpecialty' => [
				'len' => 1
            ],
			'deleteSpecialty' => [
				'len' => 1
            ],
        ]
    ],

	'DiagnosisCodes' => [
		'methods' => [
			'ICDCodeSearch' => [
				'len' => 1
            ],
			'liveCodeSearch' => [
				'len' => 1
            ],
			'getICDByEid' => [
				'len' => 1
            ]
        ]
    ],

	'Vitals' => [
		'methods' => [
			'getVitals' => [
				'len' => 1
            ],
			'addVitals' => [
				'len' => 1
            ],
			'updateVitals' => [
				'len' => 1
            ],
			'getVitalsByPid' => [
				'len' => 1
            ],
			'getVitalsByEid' => [
				'len' => 1
            ],
			'G2' => [
				'len' => 1
            ]
        ]
    ],

	'CognitiveAndFunctionalStatus' => [
		'methods' => [
			'getPatientCognitiveAndFunctionalStatuses' => [
				'len' => 1
            ],
			'getPatientCognitiveAndFunctionalStatus' => [
				'len' => 1
            ],
			'addPatientCognitiveAndFunctionalStatus' => [
				'len' => 1
            ],
			'updateCognitiveAndFunctionalStatus' => [
				'len' => 1
            ],
			'destroyCognitiveAndFunctionalStatus' => [
				'len' => 1
            ]
        ]
    ],

	'ExternalDataUpdate' => [
		'methods' => [
			'updateCodesWithUploadFile' => [
				'formHandler' => true,
				'len' => 0
            ],
			'getCodeFiles' => [
				'len' => 1
            ],
			'updateCodes' => [
				'len' => 1
            ],
			'getCurrentCodesInfo' => [
				'len' => 0
            ]
        ]
    ],
	/**
	 * Encounter Functions
	 */
	'Encounter' => [
		'methods' => [
			'checkOpenEncountersByPid' => [
				'len' => 1
            ],
			'getEncounters' => [
				'len' => 1
            ],
			'getEncounter' => [
				'len' => 1
            ],
			'getEncounterSummary' => [
				'len' => 1
            ],
			'updateEncounter' => [
				'len' => 1
            ],
			'updateEncounterPriority' => [
				'len' => 1
            ],
			'createEncounter' => [
				'len' => 1
            ],
			'updateSoap' => [
				'len' => 1
            ],
			'getEncounterDxs' => [
				'len' => 1
            ],
			'createEncounterDx' => [
				'len' => 1
            ],
			'updateEncounterDx' => [
				'len' => 1
            ],
			'destroyEncounterDx' => [
				'len' => 1
            ],
			'updateReviewOfSystems' => [
				'len' => 1
            ],
			'updateDictation' => [
				'len' => 1
            ],
			'updateHCFA' => [
				'len' => 1
            ],
			'getProgressNoteByEid' => [
				'len' => 1
            ],
			'signEncounter' => [
				'len' => 1
            ],
			'getEncounterCodes' => [
				'len' => 1
            ],
			'checkoutAlerts' => [
				'len' => 1
            ],
			'checkForAnOpenedEncounterByPid' => [
				'len' => 1
            ],
			'getEncounterFollowUpInfoByEid' => [
				'len' => 1
            ],
			'getEncounterMessageByEid' => [
				'len' => 1
            ],
			'getSoapHistory' => [
				'len' => 1
            ],
			'updateEncounterHCFAOptions' => [
				'len' => 1
            ],
			'onReviewAllItemsToReview' => [
				'len' => 1
            ],
			'getEncountersByDate' => [
				'len' => 1
            ],
			'getTodayEncounters' => [
				'len' => 0
            ],
			'getEncounterPrintDocumentsByEid' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Floor Plans function
	 */
	'FloorPlans' => [
		'methods' => [
			'getFloorPlans' => [
				'len' => 0
            ],
			'createFloorPlan' => [
				'len' => 1
            ],
			'updateFloorPlan' => [
				'len' => 1
            ],
			'removeFloorPlan' => [
				'len' => 1
            ],
			'getFloorPlanZones' => [
				'len' => 1
            ],
			'createFloorPlanZone' => [
				'len' => 1
            ],
			'updateFloorPlanZone' => [
				'len' => 1
            ],
			'removeFloorPlanZone' => [
				'len' => 1
            ],
			'getFloorPlanZonesByFloorPlanId' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Patient Zones
	 */
	'PatientZone' => [
		'methods' => [
			'addPatientToZone' => [
				'len' => 1
            ],
			'removePatientFromZone' => [
				'len' => 1
            ],
			'getPatientsZonesByFloorPlanId' => [
				'len' => 1
            ],
			'removePatientFromZoneByPid' => [
				'len' => 1
            ]
        ]
    ],
	'VectorGraph' => [
		'methods' => [
			'getGraphData' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Calendar Functions
	 */
	'Calendar' => [
		'methods' => [
			'getCalendars' => [
				'len' => 0
            ],
			'getEvents' => [
				'len' => 1
            ],
			'addEvent' => [
				'len' => 1
            ],
			'updateEvent' => [
				'len' => 1
            ],
			'deleteEvent' => [
				'len' => 1
            ],
			'getPatientFutureEvents' => [
				'len' => 1
            ],
        ]
    ],
	/**
	 * Messages Functions
	 */
	'Messages' => [
		'methods' => [
			'getMessages' => [
				'len' => 1
            ],
			'deleteMessage' => [
				'len' => 1
            ],
			'sendNewMessage' => [
				'len' => 1
            ],
			'replyMessage' => [
				'len' => 1
            ],
			'updateMessage' => [
				'len' => 1
            ]
        ]
    ],
    /**
	 * Fees Functions
	 */
	'Fees' => [
		'methods' => [
			'getFilterEncountersBillingData' => [
				'len' => 1
            ],
			'getEncountersByPayment' => [
				'len' => 1
            ],
			'addPayment' => [
				'len' => 1
            ],
			'getPatientBalance' => [
				'len' => 1
            ],
			'getPaymentsBySearch' => [
				'len' => 1
            ]
        ]
    ],
	'CarePlanGoals' => [
		'methods' => [
			'getPatientCarePlanGoals' => [
				'len' => 1
            ],
			'getPatientCarePlanGoal' => [
				'len' => 1
            ],
			'addPatientCarePlanGoal' => [
				'len' => 1
            ],
			'updatePatientCarePlanGoal' => [
				'len' => 1
            ],
			'destroyPatientCarePlanGoal' => [
				'len' => 1
            ]
        ]
    ],
    /**
	 * FamilyHistory Functions
	 */
	'FamilyHistory' => [
		'methods' => [
			'getFamilyHistory' => [
				'len' => 1
            ],
			'addFamilyHistory' => [
				'len' => 1
            ],
			'updateFamilyHistory' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Facilities Functions
	 */
	'Facilities' => [
		'methods' => [
			'getFacilities' => [
				'len' => 1
            ],
			'addFacility' => [
				'len' => 1
            ],
			'updateFacility' => [
				'len' => 1
            ],
			'deleteFacility' => [
				'len' => 1
            ],
			'getFacility' => [
				'len' => 1
            ],
			'setFacility' => [
				'len' => 1
            ],

			'getFacilityConfigs' => [
				'len' => 1
            ],
			'getFacilityConfig' => [
				'len' => 1
            ],
			'addFacilityConfig' => [
				'len' => 1
            ],
			'updateFacilityConfig' => [
				'len' => 1
            ],
			'deleteFacilityConfig' => [
				'len' => 1
            ],

			'getDepartments' => [
				'len' => 1
            ],
			'getDepartment' => [
				'len' => 1
            ],
			'addDepartment' => [
				'len' => 1
            ],
			'updateDepartment' => [
				'len' => 1
            ],
			'deleteDepartment' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Allergies Functions
	 */
	'Allergies' => [
		'methods' => [
			'getPatientAllergies' => [
				'len' => 1
            ],
			'getPatientAllergy' => [
				'len' => 1
            ],
			'addPatientAllergy' => [
				'len' => 1
            ],
			'updatePatientAllergy' => [
				'len' => 1
            ],
			'destroyPatientAllergy' => [
				'len' => 1
            ],
			'searchAllergiesData' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Medical Functions
	 */
//	'Medical' => array(
//		'methods' => array(
//			'getPatientLabsResults' => array(
//				'len' => 1
//			),
//			'addPatientLabsResult' => array(
//				'len' => 1
//			),
//			'updatePatientLabsResult' => array(
//				'len' => 1
//			),
//			'deletePatientLabsResult' => array(
//				'len' => 1
//			),
//			'signPatientLabsResultById' => array(
//				'len' => 1
//			),
//			'reviewMedicalWindowEncounter' => array(
//				'len' => 1
//			),
//			'getLabsLiveSearch' => array(
//				'len' => 1
//			),
//			'reviewAllMedicalWindowEncounter' => array(
//				'len' => 1
//			),
//			'getEncounterReviewByEid' => array(
//				'len' => 1
//			),
//			'getSurgeriesLiveSearch' => array(
//				'len' => 1
//			),
//			'getCDTLiveSearch' => array(
//				'len' => 1
//			)
//		)
//	),
	 /**
	 * AddressBook Functions
	 */
	'AddressBook' => [
		'methods' => [
			'getContacts' => [
				'len' => 1
            ],
			'getContact' => [
				'len' => 1
            ],
			'addContact' => [
				'len' => 1
            ],
			'updateContact' => [
				'len' => 1
            ],
			'destroyContact' => [
				'len' => 1
            ]
        ]
    ],
	'SnomedCodes' => [
		'methods' => [
			'liveCodeSearch' => [
				'len' => 1
            ],
			'liveProblemCodeSearch' => [
				'len' => 1
            ],
			'liveProcedureCodeSearch' => [
				'len' => 1
            ],
			'updateLiveProcedureCodeSearch' => [
				'len' => 1
            ],
			'updateLiveProblemCodeSearch' => [
				'len' => 1
            ]
        ]
    ],
	'Rxnorm' => [
		'methods' => [
			'getRXNORMLiveSearch' => [
				'len' => 1
            ],
			'getRXNORMList' => [
				'len' => 1
            ],
			'getRXNORMAllergyLiveSearch' => [
				'len' => 1
            ],
			'getMedicationAttributesByRxcui' => [
				'len' => 1
            ],
			'getMedicationInstructions' => [
				'len' => 1
            ],
			'getMedicationInstruction' => [
				'len' => 1
            ],
			'addMedicationInstruction' => [
				'len' => 1
            ],
			'updateMedicationInstructions' => [
				'len' => 1
            ],
			'destroyMedicationInstructions' => [
				'len' => 1
            ]
        ]
    ],
	'Medications' => [
		'methods' => [
			'getPatientMedications' => [
				'len' => 1
            ],
            'getPatientMedicationsOrders' => [
                'len' => 1
            ],
			'getPatientMedication' => [
				'len' => 1
            ],
			'addPatientMedication' => [
				'len' => 1
            ],
			'updatePatientMedication' => [
				'len' => 1
            ],
			'destroyPatientMedication' => [
				'len' => 1
            ]
        ]
    ],
	'Immunizations' => [
		'methods' => [
            'getImmunizationsList' => [
                'len' => 0
            ],
            'getPatientImmunizations' => [
                'len' => 1
            ],
            'addPatientImmunization' => [
                'len' => 1
            ],
            'updatePatientImmunization' => [
                'len' => 1
            ],
            'sendVXU' => [
                'len' => 1
            ],
			'getMvx' => [
				'len' => 1
            ],
			'getMvxForCvx' => [
				'len' => 1
            ],
			'getCptByCvx' => [
				'len' => 1
            ],
			'getImmunizationLiveSearch' => [
				'len' => 1
            ],
			'updateMVXCodes' => [
				'len' => 1
            ],
			'updateCVXCodes' => [
				'len' => 1
            ],
			'updateCvxCptTable' => [
				'len' => 1
            ]
        ]
    ],

	'Laboratories' => [
		'methods' => [
			'getLabObservations' => [
				'len' => 1
            ],
			'addLabObservation' => [
				'len' => 1
            ],
			'updateLabObservation' => [
				'len' => 1
            ],
			'removeLabObservation' => [
				'len' => 1
            ],
			'getActiveLaboratoryTypes' => [
				'len' => 1
            ],
			'indexLoincPanels' => [
				'len' => 0
            ],
			'getLabLoincLiveSearch' => [
				'len' => 1
            ],
			'getRadLoincLiveSearch' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Practice Functions
	 */
	'Practice' => [
		'methods' => [
			'getPharmacies' => [
				'len' => 0
            ],
			'addPharmacy' => [
				'len' => 1
            ],
			'updatePharmacy' => [
				'len' => 1
            ],
			'getLaboratories' => [
				'len' => 0
            ],
			'addLaboratory' => [
				'len' => 1
            ],
			'updateLaboratory' => [
				'len' => 1
            ],
			'getInsurances' => [
				'len' => 0
            ],
			'addInsurance' => [
				'len' => 1
            ],
			'updateInsurance' => [
				'len' => 1
            ],
			'getInsuranceNumbers' => [
				'len' => 1
            ],
			'getX12Partners' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Globals Functions
	 */
	'Globals' => [
		'methods' => [
			'setGlobals' => [
				'len' => 0
            ],
			'getGlobals' => [
				'len' => 0
            ],
			'getAllGlobals' => [
				'len' => 0
            ],
			'updateGlobals' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Lists Functions
	 */
	'Lists' => [
		'methods' => [
			'getOptions' => [
				'len' => 1
            ],
			'addOption' => [
				'len' => 1
            ],
			'updateOption' => [
				'len' => 1
            ],
			'deleteOption' => [
				'len' => 1
            ],
			'sortOptions' => [
				'len' => 1
            ],
			'getLists' => [
				'len' => 1
            ],
			'addList' => [
				'len' => 1
            ],
			'updateList' => [
				'len' => 1
            ],
			'deleteList' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Office Notes Functions
	 */
	'OfficeNotes' => [
		'methods' => [
			'getOfficeNotes' => [
				'len' => 1
            ],
			'addOfficeNotes' => [
				'len' => 1
            ],
			'updateOfficeNotes' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Social History Functions
	 */
	'SocialHistory' => [
		'methods' => [
			'getSocialHistories' => [
				'len' => 1
            ],
			'getSocialHistory' => [
				'len' => 1
            ],
			'addSocialHistory' => [
				'len' => 1
            ],
			'updateSocialHistory' => [
				'len' => 1
            ],
			'destroySocialHistory' => [
				'len' => 1
            ],
			'getSmokeStatus' => [
				'len' => 1
            ],
			'addSmokeStatus' => [
				'len' => 1
            ],
			'updateSmokeStatus' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Prescriptions Functions
	 */
	'Prescriptions' => [
		'methods' => [
			'getPrescriptions' => [
				'len' => 1
            ],
			'addPrescription' => [
				'len' => 1
            ],
			'updatePrescription' => [
				'len' => 1
            ],
			'getPrescriptionMedications' => [
				'len' => 1
            ],
			'addPrescriptionMedication' => [
				'len' => 1
            ],
			'updatePrescriptionMedication' => [
				'len' => 1
            ],
			'getSigCodesByQuery' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Services Functions
	 */
	'DataManager' => [
		'methods' => [
			'getServices' => [
				'len' => 1
            ],
			'addService' => [
				'len' => 1
            ],
			'updateService' => [
				'len' => 1
            ],
			'liveCodeSearch' => [
				'len' => 1
            ],
			'getCptCodes' => [
				'len' => 1
            ],
			'addCptCode' => [
				'len' => 1
            ],
			'updateCptCode' => [
				'len' => 1
            ],
			'deleteCptCode' => [
				'len' => 1
            ],
			'getActiveProblems' => [
				'len' => 1
            ],
			'addActiveProblems' => [
				'len' => 1
            ],
			'removeActiveProblems' => [
				'len' => 1
            ],
			'getMedications' => [
				'len' => 1
            ],
			'addMedications' => [
				'len' => 1
            ],
			'removeMedications' => [
				'len' => 1
            ],
			'updateMedications' => [
				'len' => 1
            ],
			'getAllLabObservations' => [
				'len' => 1
            ],
			'getLabObservations' => [
				'len' => 1
            ],
			'addLabObservation' => [
				'len' => 1
            ],
			'updateLabObservation' => [
				'len' => 1
            ],
			'removeLabObservation' => [
				'len' => 1
            ],
			'getActiveLaboratoryTypes' => [
				'len' => 0
            ]
        ]
    ],
	'Services' => [
		'methods' => [

            'getEncounterServices' => [
				'len' => 1
            ],
            'getEncounterService' => [
				'len' => 1
            ],
            'addEncounterService' => [
				'len' => 1
            ],
            'updateEncounterService' => [
				'len' => 1
            ],
            'removeEncounterService' => [
				'len' => 1
            ],



			'getServices' => [
				'len' => 1
            ],

			'addService' => [
				'len' => 1
            ],
			'updateService' => [
				'len' => 1
            ],
			'liveCodeSearch' => [
				'len' => 1
            ],
			'getCptCodes' => [
				'len' => 1
            ]
        ]
    ],

	/**
	 * DecisionSupport Functions
	 */
	'DecisionSupport' => [
		'methods' => [
			'getDecisionSupportRules' => [
				'len' => 1
            ],
			'getDecisionSupportRule' => [
				'len' => 1
            ],
			'addDecisionSupportRule' => [
				'len' => 1
            ],
			'updateDecisionSupportRule' => [
				'len' => 1
            ],
			'deleteDecisionSupportRule' => [
				'len' => 1
            ],
			'getDecisionSupportRuleConcepts' => [
				'len' => 1
            ],
			'getDecisionSupportRuleConcept' => [
				'len' => 1
            ],
			'addDecisionSupportRuleConcept' => [
				'len' => 1
            ],
			'updateDecisionSupportRuleConcept' => [
				'len' => 1
            ],
			'deleteDecisionSupportRuleConcept' => [
				'len' => 1
            ],
			'getAlerts' => [
				'len' => 1
            ]
        ]
    ],
	'ActiveProblems' => [
		'methods' => [
			'getPatientActiveProblems' => [
				'len' => 1
            ],
			'getPatientActiveProblem' => [
				'len' => 1
            ],
			'addPatientActiveProblem' => [
				'len' => 1
            ],
			'updatePatientActiveProblem' => [
				'len' => 1
            ],
			'destroyPatientActiveProblem' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Form layout Engine Functions
	 */
	'FormLayoutEngine' => [
		'methods' => [
			'getFields' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Pool Area Functions
	 */
	'PoolArea' => [
		'methods' => [
			'getPatientsArrivalLog' => [
				'len' => 1
            ],
			'addPatientArrivalLog' => [
				'len' => 1
            ],
			'updatePatientArrivalLog' => [
				'len' => 1
            ],
			'removePatientArrivalLog' => [
				'len' => 1
            ],
			'getPoolAreaPatients' => [
				'len' => 1
            ],
			'sendPatientToPoolArea' => [
				'len' => 1
            ],
			'getActivePoolAreas' => [
				'len' => 0
            ],
			'getFacilityActivePoolAreas' => [
				'len' => 0
            ],
			'getPatientsByPoolAreaAccess' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Form layout Builder Functions
	 */
	'FormLayoutBuilder' => [
		'methods' => [
			'getFormDataTable' => [
				'len' => 1
            ],
			'getForms' => [
				'len' => 1
            ],
			'getParentFields' => [
				'len' => 1
            ],
			'getFormFieldsTree' => [
				'len' => 1
            ],
			'createFormField' => [
				'len' => 1
            ],
			'updateFormField' => [
				'len' => 1
            ],
			'removeFormField' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Form layout Builder Functions
	 */
	'CCR' => [
		'methods' => [
			'createCCR' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Patient Functions
	 */
	'Patient' => [
		'methods' => [
			'getPatients' => [
				'len' => 1
            ],
			'savePatient' => [
				'len' => 1
            ],
			'getInsurances' => [
				'len' => 1
            ],
			'saveInsurance' => [
				'len' => 1
            ],
			'createNewPatient' => [
				'len' => 1
            ],
			'patientLiveSearch' => [
				'len' => 1
            ],
			'getPatientSetDataByPid' => [
				'len' => 1
            ],
			'unsetPatient' => [
				'len' => 1
            ],
			'getPatientDemographicData' => [
				'len' => 1
            ],
			'updatePatientDemographicData' => [
				'len' => 1
            ],
			'addPatientNoteAndReminder' => [
				'len' => 1
            ],
			'getPatientReminders' => [
				'len' => 1
            ],
			'getPatientNotes' => [
				'len' => 1
            ],
			'getPatientDocuments' => [
				'len' => 1
            ],
			'getPatientDocumentsByEid' => [
				'len' => 1
            ],
			'getMeaningfulUserAlertByPid' => [
				'len' => 1
            ],
			'getPatientInsurancesCardsUrlByPid' => [
				'len' => 1
            ],
			'getPatientDisclosures' => [
				'len' => 1
            ],
			'createPatientDisclosure' => [
				'len' => 1
            ],
			'updatePatientDisclosure' => [
				'len' => 1
            ],
			'addPatientReminders' => [
				'len' => 1
            ],
			'updatePatientReminders' => [
				'len' => 1
            ],
			'addPatientNotes' => [
				'len' => 1
            ],
			'updatePatientNotes' => [
				'len' => 1
            ],
			'setPatientRating' => [
				'len' => 1
            ],
			'getPossibleDuplicatesByDemographic' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * User Functions
	 */
	'User' => [
		'methods' => [
			'getUsers' => [
				'len' => 1
            ],
			'getUser' => [
				'len' => 1
            ],
			'addUser' => [
				'len' => 1
            ],
			'updateUser' => [
				'len' => 1
            ],
			'updatePassword' => [
				'len' => 1
            ],
			'usernameExist' => [
				'len' => 1
            ],
			'getCurrentUserData' => [
				'len' => 0
            ],
			'getCurrentUserBasicData' => [
				'len' => 0
            ],
			'updateMyAccount' => [
				'len' => 1
            ],
			'verifyUserPass' => [
				'len' => 1
            ],
			'getProviders' => [
				'len' => 0
            ],
			'getActiveProviders' => [
				'len' => 0
            ],
			'getUserFullNameById' => [
				'len' => 1
            ],
			'userLiveSearch' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Authorization Procedures Functions
	 */
	'authProcedures' => [
		'methods' => [
			'login' => [
				'len' => 1
            ],
			'ckAuth' => [
				'len' => 0
            ],
			'unAuth' => [
				'len' => 0
            ],
			'getSites' => [
				'len' => 0
            ]
        ]
    ],
	/**
	 * Comobo Boxes Data Functions
	 */
	'CombosData' => [
		'methods' => [
			'getOptionsByListId' => [
				'len' => 1
            ],
			'getTimeZoneList' => [
				'len' => 1
            ],
			'getActivePharmacies' => [
				'len' => 0
            ],
			'getUsers' => [
				'len' => 0
            ],
			'getLists' => [
				'len' => 0
            ],
			'getFacilities' => [
				'len' => 0
            ],
			'getActiveFacilities' => [
				'len' => 0
            ],
			'getBillingFacilities' => [
				'len' => 0
            ],
			'getRoles' => [
				'len' => 0
            ],
			'getCodeTypes' => [
				'len' => 0
            ],
			'getCalendarCategories' => [
				'len' => 0
            ],
			'getFloorPlanAreas' => [
				'len' => 0
            ],
			'getAuthorizations' => [
				'len' => 0
            ],
			'getSeeAuthorizations' => [
				'len' => 0
            ],
			'getTaxIds' => [
				'len' => 0
            ],
			'getFiledXtypes' => [
				'len' => 0
            ],
			'getPosCodes' => [
				'len' => 0
            ],
			'getAllergyTypes' => [
				'len' => 0
            ],
			'getAllergiesByType' => [
				'len' => 1
            ],
			'getTemplatesTypes' => [
				'len' => 0
            ],
			'getActiveInsurances' => [
				'len' => 0
            ],
			'getThemes' => [
				'len' => 0
            ],
			'getEncounterSupervisors' => [
				'len' => 0
            ],
			'getDisplayValueByListIdAndOptionValue' => [
				'len' => 2
            ],
			'getDisplayValueByListIdAndOptionCode' => [
				'len' => 2
            ]
        ]
    ],
	/**
	 * Navigation Function
	 */
	'Navigation' => [
		'methods' => [
			'getNavigation' => [
				'len' => 0
            ]
        ]
    ],
	/**
	 * Navigation Function
	 */
	'Roles' => [
		'methods' => [
			'getRolePerms' => [
				'len' => 0
            ],
			'updateRolePerm' => [
				'len' => 1
            ],
			'getRolesData' => [
				'len' => 0
            ],
			'saveRolesData' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Navigation Function
	 */
	'ACL' => [
		'methods' => [

			'getAclGroups' => [
				'len' => 1
            ],
			'getAclGroup' => [
				'len' => 1
            ],
			'addAclGroup' => [
				'len' => 1
            ],
			'updateAclGroup' => [
				'len' => 1
            ],
			'deleteAclGroup' => [
				'len' => 1
            ],
			'getGroupPerms' => [
				'len' => 1
            ],
			'updateGroupPerms' => [
				'len' => 1
            ],
			'getAclRoles' => [
				'len' => 1
            ],
			'getAclRole' => [
				'len' => 1
            ],
			'addAclRole' => [
				'len' => 1
            ],
			'updateAclRole' => [
				'len' => 1
            ],


			'getAllUserPermsAccess' => [
				'len' => 0
            ],
			'hasPermission' => [
				'len' => 1
            ],
			'emergencyAccess' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Navigation Function
	 */
	'Documents' => [
		'methods' => [
			'updateDocumentsTitle' => [
				'len' => 1
            ]
        ]
    ],
	/**
	 * Document Handler functions
	 */
	'DocumentHandler' => [
		'methods' => [
			'getPatientDocuments' => [
				'len' => 1
            ],
			'getPatientDocument' => [
				'len' => 1
            ],
			'addPatientDocument' => [
				'len' => 1
            ],
			'updatePatientDocument' => [
				'len' => 1
            ],
			'destroyPatientDocument' => [
				'len' => 1
            ],
			'createTempDocument' => [
				'len' => 1
            ],
			'createRawTempDocument' => [
				'len' => 1
            ],
			'destroyTempDocument' => [
				'len' => 1
            ],
			'transferTempDocument' => [
				'len' => 1
            ],
			'uploadDocument' => [
				'formHandler' => true,
				'len' => 1
            ],
			'getDocumentsTemplates' => [
				'len' => 1
            ],
			'addDocumentsTemplates' => [
				'len' => 1
            ],
			'updateDocumentsTemplates' => [
				'len' => 1
            ],
			'getHeadersAndFootersTemplates' => [
				'len' => 1
            ],
			'getDefaultDocumentsTemplates' => [
				'len' => 1
            ],
			'createDocument' => [
				'len' => 1
            ],
			'createDocumentDoctorsNote' => [
				'len' => 1
            ],
			'checkDocHash' => [
				'len' => 1
            ],
			'convertDocuments' => [
				'len' => 1
            ]
        ]
    ],
	'DoctorsNotes' => [
		'methods' => [
			'getDoctorsNotes' => [
				'len' => 1
            ],
			'getDoctorsNote' => [
				'len' => 1
            ],
			'addDoctorsNote' => [
				'len' => 1
            ],
			'updateDoctorsNote' => [
				'len' => 1
            ],
			'destroyDoctorsNote' => [
				'len' => 1
            ]
        ]
    ],
	'File' => [
		'methods' => [
			'savePatientBase64Document' => [
				'len' => 1
            ]
        ]
    ],
	'CronJob' => [
		'methods' => [
			'run' => [
				'len' => 0
            ]
        ]
    ],
	'i18nRouter' => [
		'methods' => [
			'getTranslation' => [
				'len' => 0
            ],
			'getDefaultLanguage' => [
				'len' => 0
            ],
			'getAvailableLanguages' => [
				'len' => 0
            ]
        ]
    ],
	'SiteSetup' => [
		'methods' => [
			'checkDatabaseCredentials' => [
				'len' => 1
            ],
			'checkRequirements' => [
				'len' => 0
            ],
			'setSiteDirBySiteId' => [
				'len' => 1
            ],
			'createDatabaseStructure' => [
				'len' => 1
            ],
			'loadDatabaseData' => [
				'len' => 1
            ],
			'createSiteAdmin' => [
				'len' => 1
            ],
			'createConfigurationFile' => [
				'len' => 1
            ],
			'loadCode' => [
				'len' => 1
            ]
        ]
    ],
	'Applications' => [
		'methods' => [
			'getApplications' => [
				'len' => 1
            ],
			'addApplication' => [
				'len' => 1
            ],
			'updateApplication' => [
				'len' => 1
            ],
			'deleteApplication' => [
				'len' => 1
            ]
        ]
    ],
	'HL7Server' => [
		'methods' => [
			'getServers' => [
				'len' => 1
            ],
			'addServer' => [
				'len' => 1
            ],
			'updateServer' => [
				'len' => 1
            ],
			'deleteServer' => [
				'len' => 1
            ],
        ]
    ],
	'HL7Clients' => [
		'methods' => [
			'getClients' => [
				'len' => 1
            ],
			'addClient' => [
				'len' => 1
            ],
			'updateClient' => [
				'len' => 1
            ],
			'deleteClient' => [
				'len' => 1
            ]
        ]
    ],
	'HL7ServerHandler' => [
		'methods' => [
			'start' => [
				'len' => 1
            ],
			'stop' => [
				'len' => 1
            ],
			'status' => [
				'len' => 1
            ]
        ]
    ],
	'HL7Messages' => [
		'methods' => [
			'getMessages' => [
				'len' => 1
            ],
			'getMessage' => [
				'len' => 1
            ],
			'getMessageById' => [
				'len' => 1
            ],
			'sendVXU' => [
				'len' => 1
            ],
            'downloadVXU' => [
                'len' => 1
            ],
			'broadcastADT' => [
				'len' => 1
            ],
			'sendADT' => [
				'len' => 1
            ]
        ]
    ],
	'Encryption' => [
		'methods' => [
			'Encrypt' => [
				'len' => 1
            ],
			'Decrypt' => [
				'len' => 1
            ]
        ]
    ],
	'Test' => [
		'methods' => [
			't1' => [
				'len' => 0
            ],
			't2' => [
				'len' => 1
            ]
        ]
    ],
    'TransactionLog' => [
        'methods' => [
            'saveExportLog' => [
                'len' => 1
            ]
        ]
    ],
    'EncounterEventHistory' => [
        'methods' => [
            'getLogs' => [
                'len' => 1
            ],
            'getLog' => [
                'len' => 1
            ],
            'setLog' => [
                'len' => 1
            ]
        ]
    ],
    'DrugInteractions' => [
        'methods' => [
            'getDrugInteractions' => [
                'len' => 1
            ]
        ]
    ]
];
