/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.Services', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'code_text', type: 'string'},
		{name: 'sg_code', type: 'string'},
		{name: 'long_desc', type: 'string'},
		{name: 'code_text_short', type: 'string'},
		{name: 'code', type: 'string'},
		{name: 'code_type', type: 'string'},
		{name: 'modifier', type: 'string'},
		{name: 'units', type: 'string'},
		{name: 'fee', type: 'int'},
		{name: 'superbill', type: 'string'},
		{name: 'related_code', type: 'string'},
		{name: 'taxrates', type: 'string'},
		{name: 'active', type: 'bool'},
		{name: 'reportable', type: 'string'},
        ////////////////////////////////////
		{name: 'sex', type: 'string'},
		{name: 'age_start', type: 'int'},
		{name: 'age_end', type: 'int'},
		{name: 'times_to_perform', type: 'int'},
		{name: 'frequency_number', type: 'int'},
		{name: 'frequency_time', type: 'string'},
		{name: 'pregnant', type: 'bool'},
		{name: 'only_once', type: 'bool'},
		{name: 'active_problems', type: 'string'},
		{name: 'medications', type: 'string'},
		{name: 'labs', type: 'string'}
	]

});