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

Ext.define('App.view.patient.windows.PossibleDuplicates', {
	extend: 'App.ux.window.Window',
	title: i18n('possible_duplicates'),
	itemId: 'PossiblePatientDuplicatesWindow',
	closeAction: 'hide',
	bodyStyle: 'background-color:#fff',
	modal: true,
	closable: false,
	requires: [
		'Ext.toolbar.Paging',
		'Ext.ux.SlidingPager'
	],
	initComponent: function(){
		var me = this;

		me.items = [
			{
				xtype: 'grid',
				store: me.store = Ext.create('App.store.patient.PatientPossibleDuplicates'),
				width: 600,
				frame: true,
				margin: 5,
				hideHeaders: true,
				columns: [
					{
						dataIndex: 'image',
						width: 65,
						renderer: function(v){
							return '<img src="' + v + '" class="icon32Round" />';
						}
					},
					{
						dataIndex: 'fullname',
						flex: 1,
						renderer: function(v, meta, record){
							return v + ' ' + record.data.SS  + '<br>' + record.data.fulladdress + '<br>' + record.data.phones;
						}
					}
				],
				bbar: {
					xtype: 'pagingtoolbar',
					pageSize: 10,
					store: me.store,
					displayInfo: true,
					plugins: Ext.create('Ext.ux.SlidingPager')
				}
			}
		];

		me.buttons = [
			{
				text: i18n('continue'),
				itemId: 'PossiblePatientDuplicatesContinueBtn'
			}
		];

		me.callParent();
	}
});