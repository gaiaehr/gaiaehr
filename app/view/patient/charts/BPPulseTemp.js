/**
 * Created with JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/13/12
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
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
			title : i18n('blood_pressure'),
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
					title : i18n('blood_pressure'),
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
					title : i18n('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']
				}],
				series : [
				{
					title : i18n('systolic'),
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
					title : i18n('diastolic'),
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
							this.update(i18n('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + i18n('diastolic') + ': ' + storeItem.get('bp_diastolic'));
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
					title : i18n('pulse_per_min'),
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
					title : i18n('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']

				}],
				series : [
				{
					title : i18n('pulse'),
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
							this.update(i18n('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + i18n('pulse_per_min') + ': ' + storeItem.get('pulse'));
						}
					}
				}]
			}]
		},
		{
			title : i18n('temperature'),
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
					title : i18n('temp_fahrenheits'),
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
					title : i18n('date'),
					type : 'Time',
					dateFormat : 'Y-m-d h:i:s a',
					position : 'bottom',
					fields : ['date']

				}],
				series : [
				{
					title : i18n('temp_fahrenheits'),
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
							this.update(i18n('date') + ': ' + Ext.Date.format(storeItem.get('date'), 'Y-m-d h:i:s a') + '<br>' + i18n('temp_fahrenheits') + ': ' + storeItem.get('temp_f'));
						}
					}
				}]
			}]
		}];

		me.callParent(arguments);

	}
}); 