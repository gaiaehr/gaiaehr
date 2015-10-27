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
 * @type {Ext.data.Store}
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

Ext.define('FiltersCollected', {
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'filterName',
            type: 'string'
        },
        {
            name: 'operator',
            type: 'string'
        },
        {
            name: 'filterValue',
            type: 'string'
        }
    ]
});

/**
 * This is the store where the filters are collected from user input
 * @type {Ext.data.Store}
 */
var filtersCollected = Ext.create('Ext.data.Store', {
    model: 'FiltersCollected',
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'FiltersCollected'
        }
    }
});

/**
 * @type {Ext.grid.plugin.RowEditing}
 */
var rowEditor = Ext.create('Ext.grid.plugin.RowEditing', {
    clicksToEdit: 2,
    itemId: 'filterRowEditor'
});

/**
 * Store of operators for the filter
 * @type {Ext.data.Store}
 */
var operators = Ext.create('Ext.data.Store', {
    fields: [
        'id',
        'operator',
        'operatorName'
    ],
    data : [
        {
            "id": 0,
            "operator": '=',
            "operatorName": _('equals')
        },
        {
            "id": 1,
            "operator": '>',
            "operatorName": _('greater_than')
        },
        {
            "id": 2,
            "operator": '<',
            "operatorName": _('less_than')
        },
        {
            "id": 3,
            "operator": '>=',
            "operatorName": _('greater_or_equal')
        },
        {
            "id": 4,
            "operator": '<=',
            "operatorName": _('less_or_equal')
        },
        {
            "id": 5,
            "operator": '<>',
            "operatorName": _('not_equal')
        }
    ]
});

Ext.define('Modules.reportcenter.reports.PatientList.filtersForm', {
    extend: 'Ext.form.Panel',
    requires:[
        'Ext.form.field.Date',
        'App.ux.combo.ActiveProviders',
        'App.ux.combo.Allergies',
        'App.ux.LiveMedicationSearch',
        'App.ux.LiveSnomedProblemSearch'
    ],
    xtype: 'reportFilter',
    region: 'north',
    title: _('filters'),
    collapsible: true,
    border: true,
    items:[
        {
            xtype: 'grid',
            store: filtersCollected,
            border: false,
            itemId: 'patientFilters',
            selType: 'rowmodel',
            plugins: [
                rowEditor
            ],
            columns: [
                {
                    text: _('filter'),
                    sortable: false,
                    dataIndex: 'filterName',
                    hideable: false,
                    width: 200,
                    editor: {
                        xtype: 'combo',
                        name: 'filter',
                        store: filters,
                        displayField: 'name',
                        valueField: 'id',
                        listeners:{
                            select: function(records, eOpts ){
                                var Grid = Ext.ComponentQuery.query('reportFilter #patientFilters')[0],
                                    ValueColumn = Grid.columns[2];
                                switch(records.value) {
                                    case 'provider':
                                        ValueColumn.setEditor({
                                            xtype: 'activeproviderscombo',
                                            name: 'provider',
                                            allowBlank: false,
                                            flex: 1,
                                            displayField: 'option_name',
                                            valueField: 'id'
                                        });
                                        break;
                                    case 'allergy':
                                        ValueColumn.setEditor({
                                            xtype: 'allergieslivesearch',
                                            itemId: 'allergySearchCombo',
                                            name: 'allergy',
                                            enableKeyEvents: true,
                                            flex: 1,
                                            allowBlank: false
                                        });
                                        break;
                                    case 'problem':
                                        ValueColumn.setEditor({
                                            xtype: 'snomedliveproblemsearch',
                                            itemId: 'problemSearchCombo',
                                            name: 'problem',
                                            enableKeyEvents: true,
                                            flex: 1,
                                            allowBlank: false
                                        });
                                        break;
                                    case 'medication':
                                        ValueColumn.setEditor({
                                            xtype: 'medicationlivetsearch',
                                            itemId: 'medicationSearchCombo',
                                            name: 'medication',
                                            enableKeyEvents: true,
                                            flex: 1,
                                            allowBlank: false
                                        });
                                        break;
                                    case 'encounter_begin_date':
                                        ValueColumn.setEditor({
                                            xtype: 'datefield',
                                            name: 'begin_date',
                                            flex: 1,
                                            allowBlank: false,
                                            format: g('date_display_format'),
                                            submitFormat: 'Y-m-d'
                                        });
                                        break;
                                    case 'encounter_end_date':
                                        ValueColumn.setEditor({
                                            xtype: 'datefield',
                                            name: 'end_date',
                                            flex: 1,
                                            allowBlank: false,
                                            format: g('date_display_format'),
                                            submitFormat: 'Y-m-d'
                                        });
                                        break;
                                }
                            }
                        }
                    }
                },
                {
                    text: _('operator'),
                    sortable: false,
                    dataIndex: 'operator',
                    hideable: false,
                    width: 120,
                    editor: {
                        xtype: 'combo',
                        name: 'operator',
                        store: operators,
                        displayField: 'operatorName',
                        valueField: 'operator'
                    }
                },
                {
                    text: _('value'),
                    sortable: false,
                    hideable: false,
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
                xtype: 'button',
                text: _('add_filter'),
                listeners:{
                    click: function(e, eOpts){
                        rowEditor.cancelEdit();
                        filtersCollected.add({
                            "filterName":"",
                            "operatorName":"",
                            "filterValue":""
                        });
                        rowEditor.startEdit(0, 0);
                    }
                }
            }
        ]
    }]

});