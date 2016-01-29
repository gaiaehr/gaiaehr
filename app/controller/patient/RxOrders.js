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
	requires: [],
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
			ref: 'RxOrderMedicationInstructionsCombo',
			selector: '#RxOrderMedicationInstructionsCombo'
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
				beforeedit: me.onRxOrdersGridBeforeEdit,
				edit: me.onRxOrdersGridEdit
			},
			'#RxNormOrderLiveSearch': {
				beforeselect: me.onRxNormOrderLiveSearchBeforeSelect
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

		if(record){
			sm.select(record);
			return record;
		}

		return false;
	},

	onRxOrdersGridBeforeRender: function(grid){
		app.on('patientunset', function(){
			grid.editingPlugin.cancelEdit();
			grid.getStore().removeAll();
		});
	},

	onRxOrdersGridSelectionChange: function(sm, selected){
		this.getCloneRxOrderBtn().setDisabled(selected.length === 0);
		this.getPrintRxOrderBtn().setDisabled(selected.length === 0);
	},

	onRxNormOrderLiveSearchBeforeSelect: function(combo, record){
		var form = combo.up('form').getForm(),
			insCmb = this.getRxOrderMedicationInstructionsCombo(),
            store;

		form.getRecord().set({
			RXCUI: record.data.RXCUI,
			CODE: record.data.CODE,
			NDC: record.data.NDC
		});

		store = record.instructions();
		insCmb.bindStore(store, true);
		insCmb.store = store;
		insCmb.store.load();
		form.findField('dispense').focus(false, 200);
	},

	onRxOrdersGridBeforeEdit: function(plugin, context){

		this.getRxEncounterDxCombo().getStore().load({
			filters: [
				{
					property: 'eid',
					value: context.record.data.eid
				}
			]
		});

		this.getRxOrderMedicationInstructionsCombo().getStore().load({
			filters: [
				{
					property: 'rxcui',
					value: context.record.data.RXCUI
				}
			]
		});
	},

	onRxOrdersGridEdit: function(plugin, context){
		var insCmb = this.getRxOrderMedicationInstructionsCombo(),
			instructions = context.record.data.directions,
			record = insCmb.findRecordByValue(instructions),
            store;

		// record found
		if(record !== false) return true;

		Ext.Msg.show({
			title: _('new_instruction'),
			msg: '<p>' + instructions + '</p><p>' + _('would_you_like_to_save_it') + '</p>',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					store = insCmb.getStore();
					store.add({
						rxcui: context.record.data.RXCUI,
						occurrence: '1',
						instruction: instructions
					});
					store.sync();
				}
			}
		});
		return true;
	},

	onNewRxOrderBtnClick: function(btn){
		var grid = btn.up('grid');

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
			title: _('wait'),
			msg: _('sure_you_want_clone_prescription'),
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

	onPrintRxOrderBtnClick: function(orders){
		var me = this,
			grid = me.getRxOrdersGrid(),
			items = (Ext.isArray(orders) ? orders : grid.getSelectionModel().getSelection()),
			isSingleColumnTable = true,
			references = '',
			params = {},
			columns,
			data,
            i,
            refs,
            text;

		params.pid = app.patient.pid;
		params.eid = app.patient.eid;
		params.orderItems = [];
		params.docType = 'Rx';
		params.templateId = 5;

		if(isSingleColumnTable){
			columns = [''];
		}else{
			columns = [
                'Description',
                'Instructions',
                'Dispense',
                'Refill',
                'Days Supply',
                'Dx',
                'Notes',
                'References'
            ];
		}

		params.orderItems.push(columns);

		for(i = 0; i < items.length; i++){
			data = items[i].data;

			if(data.ref_order !== ''){
				refs = data.ref_order.split('~');
				if(refs.length >= 3){
					references = 'Rx Reference#: ' + refs[2];
				}
			}

			if(isSingleColumnTable){

				text = '<u>' + _('order_number') + '</u>: ' + g('rx_order_number_prefix') + data.id + '<br>';
				text += '<u>' + _('description') + '</u>: ' + '<b>' + data.STR.toUpperCase() + '</b><br>';
				text += '<u>' + _('dispense_as_written') + '</u>: ' + (data.daw ? _('yes') : _('no')) + '<br>';
				text += '<u>' + _('quantity') + '</u>: ' + data.dispense + '<br>';

				if(data.days_supply){
					text += '<u>' + _('days_supply') + '</u>: ' + data.days_supply + '<br>';
				}

				text += '<u>' + _('refill') + '</u>: ' + data.refill + '<br>';
				text += '<u>' + _('instructions') + '</u>: ' + data.directions + '<br>';

				var dxs = (data.dxs.join ? data.dxs.join(', ') : data.dxs);
				if(dxs && dxs !== ''){
					text += '<u>' + _('dx') + '</u>: ' + (data.dxs.join ? data.dxs.join(', ') : data.dxs) + '<br>';
				}

				if(data.notes !== ''){
					text += '<u>' + _('notes_to_pharmacist') + '</u>: ' + data.notes + '<br>';
				}

				if(references !== ''){
					text += '<u>References</u>: ' + references + '<br>';
				}

				if(data.system_notes !== ''){
					text += '<b>' + data.system_notes + '</b><br>';
				}

				params.orderItems.push([text]);

			}else{

				params.orderItems.push([
					data.STR + ' ' + data.dose + ' ' + data.route + ' ' + data.form,
					data.directions,
					data.dispense,
					data.refill,
					data.days_supply,
					(data.dxs.join ? data.dxs.join(', ') : data.dxs),
					data.notes,
					references
				]);
			}
		}

		DocumentHandler.createTempDocument(params, function(provider, response){
			if(window.dual){
				dual.onDocumentView(response.result.id, 'Rx');
			}else{
				app.onDocumentView(response.result.id, 'Rx');
			}
		});
	},

	onRxOrdersGridActive: function(grid){
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
	},

	doAddOrderByTemplate: function(data){
		var me = this,
			grid = me.getRxOrdersGrid(),
			store = grid.getStore(),
			newDate = new Date();

		data.pid = app.patient.pid;
		data.eid = app.patient.eid;
		data.uid = app.user.id;
		data.date_ordered = newDate;
		data.begin_date = newDate;
		data.created_date = newDate;

		store.add(data);
		store.sync({
			success: function(){
				app.msg(_('sweet'), data.STR + ' ' + _('added'));
			}
		});

	}

});
