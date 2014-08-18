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

Ext.define('App.view.areas.FloorPlan', {
	id: 'panelAreaFloorPlan',
	extend: 'App.ux.RenderPanel',
	pageTitle: i18n('area_floor_plan'),
	floorPlanId: null,
	initComponent: function(){
		var me = this;

		me.floorPlanZonesStore = Ext.create('App.store.administration.FloorPlanZones');

		me.floorPlan = Ext.create('Ext.panel.Panel', {
			title: i18n('floor_plans'),
			layout: 'absolute',
			tbar: [
				'->',
				{
					xtype: 'floorplanareascombo',
					fieldLabel: i18n('area'),
					labelWidth: 40,
					listeners: {
						scope: me,
						select: me.onFloorPlanSelect
					}
				}
			],
			tools: [
				{
					type: 'refresh',
					scope: me,
					handler: me.setZones
				}
			]
		});

		me.pageBody = [ me.floorPlan ];
		me.callParent(arguments);

	},

	renderZones:function(){
		var me = this,
			cmb = me.query('floorplanareascombo')[0];

		cmb.getStore().load({
			callback:function(records){
				if(records.length > 0){
					cmb.setValue(records[0].data.id);
					me.onFloorPlanSelect(cmb, records);
				}else{
					cmb.setValue('');
					me.floorPlanId = null;
					me.floorPlan.removeAll();
				}
			}
		});
	},

	createZone: function(record){
		var me = this, zone;
		zone = me.floorPlan.add(
			Ext.create('Ext.button.Split', {
				text: record.data.title,
				scale: record.data.scale,
				itemId: record.data.id,
				style: {
					'border-color': record.data.border_color,
					'background-color': record.data.bg_color
				},
				x: record.data.x,
				y: record.data.y,
				width: record.data.width,
				height: record.data.height,
				scope: me,
				handler: me.onZoneClicked,
				tooltip: i18n('patient_name') + ': [empty]',
				listeners: {
					scope: me,
					render: me.initializeZone,
					arrowclick: me.onZoneArrowClicked
				},
				// patient zone specific reference data --->
				pid: null,
				zoneId: record.data.id,
				priority: null,
				patientZoneId: null
				// <---
			})
		);
		zone.record = record;
	},

	onZoneArrowClicked: function(zone){
		var me = this;

		if(!me.patientInfo){
			me.patientInfo = Ext.create('Ext.Window', {
				title: '',
				width: 300,
				closeAction: 'hide',
				tpl: new Ext.XTemplate(
						'<div class="zoneSummaryContainer">' +
						'   <div class="zoneSummaryArea">' +
						'       <tpl if="this.patientImg(image)">',
						'           <img src="{image}" height="96" width="96">' +
						'       <tpl else>',
						'           <img src="'+ app.patientImage +'" height="96" width="96">',
						'       </tpl>',
						'       <p>Name: {name}</p>' +
						'       <p>DOB: {DOB}</p>' +
						'       <p>Age: {age.str}</p>' +
						'       <p>Sex: {sex}</p>' +
						'   </div>' +
						'</div>',
					{
						patientImg:function(image){
							return image != null;
						}
					}
				),
				listeners: {
					scope: me,
					blur: {
						element: 'el',
						fn: me.onPatientInfoBlur
					}
				}
			});
		}
		if(zone.data){
			me.patientInfo.update(zone.data.patient);
			me.patientInfo.show();
			me.patientInfo.alignTo(zone.getEl(), 'tl-tr?');
			me.patientInfo.focus();
		}
	},

	onZoneClicked: function(btn){
		app.setPatient(btn.data.pid, btn.data.name, function(){
			btn.data.eid ? app.openEncounter(btn.data.eid) : app.openPatientSummary();
		});
	},

	onPatientInfoBlur: function(){
		this.patientInfo.hide();
	},

	onFloorPlanSelect: function(field, records){
		var me = this;
		me.floorPlanId = records[0].data.id;
		me.loadZones(function(){
			me.setZones();
		});
	},

	loadZones: function(callback){
		var me = this;
		me.floorPlan.removeAll();
		me.floorPlanZonesStore.load({
			params: {
				floor_plan_id: this.floorPlanId
			},
			scope: me,
			callback: function(records, operation, success){
				for(var i = 0; i < records.length; i++){
					me.createZone(records[i]);
				}
				callback();
			}
		});
	},

	initializeZone: function(panel){
		var me = this;
		panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
			ddGroup: 'patientPoolAreas',

			getDragData: function(e){
				var sourceEl = panel.btnEl.dom, d;
				if(sourceEl){
					d = sourceEl.cloneNode(true);
					d.id = Ext.id();
					return panel.dragData = {
						sourceEl: sourceEl,
						repairXY: Ext.fly(sourceEl).getXY(),
						ddel: d,
						patientData: panel.data,
						zone: panel
					};
				}else{
					return false;
				}
			},

			getRepairXY: function(e){
				return this.dragData.repairXY;
			},

			b4MouseDown: function(e){
				this.autoOffset(e.getPageX(), e.getPageY());
			}
		});

		panel.dragZone.lock();

		panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
			ddGroup: 'patientPoolAreas',

			notifyOver: function(dd, e, data){
				if(panel.pid == null){
					return Ext.dd.DropZone.prototype.dropAllowed;
				}else{
					return Ext.dd.DropZone.prototype.dropNotAllowed;
				}
			},

			notifyDrop: function(dd, e, data){
				panel.data = data.patientData;
				if(data.zone){
					me.unAssignPatient(data.zone, panel.data);
				}
				me.assignPatient(panel, panel.data);
			}
		});
	},

	assignPatient: function(zone, data){
		var me = this, params = {
			zone_id: zone.zoneId,
			pid: data.pid
		};

		PatientZone.addPatientToZone(params, function(provider, response){
			data.patientZoneId = response.result.data.id;
			me.msg('Sweet!', data.name + i18n('successfully_moved') + '.');
			me.setZone(zone, data);
		});
	},

	unAssignPatient: function(zone, data){
		var me = this;
		PatientZone.removePatientFromZone({id:data.patientZoneId}, function(){
			me.unSetZone(zone)
		});
	},

	setZone: function(zone, data){
		zone.pid = data.pid;
		zone.priority = data.priority;
		zone.patientZoneId = data.patientZoneId;

		if(zone.dropZone) zone.dropZone.lock();
		if(zone.dragZone) zone.dragZone.unlock();

		zone.setTooltip(i18n('patient_name') + ':' + data.name);
		zone.addCls(data.priority);
		zone.data = data;
	},

	unSetZone: function(zone){
		zone.pid = null;
		zone.data = null;
		if(zone.dropZone) zone.dropZone.unlock();
		if(zone.dragZone) zone.dragZone.lock();
		zone.setTooltip(i18n('patient_name') + ': [empty]');
		zone.removeCls(zone.priority);
		zone.data = null;
	},

	setZones: function(){
		var me = this, zone, zones, data;
		PatientZone.getPatientsZonesByFloorPlanId(me.floorPlanId, function(provider, response){

			zones = me.floorPlan.items.items;
			data = response.result;

			for(var j = 0; j < zones.length; j++){
				me.unSetZone(zones[j]);
			}

			for(var i = 0; i < data.length; i++){
				zone = me.floorPlan.getComponent(data[i].zoneId);
				zone.data = data[i];
				me.setZone(zone, data[i]);
			}
		})
	},

	setFloorPlan: function(floorPlanId){

	},

	onActive: function(callback){
		var me = this;

		if(me.floorPlanId == null){
			me.renderZones();
		}else{
			me.setZones();
		}
		callback(true);
	}
});