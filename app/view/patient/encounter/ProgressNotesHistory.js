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

Ext.define('App.view.patient.encounter.ProgressNotesHistory', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.form.SearchField'
	],
	xtype: 'progressnoteshistory',
	title: _('history'),
	hideHeaders: true,
	initComponent: function(){

		var me = this;

		me.store = Ext.create('App.store.patient.ProgressNotesHistory');

		me.columns = [
			{
				dataIndex: 'progress',
				flex: 1
			}
		];

		me.tbar = [
			{
				xtype: 'gaiasearchfield',
				emptyText: _('search'),
				flex: 1,
				itemId: 'ProgressNotesHistorySearchField',
				store: me.store,
				filterFn: function(record, value){
					return record.data.progress.search(new RegExp(value, 'ig')) !== -1;

				}
			}
		];

		me.callParent();
	}

});