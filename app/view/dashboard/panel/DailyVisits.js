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

Ext.define('App.view.dashboard.panel.DailyVisits', {
	extend: 'Ext.panel.Panel',

	initComponent: function(){
		var me = this;

		Ext.apply(me, {
			layout: 'fit',
			height: 300,
			items: {
				xtype: 'chart',
				itemId: 'DashboardDailyVisitsChart',
				animate: false,
				shadow: false,
				store: me.store = Ext.create('Ext.data.JsonStore', {
					fields: [ 'total', 'time' ]
				}),
				axes: [
					{
						type: 'Numeric',
						minimum: 0,
						position: 'left',
						fields: [ 'total' ],
						title: 'Patients Per Hour',
						label: {
							font: '11px Arial'
						}
					},
					{
						type: 'Time',
						position: 'bottom',
						fields: [ 'time' ],
						title: 'Time',
						dateFormat: 'g:ia',
						step: [Ext.Date.HOUR, 1],
//						majorTickSteps: 5,
//						minorTickSteps: 1,
						constrain: true,
						fromDate: new Date().setHours(6, 0, 0, 0),
						toDate: new Date().setHours(20, 0, 0, 0)
					}
				],
				series: [
					{
						type: 'line',
						highlight: {
							size: 7,
							radius: 7
						},
						axis: 'left',
						smooth: true,
						fill: true,
						xField: 'time',
						yField: 'total',
						markerConfig: {
							type: 'circle',
							size: 4,
							radius: 4,
							'stroke-width': 0
						}
					}
				]
			}
		});

		me.callParent();
	}
});
