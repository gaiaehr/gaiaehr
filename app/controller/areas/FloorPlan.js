/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

Ext.define('App.controller.areas.FloorPlan', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'FloorPlanPanel',
			selector: '#FloorPlanPanel'
		},
		{
			ref: 'FloorPlanPatientZonePanel',
			selector: '#FloorPlanPatientZonePanel'
		},
		{
			ref: 'FloorPlanAreasCombo',
			selector: '#FloorPlanAreasCombo'
		},
		{
			ref: 'FloorPlanPatientZoneDetailWindow',
			selector: '#FloorPlanPatientZoneDetailWindow'
		},
		{
			ref: 'FloorPlanPatientZoneDetailRemovePatientBtn',
			selector: '#FloorPlanPatientZoneDetailRemovePatientBtn'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'#FloorPlanPanel':{
				activate: me.onFloorPlanPanelActivate,
				deactivate: me.onFloorPlanPanelDeactivate
			},
			'#FloorPlanPatientZoneDetailRemovePatientBtn':{
				click: me.onFloorPlanPatientZoneDetailRemovePatientBtnClick
			},
			'#FloorPlanAreasCombo':{
				select: me.onFloorPlanAreasComboSelect
			}
		});

		me.floorPlanZonesStore = Ext.create('App.store.administration.FloorPlanZones');

	},

	onFloorPlanPanelActivate: function(){
		if(this.getFloorPlanAreasCombo().getValue() == null){
			this.renderZones();
		}else{
			this.setZones();
		}
	},

	onFloorPlanPanelDeactivate: function(){
		if(this.getFloorPlanPatientZoneDetailWindow()){
			this.getFloorPlanPatientZoneDetailWindow().close();
		}
	},

	onFloorPlanPatientZoneDetailRemovePatientBtnClick: function(btn){
		var me = this,
			win = btn.up('window');
		me.unAssignPatient(win.zone, win.zone.data);
		win.close();
	},

	onFloorPlanAreasComboSelect: function(cmb, records){
		var me = this;
		me.loadZones(records[0], function(){
			me.setZones();
		});
	},

	renderZones:function(){
		var me = this,
			cmb = me.getFloorPlanAreasCombo();

		cmb.getStore().load({
			callback:function(records){
				if(records.length > 0){
					cmb.setValue(records[0].data.id);
					me.onFloorPlanAreasComboSelect(cmb, records);
				}else{
					cmb.setValue('');
					me.getFloorPlanPatientZonePanel().removeAll();
				}
			}
		});
	},

	createZone: function(record){
		var me = this, zone;

		zone = me.getFloorPlanPatientZonePanel().add({
			xtype: 'splitbutton',
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
			tooltip: _('patient_name') + ': [empty]',
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
		});

		zone.record = record;
	},

	loadZones: function(cmbRecord, callback){
		var me = this;
		me.getFloorPlanPatientZonePanel().removeAll();
		me.floorPlanZonesStore.load({
			params: {
				floor_plan_id: cmbRecord.data.id
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

	onZoneClicked: function(btn){
		app.setPatient(btn.data.pid, btn.data.name, function(){
			btn.data.eid ? app.openEncounter(btn.data.eid) : app.openPatientSummary();
		});
	},

	onZoneArrowClicked: function(zone){
		var me = this;

		if(!me.getFloorPlanPatientZoneDetailWindow()){
			Ext.create('Ext.Window', {
				width: 300,
				closeAction: 'hide',
				itemId: 'FloorPlanPatientZoneDetailWindow',
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
							return image != null && image != '';
						}
					}
				),
				buttons:[
					{
						text: _('remove_patient'),
						itemId: 'FloorPlanPatientZoneDetailRemovePatientBtn'
					}
				]
			});
		}

		if(zone.data){
			var win = me.getFloorPlanPatientZoneDetailWindow();

			win.zone = zone;
			win.update(zone.data.patient);
			win.show();
			win.alignTo(zone.getEl(), 'tl-tr?');
			win.focus();
		}
	},

	assignPatient: function(zone, data){
		var me = this,
			params = {
				zone_id: zone.zoneId,
				pid: data.pid
			};

		PatientZone.addPatientToZone(params, function(response){
			data.patientZoneId = response.data.id;
			app.msg('Sweet!', data.name + ' ' +_('successfully_moved') + '.');
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

		zone.setTooltip(_('patient_name') + ':' + data.name);
		zone.addCls(data.priority);
		zone.data = data;
	},

	unSetZone: function(zone){
		zone.pid = null;
		zone.data = null;
		if(zone.dropZone) zone.dropZone.unlock();
		if(zone.dragZone) zone.dragZone.lock();
		zone.setTooltip(_('patient_name') + ': [empty]');
		zone.removeCls(zone.priority);
		zone.data = null;
	},

	setZones: function(){
		var me = this,
			panel = me.getFloorPlanPatientZonePanel(),
			floorPlanId = me.getFloorPlanAreasCombo().getValue(),
			zones = panel.items.items,
			zone,
			data;

		PatientZone.getPatientsZonesByFloorPlanId(floorPlanId, function(response){

			zones = panel.items.items;
			data = response;

			for(var j = 0; j < zones.length; j++){
				me.unSetZone(zones[j]);
			}

			for(var i = 0; i < data.length; i++){
				zone = panel.getComponent(data[i].zoneId);
				zone.data = data[i];
				me.setZone(zone, data[i]);
			}
		})
	},

	setFloorPlan: function(floorPlanId){

	}


});