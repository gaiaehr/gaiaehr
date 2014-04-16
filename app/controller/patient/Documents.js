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

Ext.define('App.controller.patient.Documents', {
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.patient.windows.UploadDocument'
	],
	refs: [
		{
			ref: 'PatientDocumentPanel',
			selector: 'patientdocumentspanel'
		},
		{
			ref: 'PatientDocumentGrid',
			selector: 'patientdocumentspanel #patientDocumentGrid'
		},
		{
			ref: 'PatientDocumentViewerFrame',
			selector: 'patientdocumentspanel #patientDocumentViewerFrame'
		},
		{
			ref: 'PatientDocumentUploadWindow',
			selector: '#patientDocumentUploadWindow'
		},
		{
			ref: 'DocumentHashCheckBtn',
			selector: '#documentHashCheckBtn'
		},
		{
			ref: 'AddDocumentBtn',
			selector: 'patientdocumentspanel #addDocumentBtn'
		},
		{
			ref: 'DocumentUploadBtn',
			selector: 'patientdocumentspanel #documentUploadBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientdocumentspanel': {
				activate: me.onPatientDocumentPanelActive
			},
			'patientdocumentspanel #patientDocumentGrid': {
				selectionchange: me.onPatientDocumentGridSelectionChange
			},
			'patientdocumentspanel [toggleGroup=documentgridgroup]': {
				toggle: me.onDocumentGroupBtnToggle
			},
			'patientdocumentspanel #documentGroupBtn': {
				toggle: me.onDocumentGroupBtnToggle
			},
			'patientdocumentspanel #documentUploadBtn': {
				click: me.onDocumentUploadBtnClick
			},
			'#patientDocumentUploadWindow #uploadBtn': {
				click: me.onDocumentUploadSaveBtnClick
			}
		});
	},

	onPatientDocumentGridSelectionChange: function(grid, records){
		var frame = this.getPatientDocumentViewerFrame();

		if(records.length > 0){
			frame.setSrc('dataProvider/DocumentViewer.php?id=' + records[0].data.id);
		}else{
			frame.setSrc('dataProvider/DocumentViewer.php');
		}
	},

	onPatientDocumentPanelActive: function(){
		var store = this.getPatientDocumentGrid().getStore();
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onDocumentGroupBtnToggle: function(btn, pressed){
		if(pressed) {
			this.getPatientDocumentGrid().view.features[0].enable();
			this.getPatientDocumentGrid().getStore().group(btn.action);
		}else{
			this.getPatientDocumentGrid().view.features[0].disable();
		}
	},

	onDocumentUploadBtnClick: function(btn){
		var record = Ext.create('App.model.patient.PatientDocuments',{
				pid: app.patient.pid,
				eid: app.patient.eid,
				uid: app.user.id,
				date: new Date()
			}),
			win = this.getUploadWindow();

		win.down('form').getForm().loadRecord(record);


	},

	getGroupName: function(name){
		return Ext.String.capitalize(name);
	},

	onDocumentHashCheckBtnClick: function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			success,
			message;
		DocumentHandler.checkDocHash(rec.data, function(provider, response){
			success = response.result.success;
			message = i18n(success ? 'hash_validation_passed' : 'hash_validation_failed') + '<br>' + response.result.msg;

			if(dual){
				dual.msg(i18n(success ? 'sweet' : 'oops'), message, !success)
			}else{
				app.msg(i18n(success ? 'sweet' : 'oops'), message, !success)
			};
		});
	},

	getUploadWindow:function(){
		return Ext.widget('patientuploaddocumentwindow', {
			itemId: 'patientDocumentUploadWindow'
		})
	},

	onDocumentUploadSaveBtnClick: function(){
		var me = this,
			form = me.getPatientDocumentUploadWindow().down('form').getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			reader = new FileReader(),
			uploadField = form.findField('document');

		if(!form.isValid()) return;

		record.set(values);
		record.set({name: uploadField.getValue()});

		reader.onload = function(e){
			record.set({document: e.target.result});
			me.doNewDocumentRecordSave(record);
		};

		reader.readAsDataURL(uploadField.extractFileInput().files[0]);
	},

	doNewDocumentRecordSave:function(record){
		var me = this,
			store = me.getPatientDocumentGrid().getStore(),
			index = store.indexOf(record);

		if(index == -1){
			store.add(record);
		}

		store.sync({
			success:function(){
				app.msg(i18n('sweet'), i18n('document_added'));
				me.getPatientDocumentUploadWindow().close();
				me.getPatientDocumentGrid().getSelectionModel().select(record);

			},
			failure:function(){
				store.rejectChanges();
				if(dual){
					dual.msg(i18n('oops'), i18n('document_error'), true);
				}else{
					app.msg(i18n('oops'), i18n('document_error'), true);
				}

			}
		})

	}
});