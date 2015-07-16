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

Ext.define('App.controller.patient.SocialHistory', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'SocialHistoryGrid',
			selector: 'patientsocialhistorypanel'
		},
		{
			ref: 'SocialHistoryTypeCombo',
			selector: 'combobox[action=socialHistoryTypeCombo]'
		},
		{
			ref: 'SocialHistoryAddBtn',
			selector: 'button[action=socialHistoryAddBtn]'
		},
		{
			ref: 'ObservationColumn',
			selector: '#socialhistorypanelobservationcolumn'
		},
		{
			ref: 'SmokingStatusCombo',
			selector: '#socialsmokingstatuscombo'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientsocialhistorypanel': {
				activate: me.onSocialHistoryActive,
				beforeedit: me.onSocialHistoryBeforeEdit
			},
			'button[action=socialHistoryAddBtn]': {
				click: me.onAddBtnClicked
			},
			'combobox[action=socialHistoryTypeCombo]': {
				select: me.onHistoryTypeComboSelectionChanged
			},
			'#socialsistoryobservationcombo': {
				select: me.onHistoryObservationComboSelect
			},
			'#socialsmokingstatuscombo': {
				select: me.onSmokingStatusComboSelect
			},
			'#reviewsmokingstatuscombo': {
				select: me.onSmokingStatusComboSelect
			}
		});

		me.smokeStatusStore = Ext.create('App.store.patient.SmokeStatus',{
			pageSize: 1000,
			listeners:{
				scope: me,
				load: me.onSmokeStatusStoreLoad
			}
		});
	},

	onSmokeStatusStoreLoad: function(store, records){
		if(store.last() && this.getSmokingStatusCombo()){
			this.getSmokingStatusCombo().setValue(store.last().data.status);
		}
	},

	onSmokingStatusComboSelect: function(cmb, records){
		this.smokeStatusStore.add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			status: records[0].data.option_name,
			status_code: records[0].data.code,
			status_code_type: records[0].data.code_type,
			create_uid: app.user.id,
			create_date: new Date()
		});

		this.smokeStatusStore.sync({
			success:function(){
				if(window.dual){
					dual.msg(i18n('sweet'), i18n('record_updated'));
				}else{
					app.msg(i18n('sweet'), i18n('record_updated'));
				}
			},
			failure:function(){
				if(window.dual){
					dual.msg(i18n('oops'), i18n('record_error'), true);
				}else{
					app.msg(i18n('oops'), i18n('record_error'), true);
				}
			}
		});
	},

	onAddBtnClicked: function(){
		var record = this.getSocialHistoryTypeCombo().lastSelection[0],
			plugin = this.getSocialHistoryGrid().editingPlugin,
			addedRecs;

		if(!this.getSocialHistoryTypeCombo().isValid()) return;

		plugin.cancelEdit();
		addedRecs = this.getSocialHistoryGrid().getStore().add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			create_uid: app.user.id,
			update_uid: app.user.id,
			create_date: new Date(),
			update_date: new Date(),
			category_code: record.data.code,
			category_code_type: record.data.code_type,
			category_code_text: record.data.option_name
		});

		plugin.startEdit(addedRecs[0], 0);
	},

	onSocialHistoryBeforeEdit: function(plugin, e){
		var column = this.getObservationColumn(),
			editor;

		if(e.record.data.category_code == '229819007'){ // tobacco use
			editor = {
				xtype: 'gaiaehr.combo',
				valueField: 'option_name',
				itemId: 'socialsistoryobservationcombo',
				list: 106
			};
		}else if(e.record.data.category_code == '160573003'){ // alcohol intake
			editor = {
				xtype: 'gaiaehr.combo',
				valueField: 'option_name',
				itemId: 'socialsistoryobservationcombo',
				list: 105
			};
		}else if(e.record.data.category_code == '256235009'){ // exercise
			editor = {
				xtype: 'gaiaehr.combo',
				valueField: 'option_name',
				itemId: 'socialsistoryobservationcombo',
				list: 107
			};
		}else if(e.record.data.category_code == '363908000'){ // drug abuse
			editor = {
				xtype: 'gaiaehr.combo',
				valueField: 'option_name',
				itemId: 'socialsistoryobservationcombo',
				list: 108
			};
		}else{
			editor = {
				xtype: 'textfield'
			};
		}

		editor._marginWidth = 2;
		column.setEditor(editor);
		plugin.editor.onColumnResize(column);

	},

	onHistoryObservationComboSelect: function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			observation_code: records[0].data.code,
			observation_code_type: records[0].data.code_type
		});

	},

	onHistoryTypeComboSelectionChanged: function(){
		this.getSocialHistoryAddBtn().enable();
	},

	loadSmokeStore:function(){
		this.smokeStatusStore.clearFilter(true);
		this.smokeStatusStore.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	loadHistoryStore:function(grid){
		var store = grid.getStore();
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onSocialHistoryActive: function(grid){
		this.loadHistoryStore(grid);
		this.loadSmokeStore();
		this.getSmokingStatusCombo().reset();

	}

});