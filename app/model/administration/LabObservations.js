/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.LabObservations', {
	extend: 'Ext.data.Model',
	table: {
		name:'labobservations',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Laboratory Observations'
	},
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