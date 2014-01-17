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
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientresultspanel > grid[action=orders]': {
				selectionchange: me.onOrderSelectionChange
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
			}
		});
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
					observationStore = records[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load();
				}else{
					var newResult = resultsStore.add({});
					form.loadRecord(newResult[0]);
					observationStore = newResult[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load({ params: { loinc: orderRecord.data.code } });
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
							document: e.target.result

						};

					File.savePatientBase64Document(params, function(provider, response){
						if(response.result.success){
							values.documentId = 'doc|' + response.result.id;
							me.saveOrderResult(form, values);
						}else{
							app.msg(i18n('oops'), response.result.error)
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
		var record = form.getRecord(),
			sm = this.getOrdersGrid().getSelectionModel(),
			order = sm.getSelection();

		this.getObservationsGrid().editingPlugin.cancelEdit();

		values.result_date = values.result_date == '' ? values.result_date : (values.result_date + ' 00:00:00');
		record.set(values);
		record.save({
			success: function(rec){
				var store = rec.observations(),
					observations = store.data.items;
				for(var i = 0; i < observations.length; i++){
					observations[i].set({result_id: rec.data.id});
				}
				store.sync();

				order[0].set({status: 'Received'});
				order[0].save();

				app.msg(i18n('sweet'), i18n('record_saved'));
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
				win.body.mask(i18n('loading...'));
				HL7Messages.getMessageById(id, function(provider, response){
					me.getMessageField().setValue(response.result.message);
					me.getAcknowledgeField().setValue(response.result.response);
					win.body.unmask();
				});

			}else if(type == 'doc'){
				app.onDocumentView(id);
			}
		}else{
			app.msg(i18n('oops'), i18n('no_document_found'), true)
		}
	},








	/**
	 * OLD ******************* OLD ******************* OLD ******************* OLD
	 * OLD ******************* OLD ******************* OLD ******************* OLD
	 * OLD ******************* OLD ******************* OLD ******************* OLD
	 * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	 */











	onLaboratoryPreviewRender: function(panel){
		var me = this;
		panel.dockedItems.items[0].add({
			xtype: 'button',
			text: i18n('upload'),
			disabled: true,
			action: 'uploadBtn',
			scope: me,
			handler: me.onLabUploadWind
		});
	},

	onLabUploadWind: function(){
		var me = this, previewPanel = me.query('[action="labPreviewPanel"]')[0];
		me.uploadWin.show();
		me.uploadWin.alignTo(previewPanel.el.dom, 'tr-tr', [-5, 35])
	},

	onLabUpload: function(btn){
		var me = this, formPanel = me.uploadWin.down('form'), form = formPanel.getForm(), win = btn.up('window');
		if(form.isValid()){
			formPanel.el.mask(i18n('uploading_laboratory') + '...');
			form.submit({
				//waitMsg: i18n('uploading_laboratory') + '...',
				params: {
					pid: app.patient.pid,
					docType: 'laboratory',
					eid: app.patient.eid
				},
				success: function(fp, o){
					formPanel.el.unmask();
					say(o.result);
					win.close();
					me.getLabDocument(o.result.doc.url);
					me.addNewLabResults(o.result.doc.id);
				},
				failure: function(fp, o){
					formPanel.el.unmask();
					say(o.result);
					win.close();
				}
			});
		}
	},

	onLabResultClick: function(view, model){
		var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm();
		if(me.currDocUrl != model.data.document_url){
			form.reset();
			model.data.data.id = model.data.id;
			form.setValues(model.data.data);
			me.getLabDocument(model.data.document_url);
			me.currDocUrl = model.data.document_url;
		}
	},

	onLabResultsSign: function(){
		var me = this, form = me.query('[action="patientLabs"]')[0].down('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
		if(form.isValid()){
			if(values.id){
				me.passwordVerificationWin(function(btn, password){
					if(btn == 'ok'){
						User.verifyUserPass(password, function(provider, response){
							if(response.result){
								say(record);
								Medical.signPatientLabsResultById(record.data.id, function(provider, response){
									store.load({
										params: {
											parent_id: me.currLabPanelId
										}
									});
								});
							}else{
								Ext.Msg.show({
									title: 'Oops!',
									msg: i18n('incorrect_password'),
									//buttons:Ext.Msg.OKCANCEL,
									buttons: Ext.Msg.OK,
									icon: Ext.Msg.ERROR,
									fn: function(btn){
										if(btn == 'ok'){
											//me.onLabResultsSign();
										}
									}
								});
							}
						});
					}
				});
			}else{
				Ext.Msg.show({
					title: 'Oops!',
					msg: i18n('nothing_to_sign'),
					//buttons:Ext.Msg.OKCANCEL,
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.ERROR,
					fn: function(btn){
						if(btn == 'ok'){
							//me.onLabResultsSign();
						}
					}
				});
			}
		}
	},

	onLabResultsSave: function(btn){
		var me = this, form = btn.up('form').getForm(), dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, values = form.getValues(), record = dataView.getSelectionModel().getLastSelected();
		if(form.isValid()){
			Medical.updatePatientLabsResult(values, function(){
				store.load({params: {parent_id: record.data.parent_id}});
				form.reset();
			});
		}
	},

	addNewLabResults: function(docId){
		var me = this, dataView = me.query('[action="lalboratoryresultsdataview"]')[0], store = dataView.store, params = {
			parent_id: me.currLabPanelId,
			document_id: docId
		};
		Medical.addPatientLabsResult(params, function(provider, response){
			store.load({
				params: {
					parent_id: me.currLabPanelId
				}
			});
		});
	},

	getLabDocument: function(src){
		var panel = this.query('[action="labPreviewPanel"]')[0];
		panel.remove(this.doc);
		panel.add(this.doc = Ext.create('App.ux.ManagedIframe', {
			src: src
		}));
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