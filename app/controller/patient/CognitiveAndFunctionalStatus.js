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

Ext.define('App.controller.patient.CognitiveAndFunctionalStatus', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'CognitiveAndFunctionalStatusPanel',
			selector: 'patientcognitiveandfunctionalstatuspanel'
		},
		{
			ref: 'NewFunctionalStatusBtn',
			selector: '#newFunctionalStatusBtn'
		}

	],

	init: function(){
		var me = this;
		me.control({
			'patientcognitiveandfunctionalstatuspanel': {
				activate: me.onCognitiveAndFunctionalStatusPanelActive
			},
			'#newFunctionalStatusBtn': {
				click: me.onNewFunctionalStatusBtnClick
			},
			'#functionalStatusCategoryCombo': {
				select: me.onFunctionalStatusCategoryComboSelect
			},
			'#functionalStatusSatausCombo': {
				select: me.onFunctionalStatusStatusComboSelect
			},
			'#functionalStatusCodeCombo': {
				select: me.onFunctionalStatusCodeComboSelect
			}
		});
	},

	onCognitiveAndFunctionalStatusPanelActive: function(grid){
		var store = grid.getStore();

		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onNewFunctionalStatusBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.getStore(),
			records;
		grid.editingPlugin.cancelEdit();
		records = store.add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			begin_date: new Date(),
			created_date: new Date()
		});
		grid.editingPlugin.startEdit(records[0], 0);
	},

	onFunctionalStatusCategoryComboSelect: function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			category_code: records[0].data.code,
			category_code_type: records[0].data.code_type
		});
	},

	onFunctionalStatusStatusComboSelect: function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			status_code: records[0].data.code,
			status_code_type: records[0].data.code_type
		});
	},

	onFunctionalStatusCodeComboSelect: function(cmb, records){
		var record = cmb.up('form').getForm().getRecord();

		record.set({
			code: records[0].data.ConceptId,
			code_type: records[0].data.CodeType
		});
	}

});