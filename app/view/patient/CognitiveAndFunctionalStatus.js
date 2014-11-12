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

Ext.define('App.view.patient.CognitiveAndFunctionalStatus', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.ux.grid.RowFormEditing',
		'App.ux.LiveSnomedSearch',
		'App.store.patient.CognitiveAndFunctionalStatus'
	],
	xtype: 'patientcognitiveandfunctionalstatuspanel',
	title: _('cognitive_and_functional_status'),
	columnLines: true,
	store: Ext.create('App.store.patient.CognitiveAndFunctionalStatus', {
		remoteFilter: true
	}),
	plugins: [
		{
			ptype: 'rowediting'
		}
	],
	columns: [
		{
			xtype: 'actioncolumn',
			width: 20,
			items: [
				{
					icon: 'resources/images/icons/cross.png',
					tooltip: _('remove')
				}
			]
		},
		{
			header: _('category'),
			width: 150,
			dataIndex: 'category',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 117,
				itemId: 'functionalStatusCategoryCombo',
				allowBlank: false
			}
		},
		{
			header: _('description'),
			flex: 1,
			dataIndex: 'code_text',
			editor: {
				xtype: 'snomedlivesearch',
				itemId: 'functionalStatusCodeCombo',
				displayField: 'FullySpecifiedName',
				valueField: 'FullySpecifiedName',
				allowBlank: false
			}
		},
		{
			header: _('note'),
			flex: 2,
			dataIndex: 'note',
			editor: {
				xtype: 'textfield'
			}
		},
		{
			xtype: 'datecolumn',
			header: _('begin_date'),
			dataIndex: 'begin_date',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d'
			}
		},
		{
			xtype: 'datecolumn',
			header: _('end_date'),
			dataIndex: 'end_date',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d'
			}
		},
		{
			header: _('status'),
			dataIndex: 'status',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 118,
				itemId: 'functionalStatusSatausCombo',
				allowBlank: false
			}
		}
	],
	tbar: [
		'->',
		'-',
		{
			text: _('add_new'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId: 'newFunctionalStatusBtn'
		}
	]
});