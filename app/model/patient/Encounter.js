/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2015-01-20 21:16:17
 */

Ext.define('App.model.patient.Encounter', {
	extend: 'Ext.data.Model',
	table: {
		name: 'encounters',
		comment: 'Encounter Data'
	},
	fields: [
		{
			name: 'eid',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'rid',
			type: 'string',
			len: 80,
			comment: 'reference ID'
		},
		{
			name: 'open_uid',
			type: 'int',
			index: true
		},
		{
			name: 'provider_uid',
			type: 'int',
			index: true
		},
		{
			name: 'supervisor_uid',
			type: 'int',
			index: true
		},
		{
			name: 'requires_supervisor',
			type: 'bool',
			index: true,
			defaultValue: false
		},
		{
			name: 'technician_uid',
			type: 'int',
			useNull: true,
			index: true
		},
		{
			name: 'specialty_id',
			type: 'int',
			useNull: true,
			index: true
		},
		{
			name: 'service_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		},
		{
			name: 'close_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'onset_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		},
		{
			name: 'priority',
			type: 'string',
			len: 60
		},
		{
			name: 'brief_description',
			type: 'string',
			len: 600,
			comment: 'chief complaint'
		},
		{
			name: 'visit_category',
			type: 'string',
			len: 80
		},
		{
			name: 'facility',
			type: 'int',
			len: 1,
			index: true
		},
		{
			name: 'billing_stage',
			type: 'int',
			len: 1,
			index: true
		},
		{
			name: 'followup_time',
			type: 'string',
			len: 25
		},
		{
			name: 'followup_facility',
			type: 'string',
			len: 80
		},
		{
			name: 'review_immunizations',
			type: 'bool'
		},
		{
			name: 'review_allergies',
			type: 'bool'
		},
		{
			name: 'review_active_problems',
			type: 'bool'
		},
		{
			name: 'review_alcohol',
			type: 'string',
			len: 40
		},
		{
			name: 'review_smoke',
			type: 'bool'
		},
		{
			name: 'review_pregnant',
			type: 'string',
			len: 40
		},
		{
			name: 'review_surgery',
			type: 'bool'
		},
		{
			name: 'review_dental',
			type: 'bool'
		},
		{
			name: 'review_medications',
			type: 'bool'
		},
		{
			name: 'message',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'patient_class',
			type: 'string'
		},
		{
			name: 'referring_physician',
			type: 'int'
		}
	],
	idProperty: 'eid',
	proxy: {
		type: 'direct',
		api: {
			read: 'Encounter.getEncounters',
			create: 'Encounter.createEncounter',
			update: 'Encounter.updateEncounter'
		},
		reader: {
			root: 'encounter'
		}
	},
	hasMany: [
		{
			model: 'App.model.patient.Vitals',
			name: 'vitals',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.ReviewOfSystems',
			name: 'reviewofsystems',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.FamilyHistory',
			name: 'familyhistory',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.SOAP',
			name: 'soap',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.HCFAOptions',
			name: 'hcfaoptions',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.EncounterService',
			name: 'services',
			primaryKey: 'eid',
			foreignKey: 'eid'
		},
		{
			model: 'App.model.patient.AppointmentRequest',
			name: 'appointmentrequests',
			primaryKey: 'eid',
			foreignKey: 'eid'
		}
	],
	isClose: function(){
		return typeof this.data.close_date != 'undefined' && this.data.close_date != null;
	},

	isSigned: function(){
		return typeof this.data.provider_uid != 'undefined' && this.data.provider_uid != null && this.data.provider_uid != 0;
	},

	isCoSigned: function(){
		return typeof this.data.supervisor_uid != 'undefined' && this.data.supervisor_uid != null && this.data.supervisor_uid != 0;
	}
});
