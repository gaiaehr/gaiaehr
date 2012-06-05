/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patientfile.PatientsDoctorsNote', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'medication', type: 'string'},
        {name: 'medication_id', type: 'string'},
        {name: 'dose', type: 'int'},
		{name: 'dose_mg', type: 'string'},
		{name: 'take_pills', type: 'int'},
		{name: 'type', type: 'string'},
		{name: 'by', type: 'string'},
		{name: 'prescription_often', type: 'string'},
		{name: 'prescription_when', type: 'string'},
		{name: 'dispense', type: 'string'},
		{name: 'refill', type: 'string'}

	],
	proxy : {
		type: 'direct',
		api : {
		}
	}
});