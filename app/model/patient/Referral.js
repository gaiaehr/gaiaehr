/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-02-13 23:33:25
 */

Ext.define('App.model.patient.Referral', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_referrals',
		comment: 'Patients Referrals'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int',
			index: true,
			comment: 'encounter id'
		},
		{
			name: 'pid',
			type: 'int',
			index: true,
			comment: 'patient ID'
		},
		{
			name: 'create_uid',
			type: 'int',
			comment: 'user ID who created the referral'
		},
		{
			name: 'update_uid',
			type: 'int',
			comment: 'user ID who updated the referral'
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
			name: 'referral_date',
			type: 'date',
			dataType: 'date',
			dateFormat: 'Y-m-d'
		},
		{
			name: 'service_text',
			type: 'string',
			len: 300
		},
		{
			name: 'service_code',
			type: 'string',
			len: 10
		},
		{
			name: 'service_code_type',
			type: 'string',
			comment: 'CPT SNOMED',
			len: 10
		},
		{
			name: 'referal_reason',
			type: 'string',
			len: 1000
		},
		{
			name: 'diagnosis_text',
			type: 'string',
			len: 300
		},
		{
			name: 'diagnosis_code',
			type: 'string',
			len: 10
		},
		{
			name: 'diagnosis_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'is_external_referral',
			type: 'bool'
		},
		{
			name: 'refer_by',
			type: 'string',
			len: 80
		},
		{
			name: 'refer_by_text',
			type: 'string',
			len: 120
		},
		{
			name: 'refer_to',
			type: 'string',
			len: 80
		},
		{
			name: 'refer_to_text',
			type: 'string',
			len: 120
		},
		{
			name: 'risk_level',
			type: 'string',
			len: 20
		},
		{
			name: 'send_record',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Referrals.getPatientReferrals',
			create: 'Referrals.addPatientReferral',
			update: 'Referrals.updatePatientReferral',
			destroy: 'Referrals.deletePatientReferral'
		},
		remoteGroup: false
	}
});
