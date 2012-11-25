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

		{name: 'date'},
		{name: 'note'}

	],
	proxy : {
		type: 'direct',
		api : {
		}
	}
});