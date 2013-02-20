/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.PreventiveCareMedications', {
	extend: 'Ext.data.Model',
	table: {
		name:'preventivecaremedications',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Preventive Care Medications'
	},	
	fields: [
		{name: 'guideline_id', type: 'int'},
		{name: 'code', type: 'string'},
		{name: 'code_text', type: 'string'}
	]

});