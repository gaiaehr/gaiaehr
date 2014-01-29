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
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientsocialhistorypanel': {
				show: me.onSocialHistoryShow
			},
			'button[action=socialHistoryAddBtn]': {
				click: me.onAddBtnClicked
			},
			'combobox[action=socialHistoryTypeCombo]': {
				select: me.onHistoryTypeComboSelectionChanged
			}
		});
	},

	onAddBtnClicked: function(){
		var record = this.getSocialHistoryTypeCombo().lastSelection[0],
			plugin = this.getSocialHistoryGrid().editingPlugin,
			addedRecs;

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

	onHistoryTypeComboSelectionChanged: function(){
		this.getSocialHistoryAddBtn().enable();
	},

	onSocialHistoryShow: function(grid){
		grid.getStore().load({
			filters: [
				{
					property: 'pid',
					value: app.patient.pid
				}
			]
		});
	}

});