 /**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientLabsResults', {
	extend   : 'Ext.data.Model',
	fields   : [
		{name: 'id', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'uid', type: 'int'},
		{name: 'auth_uid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'document_id', type: 'int'},
		{name: 'document_url'},
		{name: 'date', type: 'date', dateFormat:'Y-m-d H:s:i'},
		{name: 'data'},
		{name: 'columns'},
		{name: 'parent_id'}
	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: Medical.getPatientLabsResults,
			create: Medical.addPatientLabsResult,
			update: Medical.updatePatientLabsResult,
			destroy: Medical.deletePatientLabsResult
		},
		reader     : {
			type: 'json'
		}
	}
});