// Created dynamically by Matcha::connect
// Create date: 2013-03-12 17:53:28

Ext.define('App.model.patient.Patient', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient',
		comment: 'Patients Demographics'
	},
	fields: [
		{
			name: 'pid',
			type: 'int',
			comment: 'patient ID'
		},
		{
			name: 'create_uid',
			type: 'int',
			comment: 'create user ID'
		},
		{
			name: 'update_uid',
			type: 'int',
			comment: 'update user ID'
		},
		{
			name: 'create_date',
			type: 'date',
			comment: 'create date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'update_date',
			type: 'date',
			comment: 'last update date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'title',
			type: 'string',
			comment: 'Title Mr. Sr.',
			len: 10
		},
		{
			name: 'fname',
			type: 'string',
			comment: 'first name',
			index: true,
			len: 60
		},
		{
			name: 'mname',
			type: 'string',
			comment: 'middle name',
			index: true,
			len: 40
		},
		{
			name: 'lname',
			type: 'string',
			comment: 'last name',
			index: true,
			len: 60
		},
		{
			name: 'sex',
			type: 'string',
			comment: 'sex',
			index: true,
			len: 10
		},
		{
			name: 'DOB',
			type: 'date',
			comment: 'day of birth',
			dateFormat: 'Y-m-d H:i:s',
			index: true,
			defaultValue: '0000-00-00 00:00:00'
		},
		{
			name: 'marital_status',
			type: 'string',
			comment: 'marital status',
			len: 40
		},
		{
			name: 'SS',
			type: 'string',
			index: true,
			comment: 'social security',
			len: 40
		},
		{
			name: 'pubpid',
			type: 'string',
			index: true,
			comment: 'external\/reference id',
			len: 40
		},
		{
			name: 'drivers_license',
			type: 'string',
			index: true,
			comment: 'driver licence #',
			len: 40
		},
		{
			name: 'address',
			type: 'string',
			comment: 'address',
			len: 80
		},
		{
			name: 'city',
			type: 'string',
			comment: 'city',
			len: 40
		},
		{
			name: 'state',
			type: 'string',
			comment: 'state',
			len: 40
		},
		{
			name: 'country',
			type: 'string',
			comment: 'country',
			len: 40
		},
		{
			name: 'zipcode',
			type: 'string',
			comment: 'postal code',
			len: 10
		},
		{
			name: 'home_phone',
			type: 'string',
			index: true,
			comment: 'home phone #',
			len: 15
		},
		{
			name: 'mobile_phone',
			type: 'string',
			index: true,
			comment: 'mobile phone #',
			len: 15
		},
		{
			name: 'work_phone',
			type: 'string',
			index: true,
			comment: 'work phone #',
			len: 15
		},
		{
			name: 'email',
			type: 'string',
			index: true,
			comment: 'email',
			len: 60
		},
		{
			name: 'mothers_name',
			type: 'string',
			comment: 'mother name',
			len: 40
		},
		{
			name: 'guardians_name',
			type: 'string',
			comment: 'guardians name',
			len: 40
		},
		{
			name: 'emer_contact',
			type: 'string',
			comment: 'emergency contact',
			len: 40
		},
		{
			name: 'emer_phone',
			type: 'string',
			comment: 'emergency phone #',
			len: 15
		},
		{
			name: 'provider',
			type: 'string',
			comment: 'default provider',
			len: 40
		},
		{
			name: 'pharmacy',
			type: 'string',
			comment: 'default pharmacy',
			len: 40
		},
		{
			name: 'hipaa_notice',
			type: 'string',
			comment: 'HIPAA notice status',
			len: 40
		},
		{
			name: 'race',
			type: 'string',
			comment: 'race',
			len: 40
		},
		{
			name: 'ethnicity',
			type: 'string',
			comment: 'ethnicity',
			len: 40
		},
		{
			name: 'language',
			type: 'string',
			comment: 'language',
			len: 10
		},
		{
			name: 'allow_leave_msg',
			type: 'bool'
		},
		{
			name: 'allow_voice_msg',
			type: 'bool'
		},
		{
			name: 'allow_mail_msg',
			type: 'bool'
		},
		{
			name: 'allow_sms',
			type: 'bool'
		},
		{
			name: 'allow_email',
			type: 'bool'
		},
		{
			name: 'allow_immunization_registry',
			type: 'bool'
		},
		{
			name: 'allow_immunization_info_sharing',
			type: 'bool'
		},
		{
			name: 'allow_health_info_exchange',
			type: 'bool'
		},
		{
			name: 'allow_patient_web_portal',
			type: 'bool'
		},
		{
			name: 'occupation',
			type: 'string',
			comment: 'patient occupation',
			len: 40
		},
		{
			name: 'employer_name',
			type: 'string',
			comment: 'employer name',
			len: 40
		},
		{
			name: 'employer_address',
			type: 'string',
			comment: 'employer address',
			len: 40
		},
		{
			name: 'employer_city',
			type: 'string',
			comment: 'employer city',
			len: 40
		},
		{
			name: 'employer_state',
			type: 'string',
			comment: 'employer state',
			len: 40
		},
		{
			name: 'employer_country',
			type: 'string',
			comment: 'employer country',
			len: 40
		},
		{
			name: 'employer_postal_code',
			type: 'string',
			comment: 'employer postal code',
			len: 10
		},
		{
			name: 'rating',
			type: 'int',
			comment: 'patient occupation'
		},
		{
			name: 'image',
			type: 'string',
			dataType: 'mediumtext',
			comment: 'patient image base64 string'
		},
		{
			name: 'qrcode',
			type: 'string',
			dataType: 'mediumtext',
			comment: 'patient QRCode base64 string'
		}
	],
	idProperty: 'pid',
	proxy: {
		type: 'direct',
		api: {
			read: 'Patient.getPatients',
			create: 'Patient.savePatient',
			update: 'Patient.savePatient'
		}
	},
	hasMany: [
		{
			model: 'App.model.patient.Insurance',
			name: 'insurance',
			primaryKey: 'pid',
			foreignKey: 'pid'
		}
	]
});
