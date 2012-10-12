 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Notes', {
	extend: 'Ext.data.Model',
	fields: [

        {name: 'id', type: 'int'},
        {name: 'eid', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'uid', type: 'int'},
        {name: 'date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'body', type: 'string'},
        {name: 'type', type: 'string'},
        {name: 'user_name', type: 'string'}
	],
	proxy : {
		type: 'direct',
		api : {
			read    : Patient.getPatientNotes,
            create  : Patient.addPatientNotes,
            update  : Patient.updatePatientNotes
		}
	}
});

