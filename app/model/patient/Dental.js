/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Dental', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'c'},
		{name: 'cdt_code', type: 'string'},
		{name: 'description', type: 'string'},
		{name: 'begin_date', type: 'date', dateFormat: 'c'},
		{name: 'end_date', type: 'date', dateFormat: 'c'},
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