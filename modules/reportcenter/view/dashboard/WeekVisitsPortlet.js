/**
 * Created by ernesto on 5/14/14.
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