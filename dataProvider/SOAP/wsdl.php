<?php
$functions = [];
$complexTypes = [];

/*****************************************************************************
 * To access this WSDL specification run via: /wsdl.php?WSDL
 * Any other access to this WSDL will display as a HTML document
 *
 * 2013 (C) Copyright Lyndon Leverington / DarkerWhite
 *****************************************************************************
 * Set up the web service parameters:
 * $serviceName: Plain Text to display when someone accesses this service
 *               without the ?WSDL parameter in the URL. Whitespaces are
 *               removed from this and this is then used as the ID for the
 *               XML in the WSDL. Please only use A-Z, 0-9 and spaces.
 *
 * Declare all Functions for this Web Service
 * $functions Array Parameters:
 *  funcName - Name of the particular function being served
 *  doc - Documentation to report from Web Service regarding this function
 *  inputParams - An array of arrays where name = name of field and type = data type
 *                  Omit if not required
 *  outputParams - As above, but for responses
 *                  Omit if not required
 *  soapAddress - The php file to send to to process SOAP requests
 *****************************************************************************/

/**
 * Always include the registry.
 */

$serviceName = 'GaiaEHR Access Point';

$Server = '10.23.150.10/GaiaEHR';

$complexTypes['Patient'] = [
	[
		'name' => 'Pid',
		'type' => 'int',
		'minOccurs' => '1',
		'document' => 'GaiaEHR Internal ID'
	],
	[
		'name' => 'RecordNumber',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'GaiaEHR Public ID or Record Number'
	],
	[
		'name' => 'Title',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Sr. Mr. Mss. etc. etc'
	],
	[
		'name' => 'FirstName',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'Patient First Name'
	],
	[
		'name' => 'MiddleName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Patient Middle Name'
	],
	[
		'name' => 'LastName',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'Patient Last Name'
	],
	[
		'name' => 'DateOfBirth',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'On Format YYYY-MM-DD (0000-00-00)'
	],
	[
		'name' => 'Sex',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'F or M'
	],
	[
		'name' => 'MaritalStatus',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Race',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Ethnicity',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Religion',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Language',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'DriverLicence',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'DriverLicenceState',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'DriverLicenceExpirationDate',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalAddressLineOne',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalAddressLineTwo',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalCity',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalState',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalCountry',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PhysicalZipCode',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PostalAddressLineOne',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PostalAddressLineTwo',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PostalCity',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PostalState',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'PostalZipCode',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'HomePhoneNumber',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'MobilePhoneNumber',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'WorkPhoneNumber',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'WorkPhoneExt',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Email',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'ProfileImage',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Base64 String'
	],
	[
		'name' => 'BirthPlace',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'IsBirthMultiple',
		'type' => 'bool',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'BirthOrder',
		'type' => 'int',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'Deceased',
		'type' => 'int',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'DeceaseDate',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'MothersFirstName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'MothersMiddleName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'MothersLastName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'GuardiansFirstName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'GuardiansMiddleName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'GuardiansLastName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'GuardiansPhone',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'EmergencyContactFirstName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'EmergencyContactMiddleName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'EmergencyContactLastName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'EmergencyContactPhone',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => ''
	],
	[
		'name' => 'DeathDate',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'On Format YYYY-MM-DD (0000-00-00)'
	],
	[
		'name' => 'Occupation',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'work title'
	],
	[
		'name' => 'Employer',
		'type' => 'string',
		'minOccurs' => '0'
	],
	[
		'name' => 'WebPortalUsername',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Patient Web Portal Username'
	],
	[
		'name' => 'WebPortalPassword',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Patient Web Portal Password'
	],
	[
		'name' => 'WebPortalAccess',
		'type' => 'bool',
		'minOccurs' => '0',
		'document' => 'Patient Web Portal Allow Access'
	]

];

$complexTypes['Provider'] = [
	[
		'name' => 'NPI',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'National Provider Identifier'
	],
	[
		'name' => 'FirstName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Provider Last Name'
	],
	[
		'name' => 'MiddleName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Provider Last Name'
	],
	[
		'name' => 'LastName',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'Provider Last Name'
	]

];

