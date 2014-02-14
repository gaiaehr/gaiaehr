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

Ext.define('App.model.administration.LabObservations', {
	extend: 'Ext.data.Model',
	table: {
		name: 'labs_panels',
		comment: 'Laboratory Observations'
	},
	fields: [
		{name: 'id', type: 'string', comment: 'LOINC'},
		{name: 'code_text_short', type: 'string' },
		{name: 'parent_id', type: 'int', dataType: 'bigint' },
		{name: 'parent_loinc', type: 'string', dataType: 'text' },
		{name: 'parent_name', type: 'string', dataType: 'text'  },
		{name: 'sequence', type: 'string', dataType: 'text' },
		{name: 'loinc_number', type: 'string', dataType: 'text' },
		{name: 'loinc_name', type: 'string', dataType: 'text' },
		{name: 'default_unit', type: 'string' },
		{name: 'range_start', type: 'string' },
		{name: 'range_end', type: 'string' },
		{name: 'required_in_panel', type: 'string', dataType: 'text' },
		{name: 'description', type: 'string', dataType: 'text' },
		{name: 'active', type: 'bool' }
	]
});