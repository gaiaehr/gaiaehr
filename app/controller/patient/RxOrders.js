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
			}
		});
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
		var me = this,
			grid = btn.up('grid'),
			sm = grid.getSelectionModel(),
			store = grid.getStore(),
			records = sm.getSelection(),
			newDate = new Date(),
			data;

		Ext.Msg.show({
			title: 'Wait!',
			msg: 'Are you sure you want to clone this prescription?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					grid.editingPlugin.cancelEdit();
					sm.deselectAll();
					for(var i = 0; i < records.length; i++){
						data = Ext.clone(records[i].data);
						data.id = null;
						data.pid = app.patient.pid;
						data.eid = app.patient.eid;
						data.uid = app.user.id;
						data.date_ordered = newDate;
						data.begin_date = newDate;
						data.created_date = newDate;
						store.insert(0, data);
					}
					store.sync({
						success: function(){
							if(window.dual){
								dual.msg(i18n('sweet'), i18n('record_added'));
							}else{
								app.msg(i18n('sweet'), i18n('record_added'));
							}
						},
						failure: function(){
							if(window.dual){
								dual.msg(i18n('oops'), i18n('record_error'), true);
							}else{
								app.msg(i18n('oops'), i18n('record_error'), true);
							}
						}
					});
				}
			}
		});
	},

	onPrintRxOrderBtnClick:function(){
		var me = this,
			grid = me.getRxOrdersGrid(),
			items = grid.getSelectionModel().getSelection(),
			params = {},
			data,
			i;

		params.pid = app.patient.pid;
		params.eid = app.patient.eid;
		params.orderItems = [ ];
		params.docType = 'Rx';

		params.templateId = 5;
		params.orderItems.push(['Description', 'Instructions', 'Dispense', 'Refill', 'Dx']);
		for(i = 0; i < items.length; i++){
			data = items[i].data;
			params.orderItems.push([
				data.STR + ' ' + data.dose + ' ' + data.route + ' ' + data.form,
				data.directions,
				data.dispense,
				data.refill,
				(data.dxs.join ? data.dxs.join(', ') : data.dxs)
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