$complexTypes['Document'] = [
	[
		'name' => 'Base64Document',
		'type' => 'string',
		'minOccurs' => '1'
	],
	[
		'name' => 'Date',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'On Format YYYY-MM-DD (0000-00-00)'
	],
	[
		'name' => 'Title',
		'type' => 'string',
		'minOccurs' => '1'
	],
	[
		'name' => 'Category',
		'type' => 'string',
		'minOccurs' => '1',
		'document' => 'General | Rx | CT | Lab | CR...  etc, etc,'
	],
	[
		'name' => 'Notes',
		'type' => 'string',
		'minOccurs' => '0'
	],
	[
		'name' => 'Encrypted',
		'type' => 'boolean',
		'minOccurs' => '0',
		'document' => 'Set true is you would like Gaia to save it encrypted. ***DO NOT*** send the document already encrypted'
	]
];

$complexTypes['Order'] = [
	[
		'name' => 'OrderId',
		'type' => 'string',
		'minOccurs' => '1'
	],
	[
		'name' => 'DateCollected',
		'type' => 'string',
		'minOccurs' => '0',
		'document' => 'On Format YYYY-MM-DD (0000-00-00)'
	],
	[
		'name' => 'Notes',
		'type' => 'string',
		'minOccurs' => '0'
	]
];

