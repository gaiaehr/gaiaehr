/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
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

/**
 * Filter available for the Patient List Report (Store)
 */
var filters = Ext.create('Ext.data.Store', {
    fields: [
        'id',
        'name'
    ],
    data : [
        {
            "id": 'provider',
            "name": 'Provider'
        },
        {
            "id": 'allergy',
            "name": 'Allergies'
        },
        {
            "id": 'problem',
            "name": 'Problems'
        },
        {
            "id": 'medication',
            "name": 'Medications'
        }
    ]
});

var filtersCollected = Ext.create('Ext.data.Store', {
    fields: [
        'id',
        'filterName',
        'operator',
        'filterValue'
    ]
});

Ext.define('Modules.reportcenter.reports.PatientList.filtersForm', {
    extend: 'Ext.form.Panel',
    requires:[
        'Ext.form.field.Date',
        'App.ux.combo.ActiveProviders'
    ],
    xtype: 'reportFilter',
    region: 'north',
    title: _('filters'),
    itemId: 'AutomatedMeasureCalculation',
    collapsible: true,
    border: true,
    items:[
        {
            xtype: 'grid',
            store: filtersCollected,
            border: false,
            columns: [
                {
                    text: _('filter'),
                    sortable: false,
                    dataIndex: 'filterName',
                    width: 200
                },
                {
                    text: _('operator'),
                    sortable: false,
                    dataIndex: 'operator',
                    width: 40
                },
                {
                    text: _('value'),
                    sortable: false,
                    dataIndex: 'filterValue',
                    flex: 1
                }
            ]
        }
    ],
    dockedItems: [{
        xtype: 'toolbar',
        dock: 'top',
        items: [
            '->',
            {
                xtype: 'combo',
                fieldLabel: _('choose_filter'),
                store: filters,
                displayField: 'name',
                valueField: 'id'
            },
            '-',
            {
                xtype: 'button',
                text: _('add_filter')
            }
        ]
    }]

});