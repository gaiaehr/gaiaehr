/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.PreventiveCareLabs', {
	extend: 'Ext.data.Model',
	table: {
		name:'preventivecarelabs',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Preventive Care Labs'
	},	
	fields: [
		{name: 'id', type: 'int'},
		{name: 'value_name', type: 'string'},
		{name: 'greater_than', type: 'string'},
		{name: 'less_than', type: 'string'},
		{name: 'equal_to', type: 'string'},
		{name: 'code', type: 'string'},
		{name: 'preventive_care_id', type: 'string'}
	]

});