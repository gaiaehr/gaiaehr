/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.LabObservations', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id' },
        {name: 'code_text_short' },
		{name: 'parent_id' },
		{name: 'parent_loinc' },
		{name: 'parent_name' },
		{name: 'sequence' },
		{name: 'loinc_number' },
		{name: 'loinc_name' },
		{name: 'default_unit' },
		{name: 'range_start' },
		{name: 'range_end' },
		{name: 'required_in_panel' },
		{name: 'description' },
		{name: 'active', type:'bool' }
	]
});