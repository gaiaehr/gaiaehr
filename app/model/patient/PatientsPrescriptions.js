/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsPrescriptions', {
	extend: 'Ext.data.Model',
	fields: [

		{name: 'id'},
		{name: 'pid'},
		{name: 'uid'},
		{name: 'created_date'},
		{name: 'note'}

	],
	proxy : {
		type: 'direct',
		api : {
            read  : Prescriptions.getPrescriptions,
            create: Prescriptions.addNewPrescriptions,
            update: Prescriptions.updatePrescriptions
		}
	}
});