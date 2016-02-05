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

Ext.define('App.view.patient.LabOrders', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'Ext.grid.feature.Grouping',
		'Ext.selection.CheckboxModel',
		'App.ux.LiveLabsSearch',
		'App.ux.combo.Combo'
	],
	xtype: 'patientlaborderspanel',
	title: _('lab_orders'),
	itemId: 'LabOrders',
	columnLines: true,
	tabConfig: {
		cls: 'order-tab'
	},
	store: Ext.create('App.store.patient.PatientsOrders', {
		storeId: 'LabOrderStore',
		groupField: 'date_ordered',
		remoteFilter: true,
		pageSize: 200,
		sorters: [
			{
				property: 'date_ordered',
				direction: 'DESC'
			}
		],
        proxy: {
            type: 'direct',
            api: {
                read: 'Orders.getPatientLabOrders',
                create: 'Orders.addPatientOrder',
                update: 'Orders.updatePatientOrder',
                destroy: 'Orders.deletePatientOrder'
            },
            remoteGroup: false
        }
	}),
	selModel: Ext.create('Ext.selection.CheckboxModel', {
		showHeaderCheckbox: false
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
            header: _('void'),
            groupable: false,
            width: 60,
            align: 'center',
            dataIndex: 'void',
            tooltip: _('void'),
            editor:
            {
                xtype: 'checkbox'
            },
            renderer: function(v, meta, record)
            {
                return app.voidRenderer(v);
            }
		},
		{
			header: _('order#'),
			width: 60,
			dataIndex: 'id',
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			header: _('status'),
			width: 75,
			dataIndex: 'status',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 40
			},
			renderer: function(v, meta, record){
                var look = app.getController('patient.LabOrders').labOrdersGridStatusColumnRenderer(v);
                if(record.data.void) return '<span style="text-decoration: line-through;">'+look+'</span>';
                return look;
			}
		},
		{
			xtype: 'datecolumn',
			header: _('date_ordered'),
			width: 100,
			dataIndex: 'date_ordered',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			},
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			header: _('code'),
			width: 100,
			dataIndex: 'code',
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			header: _('description'),
			flex: 1,
			dataIndex: 'description',
			editor: {
				xtype: 'labslivetsearch',
				itemId: 'rxLabOrderLabsLiveSearch'
			},
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			header: _('notes'),
			flex: 1,
			dataIndex: 'note',
			editor: {
				xtype: 'textfield'
			},
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			header: _('priority'),
			width: 100,
			dataIndex: 'priority',
			editor: {
				xtype: 'gaiaehr.combo',
				list: 98
			},
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		},
		{
			xtype: 'datecolumn',
			header: _('date_collected'),
			width: 100,
			dataIndex: 'date_collected',
			format: 'Y-m-d',
			editor: {
				xtype: 'datefield'
			},
            renderer: function(v, meta, record)
            {
                if(record.data.void) return '<span style="text-decoration: line-through;">'+ v + '</span>';
                return '<span>'+ v + '</span>';
            }
		}
	],
	tbar: [
//		me.eLabBtn =
		{
			text: _('eLab'),
			iconCls: 'icoSend',
			itemId:'electronicLabOrderBtn'
//			scope: me,
//			handler: function(){
//				alert('TODO...');
//			}
		},
		'-',
		'->',
		'-',
		{
			text: _('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId:'newLabOrderBtn'
//			scope: me,
//			handler: me.onAddOrder
		},
		'-',
//		me.labPrintBtn =
		{
			text: _('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0',
			itemId:'printLabOrderBtn'
//			scope: me,
//			handler: me.onPrintOrder
		}
	]
//	listeners: {
//		scope: me,
//		selectionchange: me.onSelectionChange
//	}
});
