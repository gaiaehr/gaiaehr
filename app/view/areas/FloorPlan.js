/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 3/16/12
 * Time: 9:09 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.areas.FloorPlan', {
	id       : 'panelAreaFloorPlan',
	extend   : 'App.classes.RenderPanel',
	pageTitle: 'Area Floor Plan',
	floorPlanId:null,
	initComponent: function() {
		var me = this;
		me.floorPlanZonesStore = Ext.create('App.store.administration.FloorPlanZones');

		me.floorPlan = Ext.create('Ext.panel.Panel',{
			title:'Floor Plan',
			layout:'absolute',
			tbar:[
				'->',
				{
					xtype:'floorplanareascombo',
					fieldLabel:'Area',
					labelWidth:40,
					listeners:{
						scope:me,
						select:me.onZoneSelect
					}
				}
			]
		});

		me.pageBody = [ me.floorPlan ];

		me.callParent(arguments);
	},


	loadZone:function(record){
		var me = this, zone, form;
		zone = Ext.create('Ext.button.Split', {
		    text:record.data.title,
			scale:'medium',
			x:record.data.x,
			y:record.data.y,
			scope:me,
			handler:me.onZoneClicked,
			menu:[
				form = Ext.create('Ext.form.Panel',{
					bodyPadding:'5 5 0 5',
					items:[
						{
							xtype:'textfield',
							fieldLabel:'Patient Name',
							labelWidth:80,
							name:'patient_name'
						}
					]
				})
			],
			tooltip:'Patient Name: [empty]'
//			listeners:{
//				scope:me,
//				menushow:me.afterMenuShow,
//				menuhide:me.afterMenuHide
//			}
		});
		me.floorPlan.add(zone);
		form.getForm().loadRecord(record);
	},

	onZoneClicked:function(btn){
		var form = btn.menu.items.items[0].getForm(),
			values = form.getValues(),
			rec = form.getRecord();
		say(rec);
	},

	onZoneSelect:function(field, record){
		this.floorPlanId = record[0].data.id;
		this.loadZones();
	},

	loadZones:function(){
		var me = this;
		me.floorPlan.removeAll();
		me.floorPlanZonesStore.load({
			params:{ floor_plan_id:this.floorPlanId },
			scope:me,
			callback:function(records, operation, success){
				for(var i=0; i < records.length; i++){
					me.loadZone(records[i]);
				}
			}
		});
	},

	onActive: function(callback) {
		var me = this;
		if(me.floorPlanId == null){
			me.floorPlanId = 1;
			me.floorPlan.query('floorplanareascombo')[0].setValue(me.floorPlanId);
			me.loadZones();
		}
		callback(true);
	}

});