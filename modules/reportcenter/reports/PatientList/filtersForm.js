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
            "name": 'Provider',
            "type": "int"
        },
        {
            "id": 'allergy',
            "name": 'Allergies',
            "type": "string"
        },
        {
            "id": 'problem',
            "name": 'Problems',
            "type": "string"
        },
        {
            "id": 'medication',
            "name": 'Medications',
            "type": "string"
        },
        {
            "id": 'encounter_begin_date',
            "name": 'Encounter Begin Date',
            "type": "date"
        },
        {
            "id": 'encounter_end_date',
            "name": 'Encounter End Date',
            "type": "date"
        }
    ]
});

/**
 * This is the store where the filters are collected from user input
 * @type {Ext.data.Store}
 */
var filtersCollected = Ext.create('Ext.data.Store', {
    fields: [
        'id',
        'filterName',
        'operator',
        'filterValue'
    ]
});

var operators = Ext.create('Ext.data.Store', {
    fields: [
        'id',
        'operator'
    ],
    data : [
        {
            "id": '=',
            "operator": _('equals')
        },
        {
            "id": '>',
            "name": _('greater_than')
        },
        {
            "id": '<',
            "name": _('less_than')
        },
        {
            "id": '>=',
            "name": _('greater_or_equal')
        },
        {
            "id": '<=',
            "name": _('less_or_equal')
        },
        {
            "id": '<>',
            "name": _('not_equal')
        }
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
            selType: 'rowmodel',
            plugins: [
                Ext.create('Ext.grid.plugin.RowEditing', {
                    clicksToEdit: 2
                })
            ],
            columns: [
                {
                    text: _('filter'),
                    sortable: false,
                    dataIndex: 'filterName',
                    width: 200,
                    editor: {
                        xtype: 'combo',
                        name: 'filter',
                        fieldLabel: _('choose_filter'),
                        store: filters,
                        displayField: 'name',
                        valueField: 'id'
                    }
                },
                {
                    text: _('operator'),
                    sortable: false,
                    dataIndex: 'operator',
                    width: 40,
                    editor: {
                        xtype: 'combo',
                        name: 'operator',
                        fieldLabel: _('choose_operator'),
                        store: operators,
                        displayField: 'name',
                        valueField: 'id'
                    }
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
                name: 'filter',
                fieldLabel: _('choose_filter'),
                store: filters,
                displayField: 'name',
                valueField: 'id'
            },
            '-',
            {
                xtype: 'button',
                text: _('add_filter'),
                listeners:{
                    click: {

                    }
                }
            }
        ]
    }]

});