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
		'App.ux.combo.SmokingStatus'
	],
	xtype: 'patientsocialhistorypanel',
	title: i18n('social_history'),
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
			groupHeaderTpl: i18n('type') + ': {name}'
		}
	],
	columns: [
		{
			text: i18n('type'),
			dataIndex: 'category_code_text',
			width: 250
		},
		{
			text: i18n('observation'),
			dataIndex: 'observation',
			flex: 1,
			itemId: 'socialhistorypanelobservationcolumn',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},
		{
			xtype: 'datecolumn',
			text: i18n('start'),
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
			text: i18n('end'),
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
			xtype: 'mitos.smokingstatuscombo',
			fieldLabel: i18n('smoking_status'),
			itemId: 'socialsmokingstatuscombo',
			width: 350
		},
		'->',
		i18n('social_history'),
		{
			xtype: 'gaiaehr.combo',
			width: 200,
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