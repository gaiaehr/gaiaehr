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

		this.initDocumentDnD();
	},

	onPatientDocumentGridSelectionChange: function(grid, records){
		var frame = this.getPatientDocumentViewerFrame();

		if(records.length > 0){
			frame.setSrc('dataProvider/DocumentViewer.php?site=' + site + '&id=' + records[0].data.id);
		}else{
			frame.setSrc('dataProvider/DocumentViewer.php?site=' + site);
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
		if(pressed){
			this.getPatientDocumentGrid().view.features[0].enable();
			this.getPatientDocumentGrid().getStore().group(btn.action);
		}else{
			this.getPatientDocumentGrid().view.features[0].disable();
		}
	},

	onDocumentUploadBtnClick: function(){
		this.setDocumentUploadWindow('click');
	},

	setDocumentUploadWindow:function(action){
		var record = this.getNewPatientDocumentRecord(),
			win = this.getUploadWindow(action);
		win.down('form').getForm().loadRecord(record);
		return win;
	},

	getNewPatientDocumentRecord:function(){
		return Ext.create('App.model.patient.PatientDocuments', {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			date: new Date()
		})
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

			if(window.dual){
				dual.msg(i18n(success ? 'sweet' : 'oops'), message, !success)
			}else{
				app.msg(i18n(success ? 'sweet' : 'oops'), message, !success)
			}
		});
	},

	getUploadWindow: function(action){
		return Ext.widget('patientuploaddocumentwindow', {
			action: action,
			itemId: 'patientDocumentUploadWindow'
		})
	},

	onDocumentUploadSaveBtnClick: function(){
		var me = this,
			formPanel = me.getPatientDocumentUploadWindow().down('form'),
			form = formPanel.getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			reader = new FileReader(),
			uploadField = form.findField('document');

		if(!form.isValid()) return;

		record.set(values);

		if(formPanel.action == 'click'){
			record.set({name: uploadField.getValue()});
			reader.onload = function(e){
				record.set({document: e.target.result});
				me.doNewDocumentRecordSave(record);
			};
			reader.readAsDataURL(uploadField.extractFileInput().files[0]);
		}else{
			me.doNewDocumentRecordSave(record);
		}

	},

	doNewDocumentRecordSave: function(record){
		var me = this,
			store = me.getPatientDocumentGrid().getStore(),
			index = store.indexOf(record);

		if(index == -1){
			store.add(record);
		}

		store.sync({
			success: function(){
				app.msg(i18n('sweet'), i18n('document_added'));
				me.getPatientDocumentUploadWindow().close();
				me.getPatientDocumentGrid().getSelectionModel().select(record);

			},
			failure: function(){
				store.rejectChanges();
				if(window.dual){
					dual.msg(i18n('oops'), i18n('document_error'), true);
				}else{
					app.msg(i18n('oops'), i18n('document_error'), true);
				}

			}
		})
	},

	initDocumentDnD: function(){
		var me = this;

		me.dnding = false;

		document.ondragenter = function(e){
			e.preventDefault();
			if(!me.dnding) me.setDropMask();
			return false;
		};

		document.ondragover = function(e){
			e.preventDefault();
			return false;
		};

		document.ondrop = function(e){
			e.preventDefault();
			me.unSetDropMask();
			if(me.dropMask && (e.target == me.dropMask.maskEl.dom  || e.target == me.dropMask.msgEl.dom)){
				me.dropHandler(e.dataTransfer.files);
			}
			return false;
		};

		document.ondragleave = function(e){
			if(e.target.localName == 'body') me.unSetDropMask();
			e.preventDefault();
			return false;
		};
	},


	setDropMask:function(){
		var me = this,
			dropPanel = me.getPatientDocumentViewerFrame();

		me.dnding = true;

		if(dropPanel && dropPanel.rendered){
			if(!me.dropMask){
				me.dropMask = new Ext.LoadMask(me.getPatientDocumentViewerFrame(), {
					msg: i18n('drop_here'),
					cls: 'uploadmask',
					maskCls: 'x-mask uploadmask',
					shadow: false
				});
				me.dropMask.show();

				me.dropMask.maskEl.dom.addEventListener('dragenter', function(e) {
					e.preventDefault();
					e.target.classList.add('validdrop');
					return false;
				});

				me.dropMask.maskEl.dom.addEventListener('dragleave', function(e) {
					e.preventDefault();
					e.target.classList.remove('validdrop');
					return false;
				});
  			}else{
				me.dropMask.show();
			}

		}
	},

	unSetDropMask:function(){
		this.dnding = false;
		if(this.dropMask){
			this.dropMask.hide();
		}
	},

	dropHandler:function(files){
		say(files);
		var me = this,
			win = me.setDocumentUploadWindow('drop'),
			form = win.down('form').getForm(),
			record = form.getRecord(),
			reader = new FileReader(),
			uploadField = form.findField('document');

		uploadField.hide();
		uploadField.disable();

		reader.onload = function(e){
			record.set({
				document: e.target.result,
				name: files[0].name
			});
		};

		reader.readAsDataURL(files[0]);
	}
});