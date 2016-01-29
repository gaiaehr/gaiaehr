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

Ext.define('App.view.patient.charts.BPPulseTemp',
{
	extend : 'Ext.container.Container',
	layout :
	{
		type : 'vbox',
		align : 'stretch'
	},
	style : 'background-color:#fff',
	defaults :
	{
		xtype : 'panel',
		layout : 'fit',
		flex : 1
	},
	initComponent : function()
	{
		var me = this;

		me.items = [
		{
			title : _('blood_pressure'),
			margin : 5,
			items : [
			{
				xtype : 'chart',
				style : 'background:#fff',
				store : me.store,
				animate : false,
				shadow : true,
				legend :
				{
					position : 'right'
				},
				axes : [
				{
					title : _('blood_pressure'),
					type : 'Numeric',
					position : 'left',
					fields : ['bp_systolic', 'bp_diastolic', 'bp_systolic_normal', 'bp_diastolic_normal'],
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
					title : _('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']
				}],
				series : [
				{
					title : _('systolic'),
					type : 'line',
					axis : 'left',
					xField : 'date',
					yField : 'bp_systolic',
					smooth : true,
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
					},
					tips :
					{
						trackMouse : true,
						renderer : function(storeItem, item)
						{
							this.update('Date: ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>Systolic: ' + storeItem.get('bp_systolic'));
						}
					}
				},
				{
					title : _('diastolic'),
					type : 'line',
					axis : 'left',
					xField : 'date',
					yField : 'bp_diastolic',
					smooth : true,
					highlight :
					{
						size : 5,
						radius : 5
					},
					markerConfig :
					{
						type : 'cross',
						size : 5,
						radius : 5,
						'stroke-width' : 0
					},
					tips :
					{
						trackMouse : true,
						renderer : function(storeItem, item)
						{
							this.update(_('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + _('diastolic') + ': ' + storeItem.get('bp_diastolic'));
						}
					}

				},
				//                            {
				//                                type     : 'area',
				//                                highlight: true,
				//                                axis     : 'left',
				//                                xField   : 'date',
				//                                yField   : ['bp_diastolic_normal', 'bp_systolic_normal'],
				//                                style    : {
				//                                    opacity: 0.93
				//                                }
				//                            },
				{
					type : 'line',
					showMarkers : false,
					axis : 'left',
					xField : 'date',
					yField : 'bp_diastolic_normal',
					style :
					{
						stroke : '#000000',
						'stroke-width' : 1
					}
				},
				{
					type : 'line',
					showMarkers : false,
					axis : 'left',
					xField : 'date',
					yField : 'bp_systolic_normal',
					style :
					{
						stroke : '#000000',
						'stroke-width' : 1
					}
				}]
			}]
		},
		{
			title : 'Pulse',
			margin : '0 5 5 5',
			items : [
			{
				xtype : 'chart',
				style : 'background:#fff',
				store : me.store,
				animate : false,
				shadow : true,
				legend :
				{
					position : 'right'
				},
				axes : [
				{
					title : _('pulse_per_min'),
					type : 'Numeric',
					position : 'left',
					fields : ['pulse'],
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
					title : _('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']

				}],
				series : [
				{
					title : _('pulse'),
					type : 'line',
					axis : 'left',
					xField : 'date',
					yField : 'pulse',
					smooth : true,
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
					},
					tips :
					{
						trackMouse : true,
						renderer : function(storeItem, item)
						{
							this.update(_('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + _('pulse_per_min') + ': ' + storeItem.get('pulse'));
						}
					}
				}]
			}]
		},
		{
			title : _('temperature'),
			margin : '0 5 5 5',
			items : [
			{

				xtype : 'chart',
				store : me.store,
				animate : false,
				shadow : true,
				legend :
				{
					position : 'right'
				},
				axes : [
				{
					title : _('temp_fahrenheits'),
					type : 'Numeric',
					position : 'left',
					fields : ['temp_f'],
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
					title : _('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']

				}],
				series : [
				{
					title : _('temp_fahrenheits'),
					type : 'line',
					axis : 'left',
					xField : 'date',
					yField : 'temp_f',
					smooth : true,
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
					},
					tips :
					{
						trackMouse : true,
						renderer : function(storeItem, item)
						{
							this.update(_('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + _('temp_fahrenheits') + ': ' + storeItem.get('temp_f'));
						}
					}
				}]
			}]
		}];

		me.callParent(arguments);

	}
}); 