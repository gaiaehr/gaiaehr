 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patientfile.LaboratoryTypes', {
	extend: 'Ext.data.Model',
	fields: [

		{name: 'id', type: 'int'},
		{name: 'label', type: 'string'},
		{name: 'fields' }

	],
	proxy : {
		type: 'direct',
		api : {
			read  : Services.getActiveLaboratoryTypes
		}
	}
});

