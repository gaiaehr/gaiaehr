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

Ext.define('App.view.administration.Specialities', {
	extend: 'Ext.grid.Panel',
	xtype: 'specialitiespanel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'Ext.ux.SlidingPager'
	],
	title: i18n('specialities'),

	initComponent: function(){
		var me = this;

		Ext.apply(me, {
			store: me.store = Ext.create('App.store.administration.Specialities', {
				autoSync: false
			}),
			columns: [
				{
					width: 200,
					text: i18n('title'),
					sortable: true
				},
				{
					flex: 1,
					text: i18n('taxonomy'),
					sortable: true,
					dataIndex: 'taxonomy'
				},
				{
					flex: 1,
					text: i18n('modality'),
					sortable: true,
					dataIndex: 'modality'
				},
				{
					text: i18n('active'),
					sortable: true,
					dataIndex: 'active',
					renderer: me.boolRenderer
				}
			],
			plugins: [
				{
					ptype: 'rowediting',
					clicksToEdit: 1
				}
			],
			tbar: [
				'->',
				{
					xtype: 'button',
					text: i18n('specialitiy'),
					iconCls: 'icoAdd',
					itemId: 'specialitiesAddBtn'
				}
			],
			bbar: Ext.create('Ext.PagingToolbar', {
				pageSize: 10,
				store: me.store,
				displayInfo: true,
				plugins: Ext.create('Ext.ux.SlidingPager', {})
			})
		});

		me.callParent(arguments);

	}

});
