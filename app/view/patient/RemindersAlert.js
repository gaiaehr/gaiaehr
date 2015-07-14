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

Ext.define('App.view.patient.RemindersAlert', {
	extend: 'Ext.window.Window',
	requires: [
		'Ext.grid.plugin.RowEditing'
	],
	title: _('reminders'),
	width: 700,
	closeAction: 'hide',
	initComponent: function(){

		var me = this;

		me.items = [
			{
				xtype: 'grid',
				itemId: 'RemindersAlertGrid',
				margin: 5,
				frame : true,
				store: Ext.create('App.store.patient.Reminders'),
				plugins: {
					ptype: 'cellediting',
					autoCancel: false,
					errorSummary: false,
					clicksToEdit: 2
				},
				columns: [
					{
						xtype: 'datecolumn',
						text: _('date'),
						format: g('date_display_format'),
						dataIndex: 'date'
					},
					{
						text: _('note'),
						dataIndex: 'body',
						flex: 1
					},
					{
						text: _('active'),
						width: 50,
						dataIndex: 'active',
						renderer: function(v, m, r){
							return app.boolRenderer(v, m, r);
						},
						editor: {
							xtype: 'checkbox'
						}
					}
				]
			}
		];



		me.callParent();

	},
	buttons: [
		'->',
		{
			text: _('ok'),
			itemId: 'RemindersAlertOkBtn'
		},
		'-',
		{
			text: _('cancel'),
			itemId: 'RemindersAlertCancelBtn'
		}
	]
});