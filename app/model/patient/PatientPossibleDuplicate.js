/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-04-21 16:48:39
 */

Ext.define('App.model.patient.PatientPossibleDuplicate', {
	extend: 'App.model.patient.Patient',
	proxy: {
		type: 'direct',
		api: {
			read: 'Patient.getPossibleDuplicatesByDemographic'
		},
		reader:{
			root: 'data'
		}
	}
});
