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

Ext.define('App.model.patient.charts.WeightForAgeInf', {
	extend: 'Ext.data.Model',
	table: {
		name: 'weightforageinf',
		comment: 'Weight For Age Information'
	},
	fields: [
		{name: 'id', type: 'int', comment: 'Weight For Age Information ID'},
		{name: 'age', type: 'float'},
		{name: 'PP', type: 'float'},
		{name: 'P3', type: 'float'},
		{name: 'P5', type: 'float'},
		{name: 'P10', type: 'float'},
		{name: 'P25', type: 'float'},
		{name: 'P50', type: 'float'},
		{name: 'P75', type: 'float'},
		{name: 'P90', type: 'float'},
		{name: 'P95', type: 'float'},
		{name: 'P97', type: 'float'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'VectorGraph.getGraphData'
		},
		reader: {
			type: 'json'
		},
		extraParams: {
			type: 1
		}
	}

});