$functions[] = [
	'funcName' => 'GetCCDDocument',
	'doc' => 'This will return the requested patient CCD document',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'Pid',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR Internal ID'
		],
		[
			'name' => 'Site',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Default Value is "default"'
		],
		[
			'name' => 'Facility',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'Default Value is "1"'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Document',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'CCD Document'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'AddPatient',
	'doc' => 'This will add a patient to GaiaEHR',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'Patient',
			'type' => 'Patient',
			'minOccurs' => '1'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Pid',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'System Internal Patient ID'
		],
		[
			'name' => 'RecordNumber',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Created Record Number'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'UpdatePatient',
	'doc' => 'This will add a patient to GaiaEHR',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'Patient',
			'type' => 'Patient',
			'minOccurs' => '1'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Pid',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'System Internal Patient ID'
		],
		[
			'name' => 'RecordNumber',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Created Record Number'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'MergePatient',
	'doc' => 'This method will use the record number as the primary ID then the PID if record number is not provided',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'PrimaryPid',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'SecondaryPid',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'PrimaryRecordNumber',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'SecondaryRecordNumber',
			'type' => 'int',
			'minOccurs' => '0'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Pid',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'Primary Patient ID'
		],
		[
			'name' => 'RecordNumber',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Primary Record Number'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'TransferPatient',
	'doc' => 'This method will use the record number as the primary ID then the PID if record number is not provided',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'PrimaryPid',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'SecondaryPid',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'PrimaryRecordNumber',
			'type' => 'int',
			'minOccurs' => '0'
		],
		[
			'name' => 'SecondaryRecordNumber',
			'type' => 'int',
			'minOccurs' => '0'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Pid',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'Primary Patient ID'
		],
		[
			'name' => 'RecordNumber',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Primary Record Number'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'AppPatientDocument',
	'doc' => 'This will add a document to the patient archive documents',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'Pid',
			'type' => 'string',
			'minOccurs' => '1'
		],
		[
			'name' => 'ProviderNPI',
			'type' => 'int',
			'minOccurs' => '1'
		],
		[
			'name' => 'Document',
			'type' => 'Document',
			'minOccurs' => '1'
		],
		[
			'name' => 'OrderId',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'GaiaEHR Order ID if document is an result of an order'
		],
		[
			'name' => 'Site',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'Default Value is "default"'
		],
		[
			'name' => 'Facility',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => 'Default Value is "1"'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'DocumentId',
			'type' => 'int',
			'minOccurs' => '0',
			'document' => ''
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'PatientPortalAuthorize',
	'doc' => 'This will verify if patient has patient portal access',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'PatientAccount',
			'type' => 'string',
			'minOccurs' => '1'
		],
		[
			'name' => 'DateOfBirth',
			'type' => 'string',
			'minOccurs' => '1'
		],
		[
			'name' => 'Password',
			'type' => 'string',
			'minOccurs' => '1'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Patient',
			'type' => 'Patient',
			'minOccurs' => '0',
			'document' => 'Patient Object'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'newPatientAmendment',
	'doc' => 'This will verify if patient has patient portal access',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'PortalId',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'Portal ID use for EHR reference'
		],
		[
			'name' => 'Pid',
			'type' => 'string',
			'minOccurs' => '1'
		],
		[
			'name' => 'Type',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'P = patient or D = Doctor or O = organization'
		],
		[
			'name' => 'Data',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'JSON Patient object'
		],
		[
			'name' => 'Message',
			'type' => 'string',
			'minOccurs' => '1'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'AmendmentId',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'GaiaEHR Amendment Reference ID'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

$functions[] = [
	'funcName' => 'cancelPatientAmendment',
	'doc' => 'This will verify if patient has patient portal access',
	'inputParams' => [
		[
			'name' => 'SecureKey',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GUID Secure Key provided'
		],
		[
			'name' => 'ServerSite',
			'type' => 'string',
			'minOccurs' => '1',
			'document' => 'GaiaEHR site'
		],
		[
			'name' => 'Pid',
			'type' => 'string',
			'minOccurs' => '1'
		],
		[
			'name' => 'AmendmentId',
			'type' => 'string',
			'minOccurs' => '1'
		]
	],
	'outputParams' => [
		[
			'name' => 'Success',
			'type' => 'boolean',
			'minOccurs' => '1',
			'document' => 'True if request was successfully processed'
		],
		[
			'name' => 'Error',
			'type' => 'string',
			'minOccurs' => '0',
			'document' => 'If success == false an error message will be send back'
		]
	],
	'soapAddress' => "http://$Server/dataProvider/SOAP/Server.php"
];

if(stristr($_SERVER['QUERY_STRING'], 'wsdl'))
{
	// WSDL request - output raw XML
	header('Content-Type: application/soap+xml; charset=utf-8');
	print DisplayXML();
}
else
{
	// Page accessed normally - output documentation
	$cp = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/') + 1); // Current page
	print '<!-- Attention: To access via a SOAP client use ' . $cp . '?WSDL -->';
	print '<html>';
	print '<head><title>' . $serviceName . '</title></head>';
	print <<<STYLES
<style>
ul ul {
	background-color: #f9f9f9;
	padding: 10px 30px;
	margin: 5px;
	border: solid 1px #ccc;

}
ul ul ul {
	background-color: #eff8ff;
}
</style>
STYLES;

	print '<body>';
	print '<h1>' . $serviceName . '</h1>';
	print '<p style="margin-left:20px;">To access via a SOAP client use <code>' . $cp . '?WSDL</code></p>';

	// Document each function
	print '<h2>Available Functions:</h2>';
	print '<div style="margin-left:20px;">';
	for($i = 0; $i < count($functions); $i++)
    {
		print '<h3>Method: ' . $functions[$i]['funcName'] . '</h3>';
		print '<div style="margin-left:20px;">';
		print '<p>';
		print 'Description: ' . $functions[$i]['doc'];
		print '</p>';
		print '<p>';
		print 'URL: ' . $functions[$i]['soapAddress'];
		print '</p>';
		print '<ul>';
		if(array_key_exists("inputParams", $functions[$i])){
			print '<li>Input Parameters:';
			DisplayElementHtml($functions[$i]['inputParams']);
			print '</li>';
		}
		if(array_key_exists("outputParams", $functions[$i])){
			print '<li>Output Parameters:';
			DisplayElementHtml($functions[$i]['outputParams']);
			print '</li>';
		}
		print '</ul>';
		print '</p>';
		print '</div>';
	}
	print '</div>';

	print '<h2>WSDL output:</h2>';
	print '<pre style="margin-left:20px;width:90%;overflow-x:scroll;border:1px solid black;padding:10px;background-color:#D3D3D3;">';
	print DisplayXML(false);
	print '</pre>';
	print '</body></html>';
}

exit;

function DisplayElementHtml($elements) {
	global $complexTypes;
	print ' :<ul>';
	foreach($elements as $element){
		print '<li>' . $element['name'];
		print ' {' . $element['type'] . '} ';
		$element['minOccurs'] !== '0' ? print '<b>(required)</b>' : print '(optional)';
		if(isset($complexTypes[$element['type']])){
			DisplayElementHtml($complexTypes[$element['type']]);
		}
		if(isset($element['document']) && $element['document'] != ''){
			print '<p style="font-size: 14px; border: solid 1px #ccc; margin: 2px; padding: 5px; background-color: #cbe7d0; border-radius: 5px; width: 90%">';
			print $element['document'];
			print '</p>';
		}
		print '</li>';
	}
	print '</ul>';
}

/*****************************************************************************
 * Create WSDL XML
 * @PARAM xmlformat=true - Display output in HTML friendly format if set false
 *****************************************************************************/
function DisplayXML($xmlformat = true) {
	global $functions; // Functions that this web service supports
	global $serviceName; // Web Service ID
	global $complexTypes;

	$i = 0; // For traversing functions array
	$j = 0; // For traversing parameters arrays
	$str = ''; // XML String to output

	// Tab spacings
	$t1 = '    ';
	if(!$xmlformat)
		$t1 = '&nbsp;&nbsp;&nbsp;&nbsp;';
	$t2 = $t1 . $t1;
	$t3 = $t2 . $t1;
	$t4 = $t3 . $t1;
	$t5 = $t4 . $t1;

	$serviceID = str_replace(" ", "", $serviceName);

	// Declare XML format
	$str .= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>' . "\n\n";

	// Declare definitions / namespaces
	$str .= '<wsdl:definitions ' . "\n";
	$str .= $t1 . 'xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" ' . "\n";
	$str .= $t1 . 'xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" ' . "\n";
	$str .= $t1 . 'xmlns:s="http://www.w3.org/2001/XMLSchema" ' . "\n";
	$str .= $t1 . 'targetNamespace="http://www.darkerwhite.com/" ' . "\n";
	$str .= $t1 . 'xmlns:tns="http://www.darkerwhite.com/" ' . "\n";
	$str .= $t1 . 'name="' . $serviceID . '" ' . "\n";
	$str .= '>' . "\n\n";

	// Declare Types / Schema
	$str .= '<wsdl:types>' . "\n";
	$str .= $t1 . '<s:schema elementFormDefault="qualified" targetNamespace="http://www.darkerwhite.com/">' . "\n";

	// Define Request Complex Types
	if(count($complexTypes) > 0){
		foreach($complexTypes as $index => $complexType){
			$str .= $t3 . '<s:complexType name="' . $index . '">' . "\n";
			$str .= $t4 . '<s:sequence>' . "\n";
			foreach($complexType as $element){
				$str .= $t5 . '<s:element minOccurs="' . $element['minOccurs'] . '" maxOccurs="1" ';
				$str .= 'name="' . $element['name'] . '" ';
				$str .= 'type="s:' . $element['type'] . '" />' . "\n";
			}
			$str .= $t4 . '</s:sequence>' . "\n";
			$str .= $t3 . '</s:complexType>' . "\n";
		}
	}

	for($i = 0; $i < count($functions); $i++){
		// Define Request Types
		if(array_key_exists("inputParams", $functions[$i])){
			$str .= $t2 . '<s:element name="' . $functions[$i]['funcName'] . 'Request">' . "\n";
			$str .= $t3 . '<s:complexType>' . "\n";
			$str .= $t4 . '<s:sequence>' . "\n";
			for($j = 0; $j < count($functions[$i]['inputParams']); $j++){
				$str .= $t5 . '<s:element minOccurs="' . $functions[$i]['inputParams'][$j]['minOccurs'] . '" maxOccurs="1" ';
				$str .= 'name="' . $functions[$i]['inputParams'][$j]['name'] . '" ';
				$str .= 'type="' . ((isset($complexTypes[$functions[$i]['inputParams'][$j]['type']])) ? 'tns' : 's');
				$str .= ':' . $functions[$i]['inputParams'][$j]['type'] . '" />' . "\n";
			}
			$str .= $t4 . '</s:sequence>' . "\n";
			$str .= $t3 . '</s:complexType>' . "\n";
			$str .= $t2 . '</s:element>' . "\n";
		}
		// Define Response Types
		if(array_key_exists("outputParams", $functions[$i])){
			$str .= $t2 . '<s:element name="' . $functions[$i]['funcName'] . 'Response">' . "\n";
			$str .= $t3 . '<s:complexType>' . "\n";
			$str .= $t4 . '<s:sequence>' . "\n";
			for($j = 0; $j < count($functions[$i]['outputParams']); $j++){
				$str .= $t5 . '<s:element minOccurs="' . $functions[$i]['outputParams'][$j]['minOccurs'] . '" maxOccurs="1" ';
				$str .= 'name="' . $functions[$i]['outputParams'][$j]['name'] . '" ';
				$str .= 'type="' . ((isset($complexTypes[$functions[$i]['outputParams'][$j]['type']])) ? 'tns' : 's');
				$str .= ':' . $functions[$i]['outputParams'][$j]['type'] . '" />' . "\n";
			}
			$str .= $t4 . '</s:sequence>' . "\n";
			$str .= $t3 . '</s:complexType>' . "\n";
			$str .= $t2 . '</s:element>' . "\n";
		}
	}
	$str .= $t1 . '</s:schema>' . "\n";
	$str .= '</wsdl:types>' . "\n\n";

	// Declare Messages
	for($i = 0; $i < count($functions); $i++){
		// Define Request Messages
		if(array_key_exists("inputParams", $functions[$i])){
			$str .= '<wsdl:message name="' . $functions[$i]['funcName'] . 'Request">' . "\n";
			$str .= $t1 . '<wsdl:part name="parameters" element="tns:' . $functions[$i]['funcName'] . 'Request" />' . "\n";
			$str .= '</wsdl:message>' . "\n";
		}
		// Define Response Messages
		if(array_key_exists("outputParams", $functions[$i])){
			$str .= '<wsdl:message name="' . $functions[$i]['funcName'] . 'Response">' . "\n";
			$str .= $t1 . '<wsdl:part name="parameters" element="tns:' . $functions[$i]['funcName'] . 'Response" />' . "\n";
			$str .= '</wsdl:message>' . "\n\n";
		}
	}

	// Declare Port Types
	for($i = 0; $i < count($functions); $i++){
		$str .= '<wsdl:portType name="' . $functions[$i]['funcName'] . 'PortType">' . "\n";
		$str .= $t1 . '<wsdl:operation name="' . $functions[$i]['funcName'] . '">' . "\n";
		if(array_key_exists("inputParams", $functions[$i]))
			$str .= $t2 . '<wsdl:input message="tns:' . $functions[$i]['funcName'] . 'Request" />' . "\n";
		if(array_key_exists("outputParams", $functions[$i]))
			$str .= $t2 . '<wsdl:output message="tns:' . $functions[$i]['funcName'] . 'Response" />' . "\n";
		$str .= $t1 . '</wsdl:operation>' . "\n";
		$str .= '</wsdl:portType>' . "\n\n";
	}

	// Declare Bindings
	for($i = 0; $i < count($functions); $i++){
		$str .= '<wsdl:binding name="' . $functions[$i]['funcName'] . 'Binding" type="tns:' . $functions[$i]['funcName'] . 'PortType">' . "\n";
		$str .= $t1 . '<soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http" />' . "\n";
		$str .= $t1 . '<wsdl:operation name="' . $functions[$i]['funcName'] . '">' . "\n";
		$str .= $t2 . '<soap:operation soapAction="' . $functions[$i]['soapAddress'] . '#' . $functions[$i]['funcName'] . '" style="document" />' . "\n";
		if(array_key_exists("inputParams", $functions[$i]))
			$str .= $t2 . '<wsdl:input><soap:body use="literal" /></wsdl:input>' . "\n";
		if(array_key_exists("outputParams", $functions[$i]))
			$str .= $t2 . '<wsdl:output><soap:body use="literal" /></wsdl:output>' . "\n";
		$str .= $t2 . '<wsdl:documentation>' . $functions[$i]['doc'] . '</wsdl:documentation>' . "\n";
		$str .= $t1 . '</wsdl:operation>' . "\n";
		$str .= '</wsdl:binding>' . "\n\n";
	}

	// Declare Service
	$str .= '<wsdl:service name="' . $serviceID . '">' . "\n";
	for($i = 0; $i < count($functions); $i++){
		$str .= $t1 . '<wsdl:port name="' . $functions[$i]['funcName'] . 'Port" binding="tns:' . $functions[$i]['funcName'] . 'Binding">' . "\n";
		$str .= $t2 . '<soap:address location="' . $functions[$i]['soapAddress'] . '" />' . "\n";
		$str .= $t1 . '</wsdl:port>' . "\n";
	}
	$str .= '</wsdl:service>' . "\n\n";

	// End Document
	$str .= '</wsdl:definitions>' . "\n";

	if(!$xmlformat)
		$str = str_replace("<", "&lt;", $str);
	if(!$xmlformat)
		$str = str_replace(">", "&gt;", $str);
	if(!$xmlformat)
		$str = str_replace("\n", "<br />", $str);
	return $str;
}
