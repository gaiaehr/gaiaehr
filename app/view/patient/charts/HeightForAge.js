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

Ext.define('App.view.patient.charts.HeightForAge',
{
	extend : 'Ext.panel.Panel',
	layout : 'fit',
	margin : 5,
	title : i18n('height_for_age'),

	initComponent : function()
	{
		var me = this;

		me.items = [
		{
			xtype : 'chart',
			style : 'background:#fff',
			store : me.store,
			animate : true,
			shadow : true,
			axes : [
			{
				title : i18n('height_inches'),
				type : 'Numeric',
				minimum : 0,
				maximum : 100,
				position : 'left',
				fields : ['height_in'],
				majorTickSteps : 50,
				minorTickSteps : 1,
				grid :
				{
					odd :
					{
						opacity : 1,
						stroke : '#bbb',
						'stroke-width' : 0.5
					}
				}
			},
			{
				title : i18n('height_centimeters'),
				type : 'Numeric',
				minimum : 0,
				maximum : 250,
				position : 'right',
				majorTickSteps : 125,
				minorTickSteps : 1
			},
			{
				title : i18n('age_years'),
				type : 'Numeric',
				minimum : 0,
				maximum : 20,
				position : 'bottom',
				fields : ['date'],
				majorTickSteps : 18,
				minorTickSteps : 2

			}],
			series : [
			{
				title : i18n('actual_growth'),
				type : 'line',
				axis : 'left',
				xField : 'date',
				yField : 'height_in',
				highlight :
				{
					size : 10,
					radius : 10
				},
				markerConfig :
				{
					type : 'circle',
					size : 5,
					radius : 5,
					'stroke-width' : 0
				}
			},
			{
				title : i18n('normal_growth'),
				type : 'line',
				highlight :
				{
					size : 5,
					radius : 5
				},
				axis : 'left',
				xField : 'date',
				yField : 'height_in',
				smooth : true,
				fill : true
			}]
		}];

		me.callParent(arguments);

	}
}); 