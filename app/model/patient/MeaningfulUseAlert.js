 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.MeaningfulUseAlert', {
	extend: 'Ext.data.Model',
	fields: [

		{name: 'name', type: 'string'},
		{name: 'val', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getMeaningfulUserAlertByPid
		}
	}
});

