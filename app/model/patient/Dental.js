/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Dental', {
	extend: 'Ext.data.Model',
	table: {
		name:'dental',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Dental data'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'cdt_code', type: 'string'},
		{name: 'description', type: 'string'},
		{name: 'begin_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'end_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'ocurrence', type: 'string'},
		{name: 'referred_by', type: 'string'},
		{name: 'outcome', type: 'string'},
		{name: 'destination', type: 'string'},
        {name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientDental,
			create: Medical.addPatientDental,
			update: Medical.updatePatientDental
		}
	}
});