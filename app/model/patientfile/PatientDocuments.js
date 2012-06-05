 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patientfile.PatientDocuments', {
	extend: 'Ext.data.Model',
	fields: [

        {name: 'id', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'uid', type: 'int'},
        {name: 'docType	', type: 'string'},
        {name: 'name', type: 'string'},
        {name: 'date', type: 'date'},
        {name: 'url', type: 'string'},
        {name: 'date', type: 'string'},
        {name: 'note', type: 'string'},
        {name: 'user_name', type: 'string'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getPatientDocuments
		}
	}
});

