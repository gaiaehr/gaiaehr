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

Ext.define('App.model.patient.QRCptCodes', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int', comment: 'QR CPT Code ID'},
		{name: 'eid', type: 'int'},
		{name: 'code', type: 'string'},
		{name: 'code_text', type: 'string'},
		{name: 'code_text_medium', type: 'string'},
		{name: 'place_of_service', type: 'string'},
		{name: 'emergency', type: 'bool'},
		{name: 'charge', type: 'string'},
		{name: 'days_of_units', type: 'string'},
		{name: 'essdt_plan', type: 'string'},
		{name: 'modifiers', type: 'string'},
		{name: 'status', type: 'int', defaultValue: 0}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Services.getCptCodes'
		},
		reader: {
			root: 'rows',
			totalProperty: 'totals'
		}
	}
});