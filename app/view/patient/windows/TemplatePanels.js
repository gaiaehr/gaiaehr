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

Ext.define('App.view.patient.windows.TemplatePanels', {
	extend: 'App.ux.window.Window',
	title: _('templates'),
	closeAction: 'hide',
	layout: 'fit',
	modal: true,
	width: 600,
	height: 300,
	itemId: 'TemplatePanelsWindow',
	bodyPadding: 5,
	tbar: [
		{
			xtype: 'combobox',
			store: Ext.create('App.store.administration.TemplatePanels'),
			displayField: 'description',
			valueField: 'id',
			itemId: 'TemplatePanelsCombo',
			width: 300,
			editable: false,
			allowBlank: false,
			queryMode: 'local'
		}
	],
	items: [
		{
			xtype: 'grid',
			frame: true,
			itemId: 'TemplatePanelsGrid',
			selType: 'checkboxmodel',
			features: [
				{
					ftype:'grouping',
					groupHeaderTpl: '{name}',
					collapsible: false
				}
			],
			columns: [
				{
					text: _('description'),
					dataIndex: 'description',
					flex: 1,
					sortable: false,
					groupable: false,
					hideable: false,
					menuDisabled: true
				}
			]
		}
	],
	buttons: [
		{
			text: _('add'),
			itemId: 'TemplatePanelsAddBtn'
		},
		{
			text: _('cancel'),
			itemId: 'TemplatePanelsCancelBtn'
		}
	]
});
