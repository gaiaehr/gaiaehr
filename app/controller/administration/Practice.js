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

Ext.define('App.controller.administration.Practice', {
    extend: 'Ext.app.Controller',

	refs: [
		{
			ref:'PracticePanel',
			selector:'practicepanel'
		}
	],

	init: function() {
		var me = this;

		me.control({
			'practicepanel grid':{
				activate: me.onPracticeGridPanelsActive,
			},
			'practicepanel button[toggleGroup=insurance_number_group]':{
				toggle: me.onInsuranceNumberGroupToggle
			},
			'practicepanel toolbar > #addBtn':{
				click: me.onAddBtnClick
			}
		});
	},

	onPracticeGridPanelsActive: function(grid){
		grid.getStore().load();
	},

	onAddBtnClick: function(btn){
		var	grid = btn.up('grid'),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			active: 1
		});
		grid.editingPlugin.startEdit(0, 0);
	},

	onInsuranceNumberGroupToggle:function(btn, pressed){
		var grid = btn.up('grid');

		if(pressed) {
			grid.view.features[0].enable();
			grid.getStore().group(btn.action);
		}else{
			grid.view.features[0].disable();
		}
	}

});
