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
			ref: 'PatientDocumentUploadScanBtn',
			selector: '#patientDocumentUploadWindow #scanBtn'
		},
		{
			ref: 'PatientDocumentUploadFileUploadField',
			selector: '#patientDocumentUploadWindow #fileUploadField'
		},
		{
			ref: 'DocumentHashCheckBtn',
			selector: '#documentHashCheckBtn'
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
		},
		{
			ref: 'PatientDocumentErrorNoteWindow',
			selector: 'patientdocumenterrornotewindow'
		}
	],

	scannedDocument: null,

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				scanconnected: me.onScanConnected,
				scandisconnected: me.onScanDisconnected,
				documentedit: me.onDocumentEdit
			},
			'patientdocumentspanel': {
				activate: me.onPatientDocumentPanelActive,
				beforerender: me.onPatientDocumentBeforeRender
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
			'#patientDocumentUploadWindow': {
				show: me.onPatientDocumentUploadWindowShow
			},
			'#patientDocumentUploadWindow #uploadBtn': {
				click: me.onDocumentUploadSaveBtnClick
			},
			'#patientDocumentUploadWindow #scanBtn': {
				click: me.onDocumentUploadScanBtnClick
			},


			'#DocumentErrorNoteSaveBtn': {
				click: me.onDocumentErrorNoteSaveBtnClick
			}
		});

		me.nav = this.getController('Navigation');
		//this.initDocumentDnD();
	},

	setDocumentInError: function(document_record){
		var me = this;

		Ext.Msg.show({
			title: _('wait'),
			msg: _('document_entered_in_error_message'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					var win = me.showPatientDocumentErrorNoteWindow();
					win.down('form').getForm().loadRecord(document_record);
				}
			}
		});
	},

	onDocumentErrorNoteSaveBtnClick: function(){
		var me = this,
			win = me.getPatientDocumentErrorNoteWindow(),
			form = win.down('form').getForm(),
			values = form.getValues(),
			document_record = form.getRecord(),
			site = document_record.site ? document_record.site : null;

		if(form.isValid()){
			values.entered_in_error = true;
			values.site = site;
			document_record.set(values);
			document_record.save({
				success: function(){
					win.close();
					document_record.set({groupDate:''});
					document_record.commit();
				}
			});
		}
	},

	showPatientDocumentErrorNoteWindow: function(){
		if(!this.getPatientDocumentErrorNoteWindow()){
			Ext.create('App.view.patient.windows.DocumentErrorNote');
		}
		return this.getPatientDocumentErrorNoteWindow().show();
	},

	onPatientDocumentBeforeRender: function(){
		this.setViewerSite(app.user.site);
	},

	onDocumentEdit: function(data){

		var store = this.getPatientDocumentGrid().getStore(),
			record = store.getById(data.save.id);

		if(record){
			var src = data.save.document.split(',');

			record.set({document: (src[1] || src[0])});
			record.save({
				success: function(){
					if(window.dual){
						dual.msg('sweet', _('record_saved'));
					}else{
						app.msg('sweet', _('record_saved'));
					}
				},
				failure: function(){
					if(window.dual){
						dual.msg('oops', _('record_error'), true);
					}else{
						app.msg('oops', _('record_error'), true);
					}
				}
			})
		}
	},

	onScanConnected: function(){
		if(this.getPatientDocumentUploadScanBtn()){
			this.getPatientDocumentUploadScanBtn().show();
		}
	},

	onScanDisconnected: function(){
		if(this.getPatientDocumentUploadScanBtn()){
			this.getPatientDocumentUploadScanBtn().hide();
		}
	},

	onPatientDocumentUploadWindowShow: function(){
		this.scannedDocument = null;
		this.getPatientDocumentUploadFileUploadField().enable();
		this.getPatientDocumentUploadScanBtn().setVisible(this.getController('Scanner').conencted);
	},

	onPatientDocumentGridSelectionChange: function(sm, records){
		var frame = sm.view.panel.up('panel').query('#patientDocumentViewerFrame')[0];

		if(records.length > 0){
			frame.setSrc('dataProvider/DocumentViewer.php?site=' + this.site + '&token=' + app.user.token + '&id=' + records[0].data.id);
		}else{
			frame.setSrc('dataProvider/DocumentViewer.php?site=' + this.site + '&token=' + app.user.token);
		}
	},

	onPatientDocumentPanelActive: function(panel){
		var me = this,
			grid = panel.down('grid'),
			store = grid.getStore(),
			params = me.nav.getExtraParams();

		me.activePAnel = panel;

		if(params && params.document){
			store.on('load', me.doSelectDocument, me);
		}

		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	doSelectDocument: function(store){
		var me = this,
			grid = me.activePAnel.down('grid'),
			params = me.nav.getExtraParams();

		var doc = store.getById(params.document);
		if(doc){
			grid.getSelectionModel().select(doc);

		}else{
			app.msg(_('oops'), _('unable_to_find_document'), true);
		}
		store.un('load', me.doSelectDocument, me);

	},

	onDocumentGroupBtnToggle: function(btn, pressed){
		var grid = btn.up('grid');

		if(pressed){
			grid.getStore().group(btn.action);
			grid.query('#' + btn.action)[0].hide();
			btn.disable();
		}else{
			grid.query('#' + btn.action)[0].show();
			btn.enable();
		}
	},

	onDocumentUploadBtnClick: function(){
		this.setDocumentUploadWindow('click');
	},

	setDocumentUploadWindow: function(action){
		var record = this.getNewPatientDocumentRecord(),
			win = this.getUploadWindow(action);
		win.down('form').getForm().loadRecord(record);
		return win;
	},

	getNewPatientDocumentRecord: function(){
		return Ext.create('App.model.patient.PatientDocuments', {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			date: new Date()
		})
	},

	getGroupName: function(store, record){
		var group = store.groupers.items[0].property;

		if(group == 'docTypeCode'){
			return Ext.String.capitalize(record.get('docTypeCode') + ' - ' + record.get('docType'));
		}else if(group == 'groupDate'){
			return Ext.Date.format(record.get(group), g('date_display_format'));
		}else{
			return Ext.String.capitalize(record.get(group));
		}
	},

	onDocumentHashCheckBtnClick: function(grid, rowIndex){
		var rec = grid.getStore().getAt(rowIndex),
			success,
			message;
		DocumentHandler.checkDocHash(rec.data, function(provider, response){
			success = response.result.success;
			message = '<b>' + _(success ? 'hash_validation_passed' : 'hash_validation_failed') + '</b><br>' + Ext.String.htmlDecode(response.result.msg);

			if(window.dual){
				dual.msg(_(success ? 'sweet' : 'oops'), message, !success)
			}else{
				app.msg(_(success ? 'sweet' : 'oops'), message, !success)
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
			win = me.getPatientDocumentUploadWindow(),
			form = win.down('form').getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			reader = new FileReader(),
			uploadField = form.findField('document');

		if(!form.isValid()) return;

		record.set(values);

		if(win.action == 'click'){
			var uploadValue = uploadField.getValue();
			record.set({name: uploadValue});

			if(me.scannedDocument){
				record.set({document: me.scannedDocument});
				me.doNewDocumentRecordSave(record);
			}else{
				reader.onload = function(e){
					record.set({document: e.target.result});
					me.doNewDocumentRecordSave(record);
				};
				reader.readAsDataURL(uploadField.extractFileInput().files[0]);
			}
		}else{
			me.doNewDocumentRecordSave(record);
		}
	},

	onDocumentUploadScanBtnClick: function(){
		var me = this,
			scanCtrl = this.getController('Scanner');

		scanCtrl.initScan();
		app.on('scancompleted', this.onScanCompleted, me);
	},

	onScanCompleted: function(controller, document){
		var me = this,
			win = me.getPatientDocumentUploadWindow(),
			form = win.down('form').getForm(),
			uploadField = form.findField('document');

		uploadField.disable();

		me.scannedDocument = document;
		app.un('scancompleted', this.onScanCompleted, me);
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
				app.msg(_('sweet'), _('document_added'));
				me.getPatientDocumentUploadWindow().close();
				me.getPatientDocumentGrid().getSelectionModel().select(record);

			},
			failure: function(){
				store.rejectChanges();
				if(window.dual){
					dual.msg(_('oops'), _('document_error'), true);
				}else{
					app.msg(_('oops'), _('document_error'), true);
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
			if(me.dropMask && (e.target == me.dropMask.maskEl.dom || e.target == me.dropMask.msgEl.dom)){
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

	setDropMask: function(){
		var me = this,
			dropPanel = me.getPatientDocumentViewerFrame();

		me.dnding = true;

		if(dropPanel && dropPanel.rendered){
			if(!me.dropMask){
				me.dropMask = new Ext.LoadMask(me.getPatientDocumentViewerFrame(), {
					msg: _('drop_here'),
					cls: 'uploadmask',
					maskCls: 'x-mask uploadmask',
					shadow: false
				});
				me.dropMask.show();

				me.dropMask.maskEl.dom.addEventListener('dragenter', function(e){
					e.preventDefault();
					e.target.classList.add('validdrop');
					return false;
				});

				me.dropMask.maskEl.dom.addEventListener('dragleave', function(e){
					e.preventDefault();
					e.target.classList.remove('validdrop');
					return false;
				});
			}else{
				me.dropMask.show();
			}

		}
	},

	unSetDropMask: function(){
		this.dnding = false;
		if(this.dropMask){
			this.dropMask.hide();
		}
	},

	dropHandler: function(files){
		//		say(files);
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
	},

	setViewerSite: function(site){
		this.site = site;
	}
});