/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-05-15 21:20:43
 */

Ext.define('App.model.patient.FamilyHistory', {
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
			name: 'condition',
			type: 'string',
			len: 60
		},
		{
			name: 'condition_code',
			type: 'string',
			len: 60
		},
		{
			name: 'condition_code_type',
			type: 'string',
			len: 60
		},
		{
			name: 'relation',
			type: 'string',
			len: 60
		},
		{
			name: 'relation_code',
			type: 'string',
			len: 60
		},
		{
			name: 'relation_code_type',
			type: 'string',
			len: 60
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
