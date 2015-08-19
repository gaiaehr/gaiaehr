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

Ext.define('App.view.patient.FamilyHistory', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'Ext.grid.feature.Grouping'
	],
	xtype: 'patientfamilyhistorypanel',
	title: _('family_history'),
	columnLines: true,
	store: Ext.create('App.store.patient.FamilyHistories', {
		remoteFilter: true,
		groupField: 'condition'
	}),
	features: [
		{
			ftype: 'grouping'
		}
	],
	plugins: [
		{
			ptype: 'rowediting',
			clicksToEdit: 2
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
			xtype: 'datecolumn',
			header: _('date'),
			width: 100,
			dataIndex: 'create_date',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			}
		},
		{
			header: _('condition'),
			flex: 1,
			dataIndex: 'condition'
		},
		{
			header: _('relation'),
			flex: 1,
			dataIndex: 'relation'
		},
		{
			header: _('status'),
			flex: 1,
			dataIndex: 'status'
		}
	],
	tbar: [
		'->',
		{
			text: _('history'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId:'FamilyHistoryGridAddBtn'
		}
	]
});