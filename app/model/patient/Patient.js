/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2015-10-25 14:22:10
 */

Ext.define('App.model.patient.Patient',{
    extend: 'Ext.data.Model',
    requires: [
        'App.model.patient.Insurance',
        'App.model.patient.Allergies',
        'App.model.patient.Medications',
        'App.model.patient.PatientActiveProblem'
    ],
    table: {
        name: 'patient'
    },
    fields: [
        {
            name: 'pid',
            type: 'int',
            comment: 'patient ID'
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
            name: 'name',
            type: 'string',
            store: false
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
            name: 'DOBFormatted',
            type: 'string',
            persist: false
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
            comment: 'external reference id',
            len: 40
        },
        {
            name: 'pubaccount',
            type: 'string',
            index: true,
            comment: 'external reference account',
            len: 40
        },
        {
            name: 'record_number',
            type: 'string',
            persist: false
        },
        {
            name: 'drivers_license',
            type: 'string',
            index: true,
            comment: 'driver licence #',
            len: 40
        },
        {
            name: 'drivers_license_state',
            type: 'string',
            len: 40
        },
        {
            name: 'drivers_license_exp',
            type: 'date',
            dataType: 'date',
            dateFormat: 'Y-m-d'
        },
        {
            name: 'fulladdress',
            type: 'string',
            persist: false,
            convert: false
        },
        {
            name: 'phones',
            type: 'string',
            store: false
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
            comment: 'patient stars rating'
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
        },
        {
            name: 'birth_place',
            type: 'string',
            len: 150
        },
        {
            name: 'birth_multiple',
            type: 'bool'
        },
        {
            name: 'birth_order',
            type: 'int',
            defaultValue: 1,
            len: 2
        },
        {
            name: 'is_veteran',
            type: 'string',
            len: 1
        },
        {
            name: 'deceased',
            type: 'string',
            len: 1
        },
        {
            name: 'death_date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        },
        {
            name: 'alias',
            type: 'string',
            len: 80
        },
        {
            name: 'citizenship',
            type: 'string',
            len: 80
        },
        {
            name: 'primary_facility',
            type: 'int'
        },
        {
            name: 'primary_provider',
            type: 'int'
        },
        {
            name: 'administrative_status',
            type: 'string',
            comment: 'active | inactive | merged',
            len: 15
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
            name: 'portal_password',
            type: 'string',
            dataType: 'blob',
            encrypt: true
        },
        {
            name: 'portal_username',
            type: 'string',
	        len: 40
        },
	    {
		    name: 'phone_publicity',
		    type: 'string',
		    len: 10
	    },
        {
            name: 'phone_home',
            type: 'string',
	        len: 25
        },
        {
            name: 'phone_mobile',
            type: 'string',
	        len: 25
        },
        {
            name: 'phone_work',
            type: 'string',
	        len: 25
        },
        {
            name: 'phone_work_ext',
            type: 'string',
	        len: 25
        },
	    {
		    name: 'phone_fax',
		    type: 'string',
		    len: 25
	    },
        {
            name: 'email',
            type: 'string',
	        len: 80
        },
        {
            name: 'postal_address',
            type: 'string',
	        len: 40
        },
        {
            name: 'postal_address_cont',
            type: 'string',
	        len: 40
        },
        {
            name: 'postal_city',
            type: 'string',
	        len: 35
        },
        {
            name: 'postal_state',
            type: 'string',
	        len: 35
        },
        {
            name: 'postal_zip',
            type: 'string',
	        len: 35
        },
        {
            name: 'physical_address',
            type: 'string',
	        len: 40
        },
        {
            name: 'physical_address_cont',
            type: 'string',
	        len: 40
        },
        {
            name: 'physical_city',
            type: 'string',
	        len: 35
        },
        {
            name: 'physical_state',
            type: 'string',
	        len: 35
        },
        {
            name: 'physical_zip',
            type: 'string',
	        len: 35
        },
	    {
		    name: 'guardians_relation',
		    type: 'string',
		    len: 20
	    },
        {
            name: 'guardians_fname',
            type: 'string',
	        len: 80
        },
        {
            name: 'guardians_mname',
            type: 'string',
	        len: 80
        },
        {
            name: 'guardians_lname',
            type: 'string',
	        len: 80
        },
        {
            name: 'guardians_phone',
            type: 'string',
	        len: 20
        },
        {
            name: 'guardians_phone_type',
            type: 'string',
	        len: 10
        },
        {
            name: 'emergency_contact_relation',
            type: 'string',
	        len: 20
        },
        {
            name: 'emergency_contact_fname',
            type: 'string',
	        len: 80
        },
        {
            name: 'emergency_contact_mname',
            type: 'string',
	        len: 80
        },
        {
            name: 'emergency_contact_lname',
            type: 'string',
	        len: 80
        },
        {
            name: 'emergency_contact_phone',
            type: 'string',
	        len: 20
        },
        {
            name: 'emergency_contact_phone_type',
            type: 'string',
            len: 10
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
        },
        {
            model: 'App.model.patient.Allergies',
            name: 'allergies',
            primaryKey: 'pid',
            foreignKey: 'pid'
        },
        {
            model: 'App.model.patient.Medications',
            name: 'medications',
            primaryKey: 'pid',
            foreignKey: 'pid'
        },
        {
            model: 'App.model.patient.PatientActiveProblem',
            name: 'activeproblems',
            primaryKey: 'pid',
            foreignKey: 'pid'
        },
        {
            model: 'App.model.patient.PatientContacts',
            name: 'contacts',
            primaryKey: 'pid',
            foreignKey: 'pid'
        }
    ],
    indexes: [
        {
            name: 'live_search_index',
            choice: 'INDEX',
            type: 'BTREE',
            columns: [
                'pid',
                'pubpid',
                'fname',
                'mname',
                'lname',
                'SS'
            ]
        }
    ]
});
