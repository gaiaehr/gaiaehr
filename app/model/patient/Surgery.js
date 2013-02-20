/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Surgery', {
	extend: 'Ext.data.Model',
	table: {
		name:'surgery',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Surgery'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'surgery', type: 'string'},
		{name: 'surgery_id', type: 'string'},
		{name: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'referred_by', type: 'string'},
		{name: 'outcome', type: 'string'},
		{name: 'notes', type: 'string'},
        {name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientSurgery,
			create: Medical.addPatientSurgery,
			update: Medical.updatePatientSurgery
		}
	}
});