/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patientfile.PatientsLabsOrders', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'laboratories', type: 'string'}

	],
	proxy : {
		type: 'direct',
		api : {
		}
	}
});