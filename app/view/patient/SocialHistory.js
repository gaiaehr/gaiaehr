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

Ext.define('App.view.patient.SocialHistory', {
	extend: 'Ext.grid.Panel',
	requires:[
		'Ext.grid.plugin.RowEditing',
		'Ext.grid.feature.Grouping',
		'App.store.patient.PatientSocialHistory',
		'App.ux.combo.Combo',
	],
	xtype: 'patientsocialhistorypanel',
	itemId: 'PatientSocialHistoryGrid',
	columnLines: true,
	store: Ext.create('App.store.patient.PatientSocialHistory',{
		remoteFilter: true
	}),
	plugins: [
		{
			ptype: 'rowediting'
		}
	],
	features: [
		{
			ftype: 'grouping',
			groupHeaderTpl: _('type') + ': {name}'
		}
	],
	columns: [
		{
			text: _('type'),
			dataIndex: 'category_code_text',
			width: 250
		},
		{
			text: _('observation'),
			dataIndex: 'observation',
			flex: 1,
			itemId: 'socialhistorypanelobservationcolumn',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},
		{
			text: _('note'),
			dataIndex: 'note',
			flex: 1,
			editor: {
				xtype: 'textfield'
			}
		},
		{
			xtype: 'datecolumn',
			text: _('start'),
			dataIndex: 'start_date',
			format: 'Y-m-d',
			width: 120,
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d',
				allowBlank: false
			}
		},
		{
			xtype: 'datecolumn',
			text: _('end'),
			dataIndex: 'end_date',
			format: 'Y-m-d',
			width: 120,
			editor: {
				xtype: 'datefield',
				format: 'Y-m-d'
			}
		}
	],
	tbar: [
		{
			xtype: 'tbtext',
			text: _('social_history'),
			width: 100
		},
		{
			xtype: 'gaiaehr.combo',
			width: 250,
			list: 101,
			allowBlank: false,
			action: 'socialHistoryTypeCombo'
		},
		{
			iconCls: 'icoAdd',
			disabled: true,
			itemId: 'encounterRecordAdd',
			action: 'socialHistoryAddBtn'
		}
	]
});