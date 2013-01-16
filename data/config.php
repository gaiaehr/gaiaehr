<?php
$API = array(
	'Modules' => array(
		'methods' => array(
			'getAllModules' => array(
				'len' => 0
			),
			'getActiveModules' => array(
				'len' => 0
			),
			'getEnabledModules' => array(
				'len' => 0
			),
			'getDisabledModules' => array(
				'len' => 0
			),
			'getModuleByName' => array(
				'len' => 1
			),
			'updateModule' => array(
				'len' => 1
			)
		)
	),
	'Emergency' => array(
		'methods' => array(
			'createNewEmergency' => array(
				'len' => 0
            )
        )
	),
	'Orders' => array(
		'methods' => array(
			'getPatientLabOrders' => array(
				'len' => 1
            ),
			'addPatientLabOrder' => array(
				'len' => 1
            ),
			'updatePatientLabOrder' => array(
				'len' => 1
            ),
			'getPatientXrayCtOrders' => array(
				'len' => 1
            ),
			'addPatientXrayCtOrder' => array(
				'len' => 1
            ),
			'updatePatientXrayCtOrder' => array(
				'len' => 1
            )
		)
	),
	'DiagnosisCodes' => array(
		'methods' => array(
			'ICDCodeSearch' => array(
				'len' => 1
			),
			'liveCodeSearch' => array(
				'len' => 1
			),
			'getICDByEid' => array(
				'len' => 1
			)
		)
	),
	'ExternalDataUpdate' => array(
		'methods' => array(
			'updateCodesWithUploadFile' => array(
				'formHandler' => true,
				'len' => 0
			),
			'getCodeFiles' => array(
				'len' => 1
			),
			'updateCodes' => array(
				'len' => 1
			),
			'getCurrentCodesInfo' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Encounter Functions
	 */
	'Encounter' => array(
		'methods' => array(
			'checkOpenEncounters' => array(
				'len' => 0
			),
			'getEncounters' => array(
				'len' => 1
			),
			'getEncounter' => array(
				'len' => 1
			),
			'getEncounterSummary' => array(
				'len' => 1
			),
			'updateEncounter' => array(
				'len' => 1
			),
			'updateEncounterPriority' => array(
				'len' => 1
			),
			'getVitals' => array(
				'len' => 1
			),
			'addVitals' => array(
				'len' => 1
			),
			'updateVitals' => array(
				'len' => 1
			),
			'createEncounter' => array(
				'len' => 1
			),
			'updateSoapById' => array(
				'len' => 1
			),
			'getEncounterIcdxCodes' => array(
				'len' => 1
			),
			'updateEncounterIcdxCodes' => array(
				'len' => 1
			),
			'updateReviewOfSystemsChecksById' => array(
				'len' => 1
			),
			'updateReviewOfSystemsById' => array(
				'len' => 1
			),
			'updateDictationById' => array(
				'len' => 1
			),
			'getProgressNoteByEid' => array(
				'len' => 1
			),
			'closeEncounter' => array(
				'len' => 1
			),
			'getEncounterEventHistory' => array(
				'len' => 1
			),
			'getEncounterCodes' => array(
				'len' => 1
			),
			'checkoutAlerts' => array(
				'len' => 1
			),
			'checkForAnOpenedEncounterByPid' => array(
				'len' => 1
			),
			'getEncounterFollowUpInfoByEid' => array(
				'len' => 1
			),
			'getEncounterMessageByEid' => array(
				'len' => 1
			),
			'onSaveItemsToReview' => array(
				'len' => 1
			),
			'getSoapHistory' => array(
				'len' => 1
			),
			'updateEncounterHCFAOptions' => array(
				'len' => 1
			),
			'onReviewAllItemsToReview' => array(
				'len' => 1
			)
		)
	),
	/*
	 * Floor Plans function
	 */
	'FloorPlans' => array(
		'methods' => array(
			'getFloorPlans' => array(
				'len' => 0
			),
			'createFloorPlan' => array(
				'len' => 1
			),
			'updateFloorPlan' => array(
				'len' => 1
			),
			'removeFloorPlan' => array(
				'len' => 1
			),
			'getFloorPlanZones' => array(
				'len' => 1
			),
			'createFloorPlanZone' => array(
				'len' => 1
			),
			'updateFloorPlanZone' => array(
				'len' => 1
			),
			'removeFloorPlanZone' => array(
				'len' => 1
			),
			'setPatientToZone' => array(
				'len' => 1
			),
			'unSetPatientZoneByPatientZoneId' => array(
				'len' => 1
			),
			'getPatientsZonesByFloorPlanId' => array(
				'len' => 1
			),
			'unSetPatientFromZoneByPid' => array(
				'len' => 1
			)
		)
	),
	'VectorGraph' => array(
		'methods' => array(
			'getGraphData' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Calendar Functions
	 */
	'Calendar' => array(
		'methods' => array(
			'getCalendars' => array(
				'len' => 0
			),
			'getEvents' => array(
				'len' => 1
			),
			'addEvent' => array(
				'len' => 1
			),
			'updateEvent' => array(
				'len' => 1
			),
			'deleteEvent' => array(
				'len' => 1
			),
			'getPatientFutureEvents' => array(
				'len' => 1
			),
		)
	),
	/**
	 * Messages Functions
	 */
	'Messages' => array(
		'methods' => array(
			'getMessages' => array(
				'len' => 1
			),
			'deleteMessage' => array(
				'len' => 1
			),
			'sendNewMessage' => array(
				'len' => 1
			),
			'replyMessage' => array(
				'len' => 1
			),
			'updateMessage' => array(
				'len' => 1
			)
		)
	), /**
	 * Fees Functions
	 */
	'Fees' => array(
		'methods' => array(
			'getFilterEncountersBillingData' => array(
				'len' => 1
			),
			'getEncountersByPayment' => array(
				'len' => 1
			),
			'addPayment' => array(
				'len' => 1
			),
			'getPatientBalance' => array(
				'len' => 1
			),
			'getPaymentsBySearch' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Facilities Functions
	 */
	'Facilities' => array(
		'methods' => array(
			'getFacilities' => array(
				'len' => 1
			),
			'addFacility' => array(
				'len' => 1
			),
			'updateFacility' => array(
				'len' => 1
			),
			'deleteFacility' => array(
				'len' => 1
			)
		)
	),
	/*
	 * Document Handler functions
	 */
	'DocumentHandler' => array(
		'methods' => array(
			'uploadDocument' => array(
				'formHandler' => true,
				'len' => 1
			),
			'getDocumentsTemplates' => array(
				'len' => 1
			),
			'addDocumentsTemplates' => array(
				'len' => 1
			),
			'updateDocumentsTemplates' => array(
				'len' => 1
			),
			'getHeadersAndFootersTemplates' => array(
				'len' => 1
			),
			'getDefaultDocumentsTemplates' => array(
				'len' => 1
			),
			'createDocument' => array(
				'len' => 1
			),
			'createDocumentDoctorsNote' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Medical Functions
	 */
	'Medical' => array(
		'methods' => array(
			'getImmunizationsList' => array(
				'len' => 0
			),
			'getPatientImmunizations' => array(
				'len' => 1
			),
			'addPatientImmunization' => array(
				'len' => 1
			),
			'updatePatientImmunization' => array(
				'len' => 1
			),
			'getPatientAllergies' => array(
				'len' => 1
			),
			'addPatientAllergies' => array(
				'len' => 1
			),
			'updatePatientAllergies' => array(
				'len' => 1
			),
			'getMedicalIssues' => array(
				'len' => 1
			),
			'addMedicalIssues' => array(
				'len' => 1
			),
			'updateMedicalIssues' => array(
				'len' => 1
			),
			'getPatientSurgery' => array(
				'len' => 1
			),
			'addPatientSurgery' => array(
				'len' => 1
			),
			'updatePatientSurgery' => array(
				'len' => 1
			),
			'getPatientDental' => array(
				'len' => 1
			),
			'addPatientDental' => array(
				'len' => 1
			),
			'updatePatientDental' => array(
				'len' => 1
			),
			'getPatientMedications' => array(
				'len' => 1
			),
			'addPatientMedications' => array(
				'len' => 1
			),
			'updatePatientMedications' => array(
				'len' => 1
			),
			'getMedicationLiveSearch' => array(
				'len' => 1
			),
			'getPatientLabsResults' => array(
				'len' => 1
			),
			'addPatientLabsResult' => array(
				'len' => 1
			),
			'updatePatientLabsResult' => array(
				'len' => 1
			),
			'deletePatientLabsResult' => array(
				'len' => 1
			),
			'signPatientLabsResultById' => array(
				'len' => 1
			),
			'reviewMedicalWindowEncounter' => array(
				'len' => 1
			),
			'getLabsLiveSearch' => array(
				'len' => 1
			),
			'reviewAllMedicalWindowEncounter' => array(
				'len' => 1
			),
			'getEncounterReviewByEid' => array(
				'len' => 1
			),
			'getSurgeriesLiveSearch' => array(
				'len' => 1
			),
			'getCDTLiveSearch' => array(
				'len' => 1
			),
			'getPatientsMedicalSummaryGrouped' => array(
				'len' => 1
			)
		)
	),
	 /**
	 * AddressBook Functions
	 */
	'AddressBook' => array(
		'methods' => array(
			'getAddresses' => array(
				'len' => 1
			),
			'addContact' => array(
				'len' => 1
			),
			'updateAddress' => array(
				'len' => 1
			)
		)
	),
	'ServiceCodes' => array(
		'methods' => array(
			'liveCodeSearch' => array(
				'len' => 1
			)
		)
	),
	'Rxnorm' => array(
		'methods' => array(
			'getRXNORMLiveSearch' => array(
				'len' => 1
			),
			'getMedicationAttributesByCODE' => array(
				'len' => 1
			)
		)
	),
	'Medications' => array(
		'methods' => array(
			'getMedications' => array(
				'len' => 1
			),
			'addMedications' => array(
				'len' => 1
			),
			'removeMedications' => array(
				'len' => 1
			),
			'updateMedications' => array(
				'len' => 1
			),
		)
	),
	'Immunizations' => array(
		'methods' => array(
			'getMvx' => array(
				'len' => 1
			),
			'getMvxForCvx' => array(
				'len' => 1
			),
			'getImmunizationLiveSearch' => array(
				'len' => 1
			),
		)
	),	'Xrays' => array(
		'methods' => array(
			'getXrays' => array(
				'len' => 1
			),
			'getXraysLiveSearch' => array(
				'len' => 1
			),
		)
	),
	'Laboratories' => array(
		'methods' => array(
			'getLabObservations' => array(
				'len' => 1
			),
			'addLabObservation' => array(
				'len' => 1
			),
			'updateLabObservation' => array(
				'len' => 1
			),
			'removeLabObservation' => array(
				'len' => 1
			),
			'getActiveLaboratoryTypes' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Practice Functions
	 */
	'Practice' => array(
		'methods' => array(
			'getPharmacies' => array(
				'len' => 0
			),
			'addPharmacy' => array(
				'len' => 1
			),
			'updatePharmacy' => array(
				'len' => 1
			),
			'getLaboratories' => array(
				'len' => 0
			),
			'addLaboratory' => array(
				'len' => 1
			),
			'updateLaboratory' => array(
				'len' => 1
			),
			'getInsurances' => array(
				'len' => 0
			),
			'addInsurance' => array(
				'len' => 1
			),
			'updateInsurance' => array(
				'len' => 1
			),
			'getInsuranceNumbers' => array(
				'len' => 1
			),
			'getX12Partners' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Globals Functions
	 */
	'Globals' => array(
		'methods' => array(
			'setGlobals' => array(
				'len' => 0
			),
			'getGlobals' => array(
				'len' => 0
			),
			'getAllGlobals' => array(
				'len' => 0
			),
			'updateGlobals' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Lists Functions
	 */
	'Lists' => array(
		'methods' => array(
			'getOptions' => array(
				'len' => 1
			),
			'addOption' => array(
				'len' => 1
			),
			'updateOption' => array(
				'len' => 1
			),
			'deleteOption' => array(
				'len' => 1
			),
			'sortOptions' => array(
				'len' => 1
			),
			'getLists' => array(
				'len' => 1
			),
			'addList' => array(
				'len' => 1
			),
			'updateList' => array(
				'len' => 1
			),
			'deleteList' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Office Notes Functions
	 */
	'OfficeNotes' => array(
		'methods' => array(
			'getOfficeNotes' => array(
				'len' => 1
			),
			'addOfficeNotes' => array(
				'len' => 1
			),
			'updateOfficeNotes' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Prescriptions Functions
	 */
	'Prescriptions' => array(
		'methods' => array(
			'getPrescriptions' => array(
				'len' => 1
			),
			'addPrescription' => array(
				'len' => 1
			),
			'updatePrescription' => array(
				'len' => 1
			),
			'getPrescriptionMedications' => array(
				'len' => 1
			),
			'addPrescriptionMedication' => array(
				'len' => 1
			),
			'updatePrescriptionMedication' => array(
				'len' => 1
			),
			'getSigCodesByQuery' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Services Functions
	 */
	'DataManager' => array(
		'methods' => array(
			'getServices' => array(
				'len' => 1),
			'addService' => array(
				'len' => 1
			),
			'updateService' => array(
				'len' => 1
			),
			'liveCodeSearch' => array(
				'len' => 1
			),
			'getCptCodes' => array(
				'len' => 1
			),
			'addCptCode' => array(
				'len' => 1
			),
			'updateCptCode' => array(
				'len' => 1
			),
			'deleteCptCode' => array(
				'len' => 1
			),
			'getActiveProblems' => array(
				'len' => 1
			),
			'addActiveProblems' => array(
				'len' => 1
			),
			'removeActiveProblems' => array(
				'len' => 1
			),
			'getMedications' => array(
				'len' => 1
			),
			'addMedications' => array(
				'len' => 1
			),
			'removeMedications' => array(
				'len' => 1
			),
			'updateMedications' => array(
				'len' => 1
			),
			'getAllLabObservations' => array(
				'len' => 1
			),
			'getLabObservations' => array(
				'len' => 1
			),
			'addLabObservation' => array(
				'len' => 1
			),
			'updateLabObservation' => array(
				'len' => 1
			),
			'removeLabObservation' => array(
				'len' => 1
			),
			'getActiveLaboratoryTypes' => array(
				'len' => 0
			)
		)
	),
	'Services' => array(
		'methods' => array(
			'getServices' => array(
				'len' => 1
			),
			'addService' => array(
				'len' => 1
			),
			'updateService' => array(
				'len' => 1
			),
			'liveCodeSearch' => array(
				'len' => 1
			),
			'getCptCodes' => array(
				'len' => 1
			),
			'addCptCode' => array(
				'len' => 1
			),
			'updateCptCode' => array(
				'len' => 1
			),
			'deleteCptCode' => array(
				'len' => 1
			),
			'getActiveProblems' => array(
				'len' => 1
			),
			'addActiveProblems' => array(
				'len' => 1
			),
			'removeActiveProblems' => array(
				'len' => 1
			),
			'getMedications' => array(
				'len' => 1
			),
			'addMedications' => array(
				'len' => 1
			),
			'removeMedications' => array(
				'len' => 1
			),
			'updateMedications' => array(
				'len' => 1
			),
			'getAllLabObservations' => array(
				'len' => 1
			),
			'getLabObservations' => array(
				'len' => 1
			),
			'addLabObservation' => array(
				'len' => 1
			),
			'updateLabObservation' => array(
				'len' => 1
			),
			'removeLabObservation' => array(
				'len' => 1
			),
			'getActiveLaboratoryTypes' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Preventive Care Functions
	 */
	'PreventiveCare' => array(
		'methods' => array(
			'getServices' => array(
				'len' => 1
			),
			'addService' => array(
				'len' => 1
			),
			'updateService' => array(
				'len' => 1
			),
			'liveCodeSearch' => array(
				'len' => 1
			),
			'getCptCodes' => array(
				'len' => 1
			),
			'addCptCode' => array(
				'len' => 1
			),
			'updateCptCode' => array(
				'len' => 1
			),
			'deleteCptCode' => array(
				'len' => 1
			),
			'getActiveProblems' => array(
				'len' => 1
			),
			'addActiveProblems' => array(
				'len' => 1
			),
			'removeActiveProblems' => array(
				'len' => 1
			),
			'getMedications' => array(
				'len' => 1
			),
			'addMedications' => array(
				'len' => 1
			),
			'removeMedications' => array(
				'len' => 1
			),
			'updateMedications' => array(
				'len' => 1
			),
			'getRelations' => array(
				'len' => 1
			),
			'addRelations' => array(
				'len' => 1
			),
			'removeRelations' => array(
				'len' => 1
			),
			'getImmunizationsCheck' => array(
				'len' => 1
			),
			'getPreventiveCareCheck' => array(
				'len' => 1
			),
			'activePreventiveCareAlert' => array(
				'len' => 1
			),
			'addPreventivePatientDismiss' => array(
				'len' => 1
			),
			'getGuideLinesByCategory' => array(
				'len' => 1
			),
			'addGuideLine' => array(
				'len' => 1
			),
			'updateGuideLine' => array(
				'len' => 1
			),
			'getGuideLineActiveProblems' => array(
				'len' => 1
			),
			'addGuideLineActiveProblems' => array(
				'len' => 1
			),
			'removeGuideLineActiveProblems' => array(
				'len' => 1
			),
			'getGuideLineMedications' => array(
				'len' => 1
			),
			'addGuideLineMedications' => array(
				'len' => 1
			),
			'removeGuideLineMedications' => array(
				'len' => 1
			),
			'getGuideLineLabs' => array(
				'len' => 1
			),
			'addGuideLineLabs' => array(
				'len' => 1
			),
			'removeGuideLineLabs' => array(
				'len' => 1
			),
			'updateGuideLineLabs' => array(
				'len' => 1
			),
			'getPreventiveCareDismissPatientByEncounterID' => array(
				'len' => 1
			),
			'getPreventiveCareDismissedAlertsByPid' => array(
				'len' => 1
			),
			'updatePreventiveCareDismissedAlertsByPid' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Form layout Engine Functions
	 */
	'FormLayoutEngine' => array(
		'methods' => array(
			'getFields' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Pool Area Functions
	 */
	'PoolArea' => array(
		'methods' => array(
			'getPatientsArrivalLog' => array(
				'len' => 1
			),
			'addPatientArrivalLog' => array(
				'len' => 1
			),
			'updatePatientArrivalLog' => array(
				'len' => 1
			),
			'removePatientArrivalLog' => array(
				'len' => 1
			),
			'getPoolAreaPatients' => array(
				'len' => 1
			),
			'sendPatientToPoolArea' => array(
				'len' => 1
			),
			'getActivePoolAreas' => array(
				'len' => 0
			),
			'getPatientsByPoolAreaAccess' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Form layout Builder Functions
	 */
	'FormLayoutBuilder' => array(
		'methods' => array(
			'getFormDataTable' => array(
				'len' => 1
			),
			'getForms' => array(
				'len' => 0
			),
			'getParentFields' => array(
				'len' => 1
			),
			'getFormFieldsTree' => array(
				'len' => 1
			),
			'createFormField' => array(
				'len' => 1
			),
			'updateFormField' => array(
				'len' => 1
			),
			'removeFormField' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Form layout Builder Functions
	 */
	'CCR' => array(
		'methods' => array(
			'createCCR' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Patient Functions
	 */
	'Patient' => array(
		'methods' => array(
			'createNewPatient' => array(
				'len' => 1
			),
			'patientLiveSearch' => array(
				'len' => 1
			),
			'currPatientSet' => array(
				'len' => 1
			),
			'currPatientUnset' => array(
				'len' => 0
			),
			'getPatientDemographicData' => array(
				'len' => 1
			),
			'updatePatientDemographicData' => array(
				'len' => 1
			),
			'addPatientNoteAndReminder' => array(
				'len' => 1
			),
			'getPatientReminders' => array(
				'len' => 1
			),
			'getPatientNotes' => array(
				'len' => 1
			),
			'getPatientDocuments' => array(
				'len' => 1
			),
			'getPatientDocumentsByEid' => array(
				'len' => 1
			),
			'getMeaningfulUserAlertByPid' => array(
				'len' => 1
			),
			'getPatientInsurancesCardsUrlByPid' => array(
				'len' => 1
			),
			'getPatientDisclosures' => array(
				'len' => 1
			),
			'createPatientDisclosure' => array(
				'len' => 1
			),
			'updatePatientDisclosure' => array(
				'len' => 1
			),
			'addPatientReminders' => array(
				'len' => 1
			),
			'updatePatientReminders' => array(
				'len' => 1
			),
			'addPatientNotes' => array(
				'len' => 1
			),
			'updatePatientNotes' => array(
				'len' => 1
			)
		)
	),
	/**
	 * User Functions
	 */
	'User' => array(
		'methods' => array(
			'getUsers' => array(
				'len' => 1
			),
			'getCurrentUserData' => array(
				'len' => 0
			),
			'addUser' => array(
				'len' => 1
			),
			'updateUser' => array(
				'len' => 1
			),
			'chechPasswordHistory' => array(
				'len' => 1
			),
			'changeMyPassword' => array(
				'len' => 1
			),
			'updateMyAccount' => array(
				'len' => 1
			),
			'verifyUserPass' => array(
				'len' => 1
			),
			'getProviders' => array(
				'len' => 1
			),
			'getCurrentUserBasicData' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Authorization Procedures Functions
	 */
	'authProcedures' => array(
		'methods' => array(
			'login' => array(
				'len' => 1
			),
			'ckAuth' => array(
				'len' => 0
			),
			'unAuth' => array(
				'len' => 0
			),
			'getSites' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Comobo Boxes Data Functions
	 */
	'CombosData' => array(
		'methods' => array(
			'getOptionsByListId' => array(
				'len' => 1
			),
			'getTimeZoneList' => array(
				'len' => 1
			),
			'getActivePharmacies' => array(
				'len' => 0
			),
			'getUsers' => array(
				'len' => 0
			),
			'getLists' => array(
				'len' => 0
			),
			'getFacilities' => array(
				'len' => 0
			),
			'getActiveFacilities' => array(
				'len' => 0
			),
			'getBillingFacilities' => array(
				'len' => 0
			),
			'getRoles' => array(
				'len' => 0
			),
			'getCodeTypes' => array(
				'len' => 0
			),
			'getCalendarCategories' => array(
				'len' => 0
			),
			'getFloorPlanAreas' => array(
				'len' => 0
			),
			'getAuthorizations' => array(
				'len' => 0
			),
			'getSeeAuthorizations' => array(
				'len' => 0
			),
			'getTaxIds' => array(
				'len' => 0
			),
			'getFiledXtypes' => array(
				'len' => 0
			),
			'getPosCodes' => array(
				'len' => 0
			),
			'getAllergieTypes' => array(
				'len' => 0
			),
			'getAllergiesByType' => array(
				'len' => 1
			),
			'getTemplatesTypes' => array(
				'len' => 1
			),
			'getActiveInsurances' => array(
				'len' => 0
			),
			'getThemes' => array(
				'len' => 0
			)
		)),
	/**
	 * Navigation Function
	 */
	'Navigation' => array(
		'methods' => array(
			'getNavigation' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'Roles' => array(
		'methods' => array(
			'getRoleForm' => array(
				'len' => 1
			),
			'getRolesData' => array(
				'len' => 0
			),
			'saveRolesData' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'ACL' => array(
		'methods' => array(
			'getAllUserPermsAccess' => array(
				'len' => 0
			),
			'hasPermission' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'Logs' => array(
		'methods' => array(
			'getLogs' => array(
				'len' => 1
			)
		)
	),
	'Documents' => array(
		'methods' => array(
			'updateDocumentsTitle' => array(
				'len' => 1
			)
		)
	),
	'Test' => array(
		'methods' => array(
			'getRec' => array(
				'len' => 0
			),
			'getRec2' => array(
				'len' => 0
			),
			'addRec' => array(
				'len' => 1
			),
		)
	),
	'CronJob' => array(
		'methods' => array(
			'run' => array(
				'len' => 0
			)
		)
	),
	'i18nRouter' => array(
		'methods' => array(
			'getTranslation' => array(
				'len' => 0
			),
			'getDefaultLanguage' => array(
				'len' => 0
			),
			'getAvailableLanguages' => array(
				'len' => 0
			)
		)
	),
	'SiteSetup' => array(
		'methods' => array(
			'checkDatabaseCredentials' => array(
				'len' => 1
			),
			'checkRequirements' => array(
				'len' => 0
			),
			'setSiteDirBySiteId' => array(
				'len' => 1
			),
			'createDatabaseStructure' => array(
				'len' => 1
			),
			'loadDatabaseData' => array(
				'len' => 1
			),
			'createSiteAdmin' => array(
				'len' => 1
			),
			'createSConfigurationFile' => array(
				'len' => 1
			),
			'loadCode' => array(
				'len' => 1
			)
		)
	),
	'Applications' => array(
		'methods' => array(
			'getApplications' => array(
				'len' => 1
			),
			'addApplication' => array(
				'len' => 1
			),
			'updateApplication' => array(
				'len' => 1
			),
			'deleteApplication' => array(
				'len' => 1
			)
		)
	)
);
