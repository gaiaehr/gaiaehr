 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.PatientDocuments', {
	extend: 'Ext.data.Model',
	table: {
		name:'patientdocuments',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patient Documents Storage'
	},
	fields: [

        {name: 'id', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'eid', type: 'int'},
        {name: 'uid', type: 'int'},
        {name: 'docType', type: 'string'},
        {name: 'name', type: 'string'},
        {name: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
        {name: 'url', type: 'string'},
        {name: 'note', type: 'string'},
        {name: 'title', type: 'string'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getPatientDocuments,
            update: Documents.updateDocumentsTitle
		}
	}
});

