 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patientfile.MeaningfulUseAlert', {
	extend: 'Ext.data.Model',
	fields: [

		{name: 'lenguage', type: 'bool'},
		{name: 'race', type: 'bool'},
		{name: 'ethnicity', type: 'bool'},
		{name: 'fname', type: 'bool'},
		{name: 'lname', type: 'bool'},
		{name: 'sex', type: 'bool'},
		{name: 'DOB', type: 'bool'},
		{name: 'pid', type: 'int'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Patient.getMeaningfulUserAlertByPid
		}
	}
});

