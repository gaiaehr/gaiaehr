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

Ext.define('App.controller.administration.Users', {
	extend: 'Ext.app.Controller',

	refs: [
		{
			ref: 'AdminUsersPanel',
			selector: '#AdminUsersPanel'
		},
		{
			ref: 'AdminUserGridPanel',
			selector: '#AdminUserGridPanel'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'#AdminUserGridPanel': {
				beforeedit: me.onAdminUserGridPanelBeforeEdit
			},
			'#UserGridEditFormProviderCredentializationActiveBtn': {
				click: me.onUserGridEditFormProviderCredentializationActiveBtnClick
			},
			'#UserGridEditFormProviderCredentializationInactiveBtn': {
				click: me.onUserGridEditFormProviderCredentializationInactiveBtnClick
			}
		});

	},

	onAdminUserGridPanelBeforeEdit: function(plugin, context){
		var grid = plugin.editor.down('grid'),
			store = grid.getStore(),
			filters = [
				{
					property: 'provider_id',
					value: context.record.data.id
				}
			],
			params = {};

		store.clearFilter(true);
		if(context.record.data.id > 0 && context.record.data.npi != ''){
			params = {
				providerId: context.record.data.id,
				fullList: true
			};
			Ext.Array.push(filters, {
				property: 'provider_id',
				value: null
			});

		}

		store.load({
			filters: filters,
			params: params
		});
	},

	onUserGridEditFormProviderCredentializationActiveBtnClick: function(btn){
		var store = btn.up('grid').getStore(),
			records = store.data.items,
			now = Ext.Date.format(new Date(), 'Y-m-d');

		for(var i = 0; i < records.length; i++){
			records[i].set({
				start_date: now,
				end_date: '9999-12-31',
				active: true
			});
		}
	},

	onUserGridEditFormProviderCredentializationInactiveBtnClick: function(btn){
		var store = btn.up('grid').getStore(),
			records = store.data.items,
			date = new Date(),
			yesterday = Ext.Date.format(Ext.Date.subtract(date, Ext.Date.DAY, 1), 'Y-m-d');

		for(var i = 0; i < records.length; i++){
			records[i].set({
				start_date: yesterday,
				end_date: yesterday,
				active: false
			});
		}

	}
});