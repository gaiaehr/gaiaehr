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

Ext.define('App.view.dashboard.panel.OnotesPortlet', {
	extend: 'Ext.grid.Panel',
	xtype: 'onotesportlet',
	height: 250,

	initComponent: function(){
		var me = this;

		// *************************************************************************************
		// Office Notes Portlet Data Store
		// *************************************************************************************
		me.store = Ext.create('App.store.dashboard.panel.OfficeNotesPortlet');

		Ext.apply(me, {
			height: me.height,
			store: me.store,
			stripeRows: true,
			columnLines: true,
			columns: [
				{
					id: 'user',
					text: 'From',
					sortable: true,
					dataIndex: 'user'
				},
				{
					text: 'Note',
					sortable: true,
					dataIndex: 'body',
					flex: 1
				}
			]
		});

		me.store.load();

		me.callParent(arguments);
	}
});
