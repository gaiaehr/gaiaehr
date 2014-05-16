/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-05-15 21:20:43
 */

Ext.define('App.model.patient.FamilyHistory',{
    extend: 'Ext.data.Model',
    table: {
        name: 'patient_family_history'
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
            name: 'eid',
            type: 'int',
	        index: true
        },
        {
            name: 'auth_uid',
            type: 'int'
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
        },
        {
            name: 'tuberculosis',
            type: 'string',
	        len: 60
        },
        {
            name: 'emphysema',
            type: 'string',
	        len: 60
        },
        {
            name: 'asthma',
            type: 'string',
	        len: 60
        },
        {
            name: 'hypertension',
            type: 'string',
	        len: 60
        },
        {
            name: 'heart_murmur',
            type: 'string',
	        len: 60
        },
        {
            name: 'rheumatic_fever',
            type: 'string',
	        len: 60
        },
        {
            name: 'heart_attak',
            type: 'string',
	        len: 60
        },
        {
            name: 'angina',
            type: 'string',
	        len: 60
        },
        {
            name: 'stroke',
            type: 'string',
	        len: 60
        },
        {
            name: 'high_cholesterol',
            type: 'string',
	        len: 60
        },
        {
            name: 'vascular_graft',
            type: 'string',
	        len: 60
        },
        {
            name: 'mitral_valve_prolapse',
            type: 'string',
	        len: 60
        },
        {
            name: 'hepatitis_a',
            type: 'string',
	        len: 60
        },
        {
            name: 'hepatitis_b',
            type: 'string',
	        len: 60
        },
        {
            name: 'hepatitis_c',
            type: 'string',
	        len: 60
        },
        {
            name: 'kidney',
            type: 'string',
	        len: 60
        },
        {
            name: 'std',
            type: 'string',
	        len: 60
        },
        {
            name: 'ulcers',
            type: 'string',
	        len: 60
        },
        {
            name: 'diabetes',
            type: 'string',
	        len: 60
        },
        {
            name: 'thyroid',
            type: 'string',
	        len: 60
        },
        {
            name: 'hemophilia',
            type: 'string',
	        len: 60
        },
        {
            name: 'anemia',
            type: 'string',
	        len: 60
        },
        {
            name: 'cancer',
            type: 'string',
	        len: 60
        },
        {
            name: 'hiv_aids',
            type: 'string',
	        len: 60
        },
        {
            name: 'osteoarthritis',
            type: 'string',
	        len: 60
        },
        {
            name: 'rheumatoid_arthritis',
            type: 'string',
	        len: 60
        },
        {
            name: 'seizures',
            type: 'string',
	        len: 60
        },
        {
            name: 'dementia',
            type: 'string',
	        len: 60
        },
        {
            name: 'anxiety',
            type: 'string',
	        len: 60
        },
        {
            name: 'depression',
            type: 'string',
	        len: 60
        },
        {
            name: 'eating_disorder',
            type: 'string',
	        len: 60
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'FamilyHistory.getFamilyHistory',
            create: 'FamilyHistory.addFamilyHistory',
            update: 'FamilyHistory.updateFamilyHistory'
        }
    },
    belongsTo: {
        model: 'App.model.patient.Encounter',
        foreignKey: 'eid'
    }
});
