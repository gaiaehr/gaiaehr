/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.ImmunizationCheck', {
	extend: 'Ext.data.Model',
	table: {
		name:'immunizationcheck',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Immunization Check'
	},
	fields: [
        {name: 'id', type: 'int'},
        {name: 'pid', type: 'int'},
        {name: 'immunization_id', type: 'int'},
		{name: 'immunization_name', type: 'string'},
		{name: 'alert', type: 'bool'}

	],
	proxy : {
		type: 'direct',
		api : {
			read: Medical.getPatientImmunizations
		}
	}
});