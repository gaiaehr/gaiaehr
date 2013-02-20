 /**
 * Created by JetBrains PhpStorm.
 * User: Omar U. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.MeaningfulUseAlert', {
	extend: 'Ext.data.Model',
	table: {
		name:'meaningfulusealert',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Meaningful Use Alert'
	},
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

