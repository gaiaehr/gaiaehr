// Created dynamically by Matcha::connect
// Create date: 2013-07-28 18:48:17

Ext.define('App.model.patient.Vitals',
{
    extend: 'Ext.data.Model',
    table: {
        name: 'encounter_vitals',
        comment: 'Vitals'
    },
    fields: [
        {
            name: 'id',
            type: 'int',
            comment: 'Vital ID'
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
            name: 'uid',
            type: 'int'
        },
        {
            name: 'auth_uid',
            type: 'int'
        },
        {
            name: 'date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        },
        {
            name: 'weight_lbs',
            type: 'string',
            useNull: true
        },
        {
            name: 'weight_kg',
            type: 'float',
            useNull: true
        },
        {
            name: 'height_in',
            type: 'float',
            useNull: true
        },
        {
            name: 'height_cm',
            type: 'float',
            useNull: true
        },
        {
            name: 'bp_systolic',
            type: 'float',
            useNull: true
        },
        {
            name: 'bp_diastolic',
            type: 'float',
            useNull: true
        },
        {
            name: 'pulse',
            type: 'int',
            useNull: true
        },
        {
            name: 'respiration',
            type: 'int',
            useNull: true
        },
        {
            name: 'temp_f',
            type: 'float',
            useNull: true
        },
        {
            name: 'temp_c',
            type: 'float',
            useNull: true
        },
        {
            name: 'temp_location',
            type: 'string'
        },
        {
            name: 'oxygen_saturation',
            type: 'float',
            useNull: true
        },
        {
            name: 'head_circumference_in',
            type: 'float',
            useNull: true
        },
        {
            name: 'head_circumference_cm',
            type: 'float',
            useNull: true
        },
        {
            name: 'waist_circumference_in',
            type: 'float',
            useNull: true
        },
        {
            name: 'waist_circumference_cm',
            type: 'float',
            useNull: true
        },
        {
            name: 'bmi',
            type: 'int',
            useNull: true
        },
        {
            name: 'bmi_status',
            type: 'string',
            useNull: true
        },
        {
            name: 'other_notes',
            type: 'string'
        },
        {
            name: 'administer_by',
            type: 'string'
        },
        {
            name: 'authorized_by',
            type: 'string'
        },
        {
            name: 'bp_systolic_normal',
            type: 'int',
            defaultValue: 120
        },
        {
            name: 'bp_diastolic_normal',
            type: 'int',
            defaultValue: 80
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: Encounter.getVitals,
            create: Encounter.addVitals,
            update: Encounter.updateVitals
        },
        reader: {
            type: 'json'
        }
    },
    belongsTo: {
        model: 'App.model.patient.Encounter',
        foreignKey: 'eid'
    }
});
