/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsPrescription', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'medication', type: 'string'},
        {name: 'RXCUI', type: 'string'},
        {name: 'dose', type: 'int'},
		{name: 'take_pills', type: 'int'},
		{name: 'type', type: 'string'},
		{name: 'route', type: 'string'},
		{name: 'prescription_often', type: 'string'},
		{name: 'prescription_when', type: 'string'},
		{name: 'dispense', type: 'string'},
		{name: 'refill', type: 'string'},
		{name: 'begin_date'},
		{name: 'end_date'}

	],
	proxy : {
		type: 'direct',
		api : {
		}
	}
});