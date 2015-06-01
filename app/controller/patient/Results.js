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

Ext.define('App.controller.patient.Results', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.administration.HL7MessageViewer'
	],
	refs: [
		{
			ref: 'resultsPanel',
			selector: 'patientresultspanel'
		},
		{
			ref: 'resultForm',
			selector: 'patientresultspanel > form'
		},
		{
			ref: 'observationsGrid',
			selector: 'patientresultspanel > form > grid[action=observations]'
		},
		{
			ref: 'ordersGrid',
			selector: 'patientresultspanel > grid[action=orders]'
		},
		{
			ref: 'uploadField',
			selector: 'filefield[action=orderresultuploadfield]'
		},
		{
			ref: 'messageField',
			selector: 'hl7messageviewer > textareafield[action=message]'
		},
		{
			ref: 'acknowledgeField',
			selector: 'hl7messageviewer > textareafield[action=acknowledge]'
		},
		{
			ref: 'OrderResultSignBtn',
			selector: '#OrderResultSignBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientresultspanel': {
				activate: me.onResultPanelActive
			},
			'patientresultspanel > grid[action=orders]': {
				selectionchange: me.onOrderSelectionChange,
				edit: me.onOrderSelectionEdit
			},
			'filefield[action=orderresultuploadfield]': {
				change: me.onOrderDocumentChange
			},
			'button[action=orderResultResetBtn]': {
				click: me.onResetOrderResultClicked
			},
			'button[action=orderResultSaveBtn]': {
				click: me.onSaveOrderResultClicked
			},
			'button[action=orderDocumentViewBtn]': {
				click: me.onOrderDocumentViewBtnClicked
			},
			'#OrderResultNewOrderBtn': {
				click: me.onOrderResultNewOrderBtnClick
			},
			'#OrderResultSignBtn': {
				click: me.onOrderResultSignBtnClick
			}
		});
	},

	onOrderResultSignBtnClick: function(){
		var me = this;

		app.passwordVerificationWin(function(btn, password){
			if(btn == 'ok'){

				User.verifyUserPass(password, function(success){
					if(success){
						var form = me.getResultForm().getForm(),
							record = form.getRecord();

						say(record);

						record.set({signed_uid: app.user.id});
						record.save({
							success: function(){
								app.msg(_('sweet'), _('result_signed'));
							},
							failure: function(){
								app.msg(_('sweet'), _('record_error'), true);
							}
						});

					}else{
						me.onOrderResultSignBtnClick();
					}

				});

			}
		});

	},

	onOrderSelectionEdit: function(editor, e){
		say(e.record);
		this.getOrderResult(e.record);
	},

	onOrderResultNewOrderBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.getStore(),
			records;

		grid.editingPlugin.cancelEdit();
		records = store.add({
			pid: app.patient.pid,
			uid: app.user.id,
			order_type: 'lab',
			status: 'Pending'
		});
		grid.editingPlugin.startEdit(records[0], 0);
	},

	onResultPanelActive: function(){
		this.setResultPanel();
	},

	setResultPanel: function(){
		var me = this,
			ordersStore = me.getOrdersGrid().getStore();

		if(app.patient){
			ordersStore.clearFilter(true);
			ordersStore.filter([
				{
					property: 'pid',
					value: app.patient.pid
				}
			]);
		}else{
			ordersStore.clearFilter(true);
			ordersStore.load();
		}
	},

	onOrderSelectionChange: function(model, records){
		if(records.length > 0){
			this.getOrderResult(records[0]);
		}else{
			this.resetOrderResultForm();
		}
	},

	getOrderResult: function(orderRecord){

		var me = this,
			form = me.getResultForm().getForm(),
			resultsStore = orderRecord.results(),
			observationGrid = me.getObservationsGrid(),
			observationStore;

		observationGrid.editingPlugin.cancelEdit();
		resultsStore.load({
			callback: function(records){

				if(records.length > 0){
					form.loadRecord(records[0]);
					me.getOrderResultSignBtn().setDisabled(records[0].data.signed_uid > 0);
					observationStore = records[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load();

				}else{
					var newResult = resultsStore.add({
						pid: orderRecord.data.pid,
						code: orderRecord.data.code,
						code_text: orderRecord.data.description,
						code_type: orderRecord.data.code_type,
						ordered_uid: orderRecord.data.uid,
						create_date: new Date()
					});
					form.loadRecord(newResult[0]);
					me.getOrderResultSignBtn().setDisabled(true);
					observationStore = newResult[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load({
						params: {
							loinc: orderRecord.data.code
						},
						callback: function(ObsRecords){
							for(var i = 0; i < ObsRecords.length; i++){
								ObsRecords[i].phantom = true;
							}
						}
					});
				}
			}
		});
	},

	onResetOrderResultClicked: function(){
		this.resetOrderResultForm();
	},

	resetOrderResultForm: function(){
		var me = this,
			form = me.getResultForm().getForm(),
			observationGrid = me.getObservationsGrid(),
			store = Ext.create('App.store.patient.PatientsOrderObservations');

		form.reset();
		observationGrid.editingPlugin.cancelEdit();
		observationGrid.reconfigure(store);
	},

	onSaveOrderResultClicked: function(){
		var me = this,
			form = me.getResultForm().getForm(),
			values = form.getValues(),
			files = me.getUploadField().getEl().down('input[type=file]').dom.files,
			reader = new FileReader();

		if(!form.isValid()){
			app.msg(_('oops'), _('required_fields_missing'), true);
			return;
		}

		if(files.length > 0){

			reader.onload = (function(){
				return function(e){

					var sm = me.getOrdersGrid().getSelectionModel(),
						order = sm.getSelection(),
						params = {
							pid: order[0].data.pid,
							eid: order[0].data.eid,
							uid: app.user.id,
							docType: 'lab',
							title: 'Lab #' + values.lab_order_id + ' Result',
							document: e.target.result

						};

					File.savePatientBase64Document(params, function(provider, response){
						if(response.result.success){
							values.documentId = 'doc|' + response.result.id;
							me.saveOrderResult(form, values);
						}else{
							app.msg(_('oops'), response.result.error)
						}
					});

				};
			})(files[0]);

			reader.readAsDataURL(files[0]);

		}else{
			me.saveOrderResult(form, values);
		}

	},

	saveOrderResult: function(form, values){
		var me = this,
			record = form.getRecord(),
			sm = me.getOrdersGrid().getSelectionModel(),
			order = sm.getSelection(),
			observationData = [];

		//		me.getObservationsGrid().editingPlugin.cancelEdit();

		var observationStore = record.observations(),
			observations = observationStore.data.items;


		record.set(values);

		say('saveOrderResult');
		//say(form);
		//say(values);
		//say(record);
		//say(order[0]);



		record.save({
			success: function(rec){

				say(rec.data.id);
				say(rec);
				say(observationStore.data.items);
				say(observationStore.data.items);

				for(var i = 0; i < observations.length; i++){
					observations[i].set({result_id: rec.data.id});
				}

				observationStore.sync({
					callback:function(batch, options){

						//say(batch);
						//say(options);

						//for(var i = 0; i < observations.length; i++){
						//	observations[i].set({ result_id: rec.data.id });
						//}
						//store.load({
						//	filters: [
						//		{
						//			property: 'result_id',
						//			value: rec.data.id
						//		}
						//	]
						//});
					}
				});

				order[0].set({status: 'Received'});
				order[0].save();

				app.msg(_('sweet'), _('record_saved'));
			}
		});
	},

	onOrderDocumentViewBtnClicked: function(){
		var me = this,
			form = me.getResultForm().getForm(),
			record = form.getRecord(),
			foo = record.data.documentId.split('|'),
			type = null,
			id = null,
			win;

		if(foo[0]) type = foo[0];
		if(foo[1]) id = foo[1];

		if(type && id){
			if(type == 'hl7'){
				win = Ext.widget('hl7messageviewer').show();
				win.body.mask(_('loading...'));
				HL7Messages.getMessageById(id, function(provider, response){
					me.getMessageField().setValue(response.result.message);
					me.getAcknowledgeField().setValue(response.result.response);
					win.body.unmask();
				});

			}else if(type == 'doc'){
				app.onDocumentView(id);
			}
		}else{
			app.msg(_('oops'), _('no_document_found'), true)
		}
	},

	onOrderDocumentChange: function(field){

		//		say(field);
		//		say(document.getElementById(field.inputEl.id).files[0]);
		//		say(field.inputEl);
		//
		//		var fr = new FileReader();
		//
		//
		//		fr.onload = function(e) {
		//			say(e.target.result);
		//		};
		//
		//		fr.readAsDataURL( field.value );

	}

});