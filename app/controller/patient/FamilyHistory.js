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

Ext.define('App.controller.patient.FamilyHistory', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'FamilyHistoryWindow',
			selector: 'familyhistorywindow'
		},
		{
			ref: 'FamilyHistoryGrid',
			selector: 'patientfamilyhistorypanel'
		},
		{
			ref: 'FamilyHistoryForm',
			selector: '#FamilyHistoryForm'
		},
		{
			ref: 'FamilyHistorySaveBtn',
			selector: '#familyHistorySaveBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientfamilyhistorypanel': {
				activate: me.onFamilyHistoryGridActivate
			},
			'#FamilyHistoryGridAddBtn': {
				click: me.onFamilyHistoryGridAddBtnClick
			},
			'#FamilyHistoryWindowSaveBtn': {
				click: me.onFamilyHistoryWindowSaveBtnClick
			},
			'#FamilyHistoryWindowCancelBtn': {
				click: me.onFamilyHistoryWindowCancelBtnClick
			}
		});
	},

	onFamilyHistoryGridActivate: function(grid){
		var store = grid.getStore();

		store.clearFilter(true);
		store.load({
			filters: [
				{
					property: 'pid',
					value: app.patient.pid
				}
			]
		});
	},

	onFamilyHistoryGridAddBtnClick:function(){
		this.showFamilyHistoryWindow();
		this.getFamilyHistoryForm().getForm().reset();
	},

	showFamilyHistoryWindow: function(){
		if(!this.getFamilyHistoryWindow()){
			Ext.create('App.view.patient.windows.FamilyHistory');
		}
		this.getFamilyHistoryWindow().show();
	},

	onFamilyHistoryWindowSaveBtnClick:function(){
		var grid = this.getFamilyHistoryGrid(),
			form = this.getFamilyHistoryForm().getForm(),
			store = grid.getStore(),
			values = form.getValues(),
			histories = [],
			isValid =  true,
            foo;

		Ext.Object.each(values, function(key, value){

			if(value == '0~0') return;

			foo = value.split('~'),
				condition = foo[0].split(':'),
				relation = foo[1].split(':');

			if(isValid && relation[0] == '0'){
				isValid = false;
			}

			Ext.Array.push(histories, {
				pid: app.patient.pid,
				eid: app.patient.eid,
				relation: relation[2],
				relation_code: relation[1],
				relation_code_type: relation[0],
				condition: condition[2],
				condition_code: condition[1],
				condition_code_type: condition[0],
				create_uid: app.user.id,
				create_date: new Date()
			});
		});

		if(histories.length == 0){
			app.msg(_('oops'), _('no_history_selected'), true);
			return;
		}

		if(!isValid){
			app.msg(_('oops'), _('missing_required_information'), true);
			return;
		}

		store.add(histories);
		store.sync();
		this.getFamilyHistoryWindow().close();

	},

	onFamilyHistoryWindowCancelBtnClick:function(){
		this.getFamilyHistoryWindow().close();
	}

});
