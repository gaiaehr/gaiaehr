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

Ext.define('App.controller.patient.AdvanceDirectives', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'AdvanceDirectiveGridPanel',
			selector: 'patientadvancedirectivepanel'
		},
		{
			ref: 'AdvanceDirectiveAddBtn',
			selector: '#AdvanceDirectiveAddBtn'
		},
		{
			ref: 'AdvanceDirectiveReviewBtn',
			selector: '#AdvanceDirectiveReviewBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientadvancedirectivepanel':{
				activate: me.onAdvanceDirectiveGridPanelActivate
			},
			'#AdvanceDirectiveAddBtn':{
				click: me.onAdvanceDirectiveAddBtnClick
			},
			'#AdvanceDirectiveReviewBtn':{
				click: me.onAdvanceDirectiveReviewBtnClick
			}
		});
	},

	onAdvanceDirectiveGridPanelActivate: function(grid){
		var store = grid.getStore();
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	onAdvanceDirectiveAddBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		var records = store.insert(0, {
			pid: app.patient.pid,
			eid: app.patient.eid,
			create_date: new Date(),
			created_uid: app.user.id,
			start_date: new Date(),
			verified_date: new Date(),
			verified_uid: app.user.id
		});
		grid.editingPlugin.startEdit(records[0], 0);
	},

	onAdvanceDirectiveReviewBtnClick: function(btn){

	}

});