/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.PreventiveCare', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'preventive_care_id', type: 'int'},
		{name: 'uid', type: 'int'},
		{name: 'description', type: 'string'},
		{name: 'age_start', type: 'string'},
		{name: 'age_end', type: 'string'},
		{name: 'sex', type: 'string'},
		{name: 'pregnant', type: 'bool'},
		{name: 'frequency', type: 'string'},
		{name: 'category_id', type: 'string'},
		{name: 'code', type: 'string'},
		{name: 'coding_system', type: 'string'},
		{name: 'dismiss', type: 'bool'},
		{name: 'frequency_type', type: 'string'},
		{name: 'reason', type: 'string'},
		{name: 'times_to_perform', type: 'string'},
		{name: 'doc_url1', type: 'string'},
		{name: 'doc_url2', type: 'string'},
		{name: 'doc_url3', type: 'string'},
		{name: 'active', type:'bool'}
	]

});