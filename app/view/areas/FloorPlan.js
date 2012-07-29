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
						select:me.onFloorPlanSelect
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

			// --->
			// Zone specific reference data
			pid:null,
			zoneId:record.data.id,
			priority:null,
			patientZoneId:null,
//			tpl: Ext.create('Ext.XTemplate',
//			'<div class="patient_btn  {priority}">',
//			'<div class="patient_btn_info">',
//			'<div class="patient_btn_name">{title}</div>',
//			'</div>',
//			'</div>'),
			// <---
			menu:[
				form = Ext.create('Ext.form.Panel',{
					bodyPadding:'5 5 0 5',
					items:[
						{
							xtype:'textfield',
							fieldLabel:'Patient Name',
							labelWidth:80,
							name:'patient_name'
						},
						{
							xtype:'button',
							text:'Remove Patient',
							handler:function(){
								me.unSetZone(zone);
							}
						}
					]
				})
			],
			tooltip:'Patient Name: [empty]',
			listeners:{
				scope:me,
				render:me.initializeZone
//				menushow:me.afterMenuShow,
//				menuhide:me.afterMenuHide
			}
		});
		//zone.update({title:record.data.title});
		me.floorPlan.add(zone);
		form.getForm().loadRecord(record);
	},

	onZoneClicked:function(btn){
//		var form = btn.menu.items.items[0].getForm(),
//			values = form.getValues(),
//			rec = form.getRecord();
		say(btn);
	},

	onFloorPlanSelect:function(field, record){
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

	initializeZone: function(panel) {
		var me = this;
		panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
			ddGroup    : 'patientPoolAreas',
			getDragData: function(e) {
				var sourceEl = panel.btnEl.dom, d;
				if(sourceEl) {
					d = sourceEl.cloneNode(true);
					d.id = Ext.id();
					return panel.dragData = {
								sourceEl: sourceEl,
								repairXY: Ext.fly(sourceEl).getXY(),
								ddel    : d,
								patientData : panel.data.patientData,
								zone: panel
					};
				}
			},
			getRepairXY: function(e) {
				return this.dragData.repairXY;
			},
			b4MouseDown: function(e) {
		        this.autoOffset(e.getPageX(), e.getPageY());
		    }
		});

		panel.dragZone.lock();

		panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
			ddGroup   : 'patientPoolAreas',
			notifyOver: function(dd, e, data) {
				if(panel.pid == null) {
					return Ext.dd.DropZone.prototype.dropAllowed;
				}else{
					return Ext.dd.DropZone.prototype.dropNotAllowed;
				}
			},
			notifyDrop: function(dd, e, data) {
//				say('notifyDrop');
//				say(data);
				panel.data = data;
				panel.dragZone.unlock();
				if(data.zone){
					FloorPlans.unSetPatientZoneByZoneId(data.patientData.patientZoneId, function(){
						me.unSetZone(data.zone);
					});
				}
				me.dropPatient(panel, data.patientData);
			}
		});
	},

	dropPatient:function(zone, data){
		var me = this,
			params = {
				zone_id:zone.zoneId,
				pid:data.pid
			};
		FloorPlans.setPatientToZone(params,function(provider, response){
			data.patientZoneId = response.result.data.patientZoneId;
			me.msg('Sweet!', data.name + ' successfully moved.');
			me.setZone(zone, data);
		});
	},

	setZone:function(zone, data){
		zone.pid = data.pid;
		zone.priority = data.priority;
		zone.patientZoneId = data.patientZoneId;
		zone.setTooltip('Patient Name:' + data.name);
		zone.addCls(data.priority);
	},

	unSetZone:function(zone){
		zone.dragZone.lock();
		zone.pid = null;
		zone.setTooltip('Patient Name: [empty]');
		zone.removeCls(zone.priority);
	},

	setZones:function(){

	},

	setFloorPlan:function(floorPlanId){

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