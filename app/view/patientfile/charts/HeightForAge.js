/**
 * Created with JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/13/12
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patientfile.charts.HeightForAge',{
	extend:'Ext.panel.Panel',
	layout:'fit',
	margin:5,
	title:'Height For Age',

	initComponent:function(){
		var me = this;

		me.items = [
			{
				xtype  : 'chart',
				style  : 'background:#fff',
				store  : me.store,
				animate: true,
				shadow : true,
				axes   : [
					{
						title         : 'Height (inches)',
						type          : 'Numeric',
						minimum       : 0,
						maximum       : 100,
						position      : 'left',
						fields        : ['height_in'],
						majorTickSteps: 50,
						minorTickSteps: 1,
						grid          : {
							odd: {
								opacity       : 1,
								stroke        : '#bbb',
								'stroke-width': 0.5
							}
						}
					},
					{
						title         : 'Height (centimeters)',
						type          : 'Numeric',
						minimum       : 0,
						maximum       : 250,
						position      : 'right',
						majorTickSteps: 125,
						minorTickSteps: 1
					},
					{
						title         : 'Age (Years)',
						type          : 'Numeric',
						minimum       : 0,
						maximum       : 20,
						position      : 'bottom',
						fields        : ['date'],
						majorTickSteps: 18,
						minorTickSteps: 2

					}
				],
				series : [
					{
						title       : 'Actual Growth',
						type        : 'line',
						axis        : 'left',
						xField      : 'date',
						yField      : 'height_in',
						highlight   : {
							size  : 10,
							radius: 10
						},
						markerConfig: {
							type          : 'circle',
							size          : 5,
							radius        : 5,
							'stroke-width': 0
						}
					},
					{
						title    : 'Normal Growth',
						type     : 'line',
						highlight: {
							size  : 5,
							radius: 5
						},
						axis     : 'left',
						xField   : 'date',
						yField   : 'height_in',
						smooth   : true,
						fill     : true
					}
				]
			}

		];

		me.callParent(arguments);

	}
});