/**
 * Users.ejs.php
 * Description: Users Screen
 * v0.0.4
 *
 * Author: Ernesto J Rodriguez (Certun)
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace User.getUsers
 * @namespace User.addUser
 * @namespace User.updateUser
 * @namespace User.chechPasswordHistory
 */
Ext.define('App.view.administration.FloorPlans', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelFloorPlans',
	pageTitle    : 'Floor Plan Editor',
	pageLayout   : 'border',
	floorPlanId  : null,
	activeZone   : null,
	initComponent: function() {
		var me = this;
		me.floorPlansStore = Ext.create('App.store.administration.FloorPlans',{
			autoLoad:true
		});
		me.floorPlanZonesStore = Ext.create('App.store.administration.FloorPlanZones');

		me.floorPlans = Ext.create('Ext.grid.Panel',{
			title:'Floor Plans',
			region:'west',
			width:200,
			split:true,
			hideHeaders:true,
			store: me.floorPlansStore,
			plugins:[
				me.floorPlanEditing = Ext.create('Ext.grid.plugin.RowEditing', {
		            clicksToEdit: 2
		        })
			],
			columns:[
				{
					dataIndex:'title',
					sortable: false,
                    hideable: false,
					flex: 1,
					editor:{
						xtype:'textfield'
					}
				}
			],
			tbar:[
				'->',
				{
					text:'Add Floor Plan',
					action:'newFloorPlan',
					scope:me,
					handler:me.onNewFloorPlan
				}
			],
			listeners:{
				scope:me,
				select:me.onFloorPlanSelected
			}
		});

		me.floorPlan = Ext.create('Ext.panel.Panel',{
			title:'Floor Plan',
			region:'center',
			bodyCls:'floorPlan',
			layout:'absolute',
			tbar:[
				'->',
				{
					text:'Add Zone',
					action:'newZone',
					scope:me,
					handler:me.onNewZone
				}
			]
		});



		me.listeners = {
			afterrender:function(){
				Ext.create('Ext.util.KeyNav', Ext.getDoc(), {
			        scope: me,
			        left: function(){
				        me.moveZone('left')
			        },
			        up: function(){
                        me.moveZone('up')
                    },
			        right: function(){
                        me.moveZone('right')
                    },
			        down: function(){
                        me.moveZone('down')
                    }
			    });
			}
		};

		me.pageBody = [ me.floorPlans, me.floorPlan ];
		me.callParent(arguments);
	},

	onNewZone:function(){
		this.createZone(null);
	},

	createZone:function(record){
		var me = this, zone, form;
		zone = Ext.create('Ext.button.Split', {
		    text: record ? record.data.title : 'New Zone',
			toggleGroup:'zones',
			draggable:{
				listeners:{
					scope:me,
					dragend:me.zoneDragged
				}
			},
			scale:'medium',
			x:record ? record.data.x : 0,
			y:record ? record.data.y : 0,
			enableToggle:true,
			toggleHandler:function(btn, pressed){
				if(pressed){
					me.activeZone = zone;
					me.floorPlan.focus();
				}else{
					me.activeZone = null;
					var rec = btn.menu.items.items[0].getForm().getRecord();
					rec.set({x:btn.x,y:btn.y});
				}
			},
			menu:[
				form = Ext.create('Ext.form.Panel',{
					bodyPadding:'5 5 0 5',
					items:[
						{
							xtype:'textfield',
							fieldLabel:'Zone Name',
							labelWidth:80,
							name:'title'
						}
					]
				})
			],
			listeners:{
				scope:me,
				menushow:me.afterMenuShow,
				menuhide:me.afterMenuHide
			}
		});

		me.floorPlan.add(zone);

		if(record != null){
			form.getForm().loadRecord(record)
		}else{
			me.floorPlanZonesStore.add({
				floor_plan_id:me.floorPlanId,
				title:'New Zone',
				x:0,
				y:0,
				active:1
			});

			me.floorPlanZonesStore.sync({
				callback:function(batch, options){
					form.getForm().loadRecord(batch.operations[0].records[0])
				}
			})
		}
	},

	afterMenuShow:function(btn){
		btn.toggle(true);
	},

	afterMenuHide:function(btn){
		var form = btn.menu.items.items[0].getForm(),
			values = form.getValues(),
			rec = form.getRecord();
		btn.setText(values.title);
		rec.set(values);
	},

	moveZone:function(direction){
		if(app.currCardCmp == this && this.activeZone != null){
			var x = this.activeZone.x, y = this.activeZone.y;
			if(direction == 'left'){
				x = x-1;
			}else if(direction == 'right'){
				x = x+1;
			}else if(direction == 'up'){
				y = y-1;
			}else if(direction == 'down'){
				y = y+1;
			}
			this.activeZone.setPosition(x,y);
		}
	},

	zoneDragged:function(drag){
		var me = this, rec = drag.comp.menu.items.items[0].getForm().getRecord();
		rec.set({x:drag.comp.x,y:drag.comp.y});
	},

	onNewFloorPlan:function(){
		this.floorPlansStore.add({
			title:'New Floor Plan'
		});
	},

	onFloorPlanSelected:function(model, record){
		this.floorPlanId = record.data.id;
		this.reloadFloorPlanZones();
	},

	reloadFloorPlanZones:function(){
		var me = this;
		me.floorPlan.removeAll();
		me.floorPlanZonesStore.load({
			params:{ floor_plan_id:this.floorPlanId },
			scope:me,
			callback:function(records, operation, success){
				this.activeZone = null;
				for(var i=0; i < records.length; i++){
					me.createZone(records[i]);
				}
			}
		});
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		var me = this;
		me.floorPlans.getSelectionModel().select(0);
		callback(true);
	}
});