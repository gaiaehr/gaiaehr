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

Ext.define('App.controller.patient.DoctorsNotes', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'DoctorsNotesGrid',
			selector: 'patientdoctorsnotepanel'
		},
		{
			ref: 'PrintDoctorsNoteBtn',
			selector: '#printDoctorsNoteBtn'
		},
		{
			ref: 'NewDoctorsNoteBtn',
			selector: '#newDoctorsNoteBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientdoctorsnotepanel': {
				activate: me.onDoctorsNotesGridActive,
				selectionchange: me.onDoctorsNotesGridSelectionChange,
				beforeedit: me.onDoctorsNotesGridBeforeEdit,
				validateedit: me.onDoctorsNotesGridValidateEdit
			},
			'#printDoctorsNoteBtn': {
				click: me.onPrintDoctorsNoteBtn
			},
			'#newDoctorsNoteBtn': {
				click: me.onNewDoctorsNoteBtn
			}
		});

		me.docTemplates = {};

		/** get the document templates data of grid renderer **/
		CombosData.getTemplatesTypes(function(provider, response){
			for(var i=0; i < response.result.length; i++){
				if(response.result[i].id) me.docTemplates[response.result[i].id] = response.result[i].title;
			}
		});

	},

	onDoctorsNotesGridSelectionChange:function(sm, selected){
		this.getPrintDoctorsNoteBtn().setDisabled(selected.length == 0);
	},

	onNewDoctorsNoteBtn:function(btn){
		var grid = btn.up('grid');

		grid.editingPlugin.cancelEdit();
		grid.getStore().insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			refill: 0,
			order_date: new Date(),
			from_date: new Date()
		});
		grid.editingPlugin.startEdit(0, 0);
	},


	onDoctorsNotesGridValidateEdit:function(plugin, e){
		var multiField = plugin.editor.query('multitextfield')[0],
			values = multiField.getValue();

		e.record.set({restrictions: values});

//		e.record.store.sync();
	},


	onDoctorsNotesGridBeforeEdit:function(plugin, e){
		var multiField = plugin.editor.query('multitextfield')[0],
			data = e.record.data.restrictions;
		multiField.setValue(data);

	},


	onPrintDoctorsNoteBtn:function(){
		var me = this,
			grid = me.getDoctorsNotesGrid(),
			record = grid.getSelectionModel().getSelection()[0],
			params = {};

		params.pid = record.pid;
		params.eid = record.eid;
		params.docType = 'Doctors Note';
		params.templateId = record.template_id;
		params.docNoteid = record.id;

		DocumentHandler.createTempDocument(params, function(provider, response){
			if(dual){
				dual.onDocumentView(response.result.id, 'Doctors Note');
			}else{
				app.onDocumentView(response.result.id, 'Doctors Note');
			}
		});
	},

	onDoctorsNotesGridActive:function(grid){
		var store = grid.getStore();

		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	templatesRenderer:function(v){
		return this.docTemplates[v];
	}

});