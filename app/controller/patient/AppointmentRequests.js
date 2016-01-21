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

Ext.define('App.controller.patient.AppointmentRequests', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'AppointmentRequestWindow',
			selector: '#AppointmentRequestWindow'
		},
		{
			ref: 'AppointmentRequestGrid',
			selector: '#AppointmentRequestGrid'
		},
		{
			ref: 'AppointmentRequestForm',
			selector: '#AppointmentRequestForm'
		},
		{
			ref: 'AppointmentRequestAddBtn',
			selector: '#AppointmentRequestAddBtn'
		},
		{
			ref: 'AppointmentRequestRequestedField',
			selector: '#AppointmentRequestRequestedField'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport':{
				beforeencounterload: me.onAppEncounterLoad
			},
			'#AppointmentRequestGrid':{
				itemdblclick: me.onAppointmentRequestGridItemDblClick
			},
			'#AppointmentRequestWindow':{
				close: me.onAppointmentRequestWindowClose
			},
			'#AppointmentRequestAddBtn':{
				click: me.onAppointmentRequestAddBtnClick
			},
			'#AppointmentRequestSaveBtn':{
				click: me.onAppointmentRequestSaveBtnClick
			},
			'#AppointmentRequestCancelBtn':{
				click: me.onAppointmentRequestCancelBtnClick
			},
			'#AppointmentRequestDateField > button':{
				click: me.onAppointmentRequestDateFieldButtonsClick
			},
			'#AppointmentRequestProcedureFieldSet > combobox':{
				select: me.onAppointmentRequestProcedureFieldSetCombosSelect
			}
		});
	},

	onAppointmentRequestGridItemDblClick: function(grid, record){
		this.getEditWindow(record);
	},

	onAppEncounterLoad: function(encounter){
		this.getAppointmentRequestGrid().reconfigure(encounter.appointmentrequests());
		encounter.appointmentrequests().load();
	},

	onAppointmentRequestDateFieldButtonsClick: function(btn){
		var now = new Date(),
			date;

		switch (btn.action){
			case '1D':
				date = Ext.Date.add(now, Ext.Date.DAY, 1);
				break;
			case '1W':
				date = Ext.Date.add(now, Ext.Date.DAY, 7);
				break;
			case '2W':
				date = Ext.Date.add(now, Ext.Date.DAY, 14);
				break;
			case '3W':
				date = Ext.Date.add(now, Ext.Date.DAY, 21);
				break;
			case '1M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 1);
				break;
			case '3M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 3);
				break;
			case '6M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 6);
				break;
			case '1Y':
				date = Ext.Date.add(now, Ext.Date.YEAR, 1);
				break;
			case '2Y':
				date = Ext.Date.add(now, Ext.Date.YEAR, 2);
				break;
			case '3Y':
				date = Ext.Date.add(now, Ext.Date.YEAR, 3);
				break;
			default:
				date = now;
		}

		this.getAppointmentRequestRequestedField().setValue(date);
	},

	onAppointmentRequestAddBtnClick: function(){

		var record = Ext.create('App.model.patient.AppointmentRequest',{
			pid: app.patient.pid,
			eid: app.patient.eid,
			requested_uid: app.user.id,
			create_uid: app.user.id,
			create_date: new Date()
		});

		this.getEditWindow(record);
	},

	onAppointmentRequestProcedureFieldSetCombosSelect: function(cmb, records){
		var record = this.getAppointmentRequestForm().getForm().getRecord(),
			values = {};

		values[cmb.name] = records[0].data.FullySpecifiedName;
		values[cmb.name + '_code'] = records[0].data.ConceptId;
		values[cmb.name + '_code_type'] = records[0].data.CodeType;
		record.set(values);
	},

	onAppointmentRequestSaveBtnClick:function(btn){
		var form = this.getAppointmentRequestForm().getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			store = this.getAppointmentRequestGrid().getStore();

		if(form.isValid()){
			record.set(values);
			if(!record.store){
				store.add(record);
			}
			store.sync();
		}

		btn.up('window').close();
	},

	getEditWindow: function(record){
		if(!this.getAppointmentRequestWindow()){
			Ext.create('App.view.patient.encounter.AppointmentRequestWindow');
		}
		this.getAppointmentRequestWindow().show();

		if(record){
			this.getAppointmentRequestForm().getForm().loadRecord(record);
		}

	},

	onAppointmentRequestCancelBtnClick:function(btn){
		btn.up('window').close();
	},

	onAppointmentRequestWindowClose:function(){
		this.getAppointmentRequestForm().getForm().reset(true);
	}
});
