/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.model.patient.EncounterCPTsICDs', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'string' },
		{ name: 'pid', type: 'int' },
		{ name: 'eid', type: 'int' },
		{ name: 'code', type: 'string' },
		{ name: 'code_type', type: 'string' },
		{ name: 'code_text_medium', type: 'string' },
		{ name: 'dx_pointers', type: 'string' },
		{ name: 'dx_children'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Encounter.getEncounterCptDxTree',
			create: 'Encounter.addEncounterCptDxTree',
			destroy: 'Encounter.removeEncounterCptDxTree'
		}
	}
});