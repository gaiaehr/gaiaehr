/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.Services', {
	extend: 'Ext.data.Model',
	table: {
		name: 'services',
		comment: 'Services'
	},
	fields: [
		{name: 'id', type: 'string', comment: 'Services ID'},
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
		{name: 'has_children', type: 'bool'},
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
		{name: 'labs', type: 'string'},
		{name: 'has_children', type: 'bool'},
		{name: 'class', type: 'string'}
	]

});