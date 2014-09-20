/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

Ext.define('App.model.administration.FacilityStructure', {
	extend: 'Ext.data.Model',
	table: {
		name: 'facility_structures',
		comment: 'Facilities Dept and Specialties'
	},
	fields: [
		{
			name: 'id',
			type: 'string'
		},
		{
			name: 'fid',
			type: 'int',
			index: true
		},
		{
			name: 'parentId',
			type: 'string',
			index: true
		},
		{
			name: 'foreign_id',
			type: 'int',
			index: true
		},
		{
			name: 'foreign_type',
			type: 'string',
			len: 1,
			index: true,
			comment: 'D = department S = specialty'
		},
		{
			name: 'active',
			type: 'bool'
		},
		{
			name: 'leaf',
			type: 'bool',
			store: false,
			convert: function(v, record){
				return record.data.foreign_type == 'S';
			}
		},
		{
			name: 'text',
			type: 'string',
			store: false
		}
	],
	idProperty: 'id',
	proxy: {
		type: 'direct',
		api: {
			read: 'Facilities.getFacilityConfigs',
			create: 'Facilities.addFacilityConfig',
			update: 'Facilities.updateFacilityConfig',
			destroy: 'Facilities.deleteFacilityConfig'
		}
	}
});
