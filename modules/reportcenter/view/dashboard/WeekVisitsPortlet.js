/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
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

Ext.define('Modules.reportcenter.view.dashboard.WeekVisitsPortlet', {
	extend: 'Ext.chart.Chart',
	xtype:'weekvisitsportlet',
	width: 500,
	height: 300,
	animate: true,
	theme:'Category2',
	store: Ext.create('Ext.data.JsonStore', {
		fields: ['name', 'data1', 'data2', 'data3'],
		data: [
			{ 'name': 'metric one',   'data1': 14, 'data2': 12, 'data3': 13 },
			{ 'name': 'metric two',   'data1': 16, 'data2':  8, 'data3':  3 },
			{ 'name': 'metric three', 'data1': 14, 'data2':  2, 'data3':  7 },
			{ 'name': 'metric four',  'data1':  6, 'data2': 14, 'data3': 23 },
			{ 'name': 'metric five',  'data1': 36, 'data2': 38, 'data3': 33 }
		]
	}),
	axes: [{
		type: 'Radial',
		position: 'radial',
		label: {
			display: true
		}
	}],
	series: [{
		type: 'radar',
		xField: 'name',
		yField: 'data1',
		showInLegend: true,
		showMarkers: true,
		markerConfig: {
			radius: 5,
			size: 5
		},
		style: {
			'stroke-width': 2,
			fill: 'none'
		}
	},{
		type: 'radar',
		xField: 'name',
		yField: 'data2',
		showMarkers: true,
		showInLegend: true,
		markerConfig: {
			radius: 5,
			size: 5
		},
		style: {
			'stroke-width': 2,
			fill: 'none'
		}
	},{
		type: 'radar',
		xField: 'name',
		yField: 'data3',
		showMarkers: true,
		showInLegend: true,
		markerConfig: {
			radius: 5,
			size: 5
		},
		style: {
			'stroke-width': 2,
			fill: 'none'
		}
	}]
});