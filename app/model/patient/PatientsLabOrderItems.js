/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsLabOrderItems', {
	extend: 'Ext.data.Model',
	fields: [
        { name: 'id', type: 'int' },
        { name: 'loinc', type: 'string' },
        { name: 'title', type: 'string' }
	]
//	proxy : {
//		type: 'direct',
//		api : {
//			read:Orders.getPatientOrderItems,
//			create:Orders.addPatientOrderItems,
//			destroy:Orders.removePatientOrderItems
//		}
//	}
});