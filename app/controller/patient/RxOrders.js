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

Ext.define('App.controller.patient.RxOrders', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'RxOrdersGrid',
			selector: 'patientrxorderspanel'
		},
		{
			ref: 'RxNormOrderLiveSearch',
			selector: '#RxNormOrderLiveSearch'
		},
		{
			ref: 'CloneRxOrderBtn',
			selector: '#cloneRxOrderBtn'
		},
		{
			ref: 'PrintRxOrderBtn',
			selector: '#printRxOrderBtn'
		},
		{
			ref: 'RxEncounterDxLiveSearch',
			selector: '#rxEncounterDxLiveSearch'
		},
		{
			ref: 'RxEncounterDxCombo',
			selector: '#RxEncounterDxCombo'
		},
		{
			ref: 'RxOrderGridFormNotesField',
			selector: '#RxOrderGridFormNotesField'
		},
		{
			ref: 'RxOrderCompCheckBox',
			selector: '#RxOrderCompCheckBox'
		},
		{
			ref: 'RxOrderSplyCheckBox',
			selector: '#RxOrderSplyCheckBox'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientrxorderspanel': {
				activate: me.onRxOrdersGridActive,
				selectionchange: me.onRxOrdersGridSelectionChange,
				beforerender: me.onRxOrdersGridBeforeRender,
				beforeedit: me.onRxOrdersGridBeforeEdit
			},
			'#RxNormOrderLiveSearch': {
				select: me.onRxNormOrderLiveSearchSelect
			},
			'#newRxOrderBtn': {
				click: me.onNewRxOrderBtnClick
			},
			'#cloneRxOrderBtn': {
				click: me.onCloneRxOrderBtnClick
			},
			'#printRxOrderBtn': {
				click: me.onPrintRxOrderBtnClick
			},
			'#RxOrderCompCheckBox': {
				change: me.onRxOrderCompCheckBoxChange
			},
			'#RxOrderSplyCheckBox': {
				change: me.onRxOrderSplyCheckBoxChange
			}
		});
	},

	onRxOrderCompCheckBoxChange: function(field, value){
		if(value){
			this.getRxOrderSplyCheckBox().setValue(false);
		}
	},

	onRxOrderSplyCheckBoxChange: function(field, value){
		if(value){
			this.getRxOrderCompCheckBox().setValue(false);
		}
	},

	doSelectOrderByOrderId: function(id){
		var sm = this.getRxOrdersGrid().getSelectionModel(),
			record = this.getRxOrdersGrid().getStore().getById(id);
		sm.select(record);
	},

	onRxOrdersGridBeforeRender: function(grid){
		app.on('patientunset', function(){
			grid.editingPlugin.cancelEdit();
			grid.getStore().removeAll();
		});
	},

	onRxOrdersGridSelectionChange:function(sm, selected){
		this.getCloneRxOrderBtn().setDisabled(selected.length == 0);
		this.getPrintRxOrderBtn().setDisabled(selected.length == 0);
	},

	onRxNormOrderLiveSearchSelect: function(combo, record){
		var form = combo.up('form').getForm();

		form.getRecord().set({
			RXCUI: record[0].data.RXCUI,
			CODE: record[0].data.CODE,
			NDC: record[0].data.NDC
		});

		form.findField('dispense').focus(false, 200);

//		Rxnorm.getMedicationAttributesByRxcui(record[0].data.RXCUI, function(provider, response){
//
//			form.getRecord().set({
//				RXCUI: record[0].data.RXCUI,
//				CODE: record[0].data.CODE,
//				NDC: record[0].data.NDC
//			});
//
////			form.setValues({
////				STR: record[0].data.STR.split(',')[0],
////				route: Ext.String.capitalize(response.result.DRT),
////				dose: Ext.String.capitalize(response.result.DST),
////				form: Ext.String.capitalize(response.result.DDF)
////			});
//
//			form.findField('directions').focus(false, 200);
//		});
	},

	onRxOrdersGridBeforeEdit: function(plugin, context){

		this.getRxEncounterDxCombo().getStore().load({
			filters:[
				{
					property:'eid',
					value: context.record.data.eid
				}
			]
		});


	},

	onNewRxOrderBtnClick:function(btn){
		var me = this,
			grid = btn.up('grid');

		grid.editingPlugin.cancelEdit();

		grid.getStore().insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			refill: 0,
			daw: null,
			date_ordered: new Date(),
			begin_date: new Date(),
			created_date: new Date()
		});

		grid.editingPlugin.startEdit(0, 0);
	},

	onCloneRxOrderBtnClick: function(btn){

		var me = this;

		Ext.Msg.show({
			title: 'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					me.doCloneOrder();
				}
			}
		});
	},

	doCloneOrder: function(additionalReference){

		var me = this,
			grid = me.getRxOrdersGrid(),
			sm = grid.getSelectionModel(),
			store = grid.getStore(),
			selection = sm.getSelection(),
			newDate = new Date(),
			records,
			data;

		grid.editingPlugin.cancelEdit();
		sm.deselectAll();

		for(var i = 0; i < selection.length; i++){
			data = Ext.clone(selection[i].data);

			data.pid = app.patient.pid;
			data.eid = app.patient.eid;
			data.uid = app.user.id;

			data.ref_order = data.id;
			if(typeof additionalReference == 'string'){
				data.ref_order += ('~' + additionalReference);
			}

			data.date_ordered = newDate;
			data.begin_date = newDate;
			data.created_date = newDate;

			// clear the id
			data.id = null;
			records = store.insert(0, data);
		}

		grid.editingPlugin.startEdit(records[0], 0);

		return records;
	},

	onPrintRxOrderBtnClick:function(){
		var me = this,
			grid = me.getRxOrdersGrid(),
			items = grid.getSelectionModel().getSelection(),
			notes = '',
			params = {},
			data,
			i;

		params.pid = app.patient.pid;
		params.eid = app.patient.eid;
		params.orderItems = [ ];
		params.docType = 'Rx';

		params.templateId = 5;
		params.orderItems.push(['Description', 'Instructions', 'Dispense', 'Refill', 'Days Supply', 'Dx', 'Notes']);
		for(i = 0; i < items.length; i++){
			data = items[i].data;
			notes = data.notes;

			if(data.ref_order != ''){
				var refs = data.ref_order.split('~');
				notes = 'Reference #: ' + refs[0] + '<br>' + notes;
			}

			params.orderItems.push([
				data.STR + ' ' + data.dose + ' ' + data.route + ' ' + data.form,
				data.directions,
				data.dispense,
				data.refill,
				data.days_supply,
				(data.dxs.join ? data.dxs.join(', ') : data.dxs),
				notes
			]);
		}

		DocumentHandler.createTempDocument(params, function(provider, response){
			if(window.dual){
				dual.onDocumentView(response.result.id, 'Rx');
			}else{
				app.onDocumentView(response.result.id, 'Rx');
			}
		});
	},

	onRxOrdersGridActive:function(grid){
		var store = grid.getStore();
		if(!grid.editingPlugin.editing){
			store.clearFilter(true);
			store.filter([
				{
					property: 'pid',
					value: app.patient.pid
				}
			]);
		}
	}

});