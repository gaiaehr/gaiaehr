 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Disclosures', {
	extend: 'Ext.data.Model',
	fields: [

        {name: 'id', type: 'int'},
        {name: 'eid', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'uid', type: 'int'},
        {name: 'date', type: 'date', dateFormat:'Y-m-d H:i:s'},
        {name: 'type', type: 'string'},
        {name: 'recipient', type: 'string'},
        {name: 'description', type: 'string'},
        {name: 'active', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getPatientDisclosures,
			create  : Patient.createPatientDisclosure,
			update  : Patient.updatePatientDisclosure
		}
	}
});

