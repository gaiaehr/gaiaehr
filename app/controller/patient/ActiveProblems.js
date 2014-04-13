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

Ext.define('App.controller.patient.ActiveProblems', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'ActiveProblemsGrid',
			selector: 'patientactiveproblemspanel'
		},
		{
			ref: 'ActiveProblemLiveSearch',
			selector: '#activeProblemLiveSearch'
		},
		{
			ref: 'AddActiveProblemBtn',
			selector: 'patientactiveproblemspanel #addActiveProblemBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientactiveproblemspanel':{
				activate: me.onActiveProblemsGridActive
			},
			'#activeProblemLiveSearch':{
				select: me.onActiveProbleLiveSearchSelect
			},
			'patientactiveproblemspanel #addActiveProblemBtn':{
				click: me.onAddActiveProblemBtnClick
			}
		});
	},


	onAddActiveProblemBtnClick:function(){
		var me = this,
			grid = me.getActiveProblemsGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			created_uid: app.user.id,
			create_date: new Date(),
			begin_date: new Date()
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	onActiveProblemsGridActive:function(grid){
		var store = grid.getStore();

		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onActiveProbleLiveSearchSelect:function(cmb, records){
		var form = cmb.up('form').getForm();

		form.findField('code_text').setValue(records[0].data.code_text);
		form.findField('code_type').setValue(records[0].data.code_type);

	}
});