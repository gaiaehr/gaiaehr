/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

Ext.define('App.view.administration.CPT', {
	extend: 'Ext.grid.Panel',
	requires:[
		'Ext.grid.plugin.RowEditing',
		'App.store.administration.CPT'
	],
	xtype: 'cptadmingrid',
	title: _('cpt4'),
	columns: [
		{
			width: 60,
			header: _('code'),
			dataIndex: 'code'
		},
		{
			header: _('short_name'),
			dataIndex: 'code_text_short',
			width: 100,
			flex: 1,
			editor:{
				xtype:'textfield'
			}
		},
		{
			header: _('long_name'),
			dataIndex: 'code_text',
			flex: 2,
			editor:{
				xtype:'textfield'
			}
		},
		{
			header: _('radiology'),
			dataIndex: 'isRadiology',
			editor:{
				xtype:'checkbox'
			},
			renderer: function(v){
				return this.boolRenderer(v);
			}
		},
		{
			width: 60,
			header: _('active'),
			dataIndex: 'active',
			editor:{
				xtype:'checkbox'
			},
			renderer: function(v){
				return this.boolRenderer(v);
			}
		}
	],
	plugins: [
		{
			ptype:'rowediting',
			errorSummary: false,
			clicksToEdit: 1
		}
	],
	initComponent: function(){
		var me = this;
		me.store = Ext.create('App.store.administration.CPT',{
			remoteSort: true
		});
		me.tbar = Ext.create('Ext.PagingToolbar', {
			store: me.store,
			displayInfo: true,
			emptyMsg: _('no_office_notes_to_display'),
			plugins: Ext.create('Ext.ux.SlidingPager'),
			items: [
				'-',
				{
					xtype: 'textfield',
					emptyText: _('search'),
					width: 200,
					enableKeyEvents: true,
					itemId: 'adminCpt4CodeSearchField'
				},
				'-',
				{
					xtype: 'button',
					text: _('only_active'),
					enableToggle: true,
					itemId: 'adminCpt4CodeOnlyActiveBtn'
				},
				'-'
			]
		});

		me.callParent();
	}
});
