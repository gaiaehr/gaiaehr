/**
 * Created with JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/13/12
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.charts.HeadCircumference',
{
	extend : 'Ext.panel.Panel',
	layout : 'fit',
	margin : 5,
	initComponent : function()
	{
		var me = this;

		me.items = [
		{
			xtype : 'chart',
			store : me.store,
			animate : false,
			shadow : false,
			legend :
			{
				position : 'right'
			},
			axes : [
			{
				title : me.xTitle,
				type : 'Numeric',
				position : 'left',
				fields : ['PP', 'P3', 'P5', 'P10', 'P25', 'P50', 'P75', 'P90', 'P95', 'P97'],
				grid :
				{
					odd :
					{
						opacity : 1,
						stroke : '#bbb',
						'stroke-width' : 0.5
					}
				},
				minimum : me.xMinimum,
				maximum : me.xMaximum
			},
			{
				title : me.yTitle,

				type : 'Numeric',
				position : 'bottom',
				fields : ['age'],
				minimum : me.yMinimum,
				maximum : me.yMaximum
			}],
			series : [
			{
				title : i18n('circumference_cm'),
				type : 'scatter',
				axis : 'left',
				xField : 'age',
				yField : 'PP',
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
						this.update(me.yTitle + ' : ' + storeItem.get('age') + '<br>' + me.xTitle + ': ' + storeItem.get('PP'));
					}
				}
			},
			{
				title : '97%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P97',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '95%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P95',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '75%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P75',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '50%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P50',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 3,
					opacity : 0.5
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 4
				}
			},
			{
				title : '25%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P25',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '10%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P10',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '5%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P5',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			},
			{
				title : '3%',
				type : 'line',
				axis : 'left',
				xField : 'age',
				yField : 'P3',
				smooth : true,
				showMarkers : false,
				style :
				{
					stroke : '#000000',
					'stroke-width' : 1,
					opacity : 0.3
				},
				highlight :
				{
					stroke : '#FF9900',
					size : 2
				}
			}]
		}];

		me.callParent(arguments);

	}
}); 