/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-08-31 12:10:50
 */

Ext.define('App.model.patient.Insurance',{
    extend: 'Ext.data.Model',
    table: {
        name: 'patient_insurances',
        comment: 'Patient Insurances'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'pid',
            type: 'int',
	        index: true
        },
        {
            name: 'insurance_id',
            type: 'int'
        },
        {
            name: 'insurance_type',
            type: 'string',
            comment: 'P = primary S = supplemental C =complementary D = Disable',
            len: 1
        },
        {
            name: 'effective_date',
            type: 'date',
            dataType: 'date',
            dateFormat: 'Y-m-d'
        },
        {
            name: 'expiration_date',
            type: 'date',
            dataType: 'date',
            dateFormat: 'Y-m-d'
        },
        {
            name: 'policy_number',
            type: 'string',
            len: 40
        },
        {
            name: 'group_number',
            type: 'string',
            comment: 'group number',
            len: 40
        },
        {
            name: 'covers',
            type: 'array',
	        dataType: 'VARCHAR',
	        index: true,
	        len: 300
        },
        {
            name: 'subscriber_title',
            type: 'string',
            len: 10
        },
        {
            name: 'subscriber_given_name',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_middle_name',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_surname',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_relationship',
            type: 'string',
            len: 40
        },
        {
            name: 'subscriber_dob',
            type: 'date',
            dataType: 'date',
            dateFormat: 'Y-m-d'
        },
        {
            name: 'subscriber_sex',
            type: 'string',
            len: 1
        },
        {
            name: 'subscriber_ss',
            type: 'string',
            len: 10
        },
        {
            name: 'subscriber_street',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_city',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_state',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_country',
            type: 'string',
            len: 80
        },
        {
            name: 'subscriber_postal_code',
            type: 'string',
            len: 20
        },
        {
            name: 'subscriber_phone',
            type: 'string',
            len: 20
        },
        {
            name: 'subscriber_employer',
            type: 'string',
            len: 80
        },
        {
            name: 'display_order',
            type: 'int',
            len: 3
        },
	    {
		    name: 'notes',
		    type: 'string'
	    },
	    {
		    name: 'copay',
		    type: 'string',
		    comment: 'default copay',
		    len: 10
	    },
	    {
		    name: 'image',
		    type: 'string',
		    dataType: 'mediumtext',
		    comment: 'insurance image base64 string'
	    },
        {
            name: 'create_uid',
            type: 'int'
        },
        {
            name: 'update_uid',
            type: 'int'
        },
        {
            name: 'create_date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        },
        {
            name: 'update_date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'Insurance.getInsurances',
            create: 'Insurance.addInsurance',
            update: 'Insurance.updateInsurance'
        }
    },
    associations: [
        {
            type: 'belongsTo',
            model: 'App.model.patient.Patient',
            associationKey: 'pid',
            foreignKey: 'pid'
        }
    ]
});