// Created dynamically by Matcha::connect
// Create date: 2013-07-28 18:48:17

Ext.define('App.model.patient.Vitals', {
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
			useNull: true,
			len: 10
		},
		{
			name: 'weight_kg',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'height_in',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'height_cm',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'bp_systolic',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'bp_diastolic',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'pulse',
			type: 'int',
			useNull: true,
			len: 10,
			convert: function(v){
				return v > 0 ? v : null;
			}
		},
		{
			name: 'respiration',
			type: 'int',
			useNull: true,
			len: 10,
			convert: function(v){
				return v > 0 ? v : null;
			}
		},
		{
			name: 'temp_f',
			type: 'float',
			useNull: true,
			len: 10,
			convert: function(v){
				return v > 0 ? v : null;
			}
		},
		{
			name: 'temp_c',
			type: 'float',
			useNull: true,
			len: 10,
			convert: function(v){
				return v > 0 ? v : null;
			}
		},
		{
			name: 'temp_location',
			type: 'string',
			len: 40
		},
		{
			name: 'oxygen_saturation',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'head_circumference_in',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'head_circumference_cm',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'waist_circumference_in',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'waist_circumference_cm',
			type: 'float',
			useNull: true,
			len: 10
		},
		{
			name: 'bmi',
			type: 'float',
			useNull: true,
			len: 10,
			convert: function(v){
				return v > 0 ? v : null;
			}
		},
		{
			name: 'bmi_status',
			type: 'string',
			useNull: true,
			len: 10
		},
		{
			name: 'other_notes',
			type: 'string',
			len: 600
		},
		{
			name: 'bp_systolic_normal',
			type: 'int',
			defaultValue: 120,
			store: false
		},
		{
			name: 'bp_diastolic_normal',
			type: 'int',
			defaultValue: 80,
			store: false
		},
		{
			name: 'group_date',
			type: 'string',
			store: false,
			convert: function(v, record){
				return Ext.Date.format(record.data.date, 'Y-m-d');
			}
		},
		{
			name: 'administer_by',
			type: 'string',
			store: false
		},
		{
			name: 'authorized_by',
			type: 'string',
			store: false
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Vitals.getVitals',
			create: 'Vitals.addVitals',
			update: 'Vitals.updateVitals'
		}
	},
	belongsTo: {
		model: 'App.model.patient.Encounter',
		foreignKey: 'eid'
	}
});
