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

Ext.define('App.view.patient.SupperBill', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'App.ux.LiveCPTSearch',
		'App.ux.combo.EncounterICDS'
	],
	xtype: 'superbillpanel',
	store: Ext.create('App.store.patient.EncounterServices'),
	columnLines: true,
	plugins: [
		{
			ptype: 'rowediting',
			errorSummary: false
		}
	],
	columns: [
		{
			xtype: 'actioncolumn',
			width: 20,
			menuDisabled: true,
			items: [
				{
					icon: 'resources/images/icons/delete.png',
					tooltip: _('remove'),
					handler: function(view, rowIndex, colIndex, item, e, record){
						return App.app.getController('patient.encounter.SuperBill').onRemoveService(record);
					}
				}
			]
		},
		{
			header: _('units'),
			dataIndex: 'units',
			width: 40,
			menuDisabled: true,
			editor: {
				xtype: 'numberfield',
				minValue: 1,
				allowBlank: false
			}
		},
		{
			header: _('code'),
			dataIndex: 'code',
			menuDisabled: true,
			width: 75
		},
		{
			text: _('service'),
			dataIndex: 'code_text',
			flex: 1,
			menuDisabled: true,
			editor: {
				xtype: 'livecptsearch',
				itemId: 'SuperCptSearchCmb',
				valueField: 'code_text_medium',
				allowBlank: false
			}
		},
		{
			header: _('modifiers'),
			dataIndex: 'modifiers',
			width: 100,
			menuDisabled: true,
			editor: {
				xtype: 'textfield'
			}
		},
		{
			header: _('tooth'),
			dataIndex: 'tooth',
			width: 50,
			menuDisabled: true
		},
		{
			header: _('surface'),
			dataIndex: 'surface',
			width: 60,
			menuDisabled: true,
			renderer: function(value, meta, record){
				var len = value.length,
					str = '',
					isMolar = App.app.getController('Modules.dental.controller.Plan').isMolar(record.data.tooth);

				for(var i = 0; i < len; i++){
					if(value[i] == '0') continue;

					if(value[i] == 'OI'){
						str += isMolar ? 'O' : 'I';
					}else if(value[i] == 'BF'){
						str += isMolar ? 'B' : 'F';
					}else{
						str += value[i];
					}
				}
				return str;
			}
		},
		{
			header: _('diagnosis'),
			dataIndex: 'dx_pointers',
			menuDisabled: true,
			width: 250,
			editor: {
				xtype: 'encountericdscombo',
				itemId: 'SuperBillEncounterDxCombo',
				allowBlank: false
			}
		}
	],
	dockedItems: [
		{
			xtype: 'toolbar',
			dock: 'top',
			items: [
				'->',
				{
					text: _('service'),
					iconCls: 'icoAdd',
					itemId: 'SuperBillServiceAddBtn'
				}
			]
		}
	]
});