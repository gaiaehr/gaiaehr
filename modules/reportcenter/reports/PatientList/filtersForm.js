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

Ext.define('Modules.reportcenter.reports.PatientList.filtersForm', {
    extend: 'Ext.form.Panel',
    requires:[
        'Ext.form.field.Date',
        'App.ux.combo.ActiveProviders',
        'App.ux.combo.Allergies',
        'App.ux.LiveRXNORMSearch',
        'Modules.reportcenter.reports.PatientList.ux.LiveRXNORMSearchReport'
    ],
    xtype: 'reportFilter',
    region: 'west',
    title: _('filters'),
    itemId: 'PatientList',
    collapsible: true,
    border: true,
    bodyPadding: 2,
    split: true,
    defaults:{
        layout: 'column',
        border: false,
        frame: false,
        margin: 2,
    },
    items:[
        {
            xtype: 'panel',
            items:
            [
                {
                    xtype: 'datefield',
                    name: 'begin_date',
                    fieldLabel: _('begin_date'),
                    labelWidth: 120,
                    allowBlank: false,
                    format: g('date_display_format'),
                    submitFormat: 'Y-m-d'
                },
                {
                    xtype: 'combo',
                    name: 'begin_sort',
                    queryMode: 'local',
                    width: 55,
                    editable: false,
                    emptyText: _('sort'),
                    store: ['ASC', 'DESC']
                }
            ]
        },
        {
            xtype: 'panel',
            items:
            [
                {
                    xtype: 'datefield',
                    name: 'end_date',
                    labelWidth: 120,
                    fieldLabel: _('end_date'),
                    allowBlank: false,
                    format: g('date_display_format'),
                    submitFormat: 'Y-m-d'
                }
            ]
        },
        {
            xtype: 'panel',
            items:
                [
                    {
                        xtype: 'activeproviderscombo',
                        fieldLabel: _('provider'),
                        name: 'provider',
                        displayField: 'option_name',
                        valueField: 'id',
                        editable: true,
                        hideLabel: true,
                        emptyText: _('select_provider'),
                        allowOnlyWhitespace: true,
                        listeners: {
                            select: function(combo, records, eOpts){
                                var field = Ext.ComponentQuery.query('reportFilter #provider_name')[0];
                                field.setValue(records[0].data.option_name);
                            }
                        }
                    },
                    {
                        xtype: 'hiddenfield',
                        itemId: 'provider_name',
                        name: 'provider_name',
                        value: ''
                    },
                    {
                        xtype: 'combo',
                        name: 'prov_sort',
                        queryMode: 'local',
                        width: 55,
                        editable: false,
                        emptyText: _('sort'),
                        store: ['ASC', 'DESC']
                    }
                ]
        },
        {
            xtype: 'panel',
            items:
                [
                    {
                        xtype: 'allergieslivesearch',
                        hideLabel: true,
                        name: 'allergy_code',
                        displayField: 'allergy',
                        valueField: 'allergy_code',
                        listeners: {
                            select: function(combo, records, eOpts){
                                var field = Ext.ComponentQuery.query('reportFilter #allergy_name')[0];
                                field.setValue(records[0].data.allergy);
                            }
                        }
                    },
                    {
                        xtype: 'hiddenfield',
                        itemId: 'allergy_name',
                        name: 'allergy_name',
                        value: ''
                    },
                    {
                        xtype: 'combo',
                        name: 'allergies_sort',
                        queryMode: 'local',
                        width: 55,
                        editable: false,
                        emptyText: _('sort'),
                        store: ['ASC', 'DESC']
                    }
                ]
        },
        {
            xtype: 'panel',
            items:[
                {
                    xtype: 'snomedliveproblemsearch',
                    hideLabel: true,
                    name: 'problem_code',
                    enableKeyEvents: true,
                    value: null,
                    listeners: {
                        select: function(combo, records, eOpts){
                            var field = Ext.ComponentQuery.query('reportFilter #problem_name')[0];
                            field.setValue(records[0].data.FullySpecifiedName);
                        }
                    }
                },
                {
                    xtype: 'hiddenfield',
                    itemId: 'problem_name',
                    name: 'problem_name',
                    value: ''
                },
                {
                    xtype: 'combo',
                    name: 'problems_sort',
                    queryMode: 'local',
                    width: 55,
                    editable: false,
                    emptyText: _('sort'),
                    store: ['ASC', 'DESC']
                }
            ]
        },
        {
            xtype: 'panel',
            items:[
                {
                    xtype: 'rxnormlivetsearchreport',
                    hideLabel: true,
                    name: 'medication_code',
                    enableKeyEvents: true,
                    value: null,
                    displayField: 'STR',
                    valueField: 'RXCUI'
                },
                {
                    xtype: 'hiddenfield',
                    itemId: 'medication_name',
                    name: 'medication_name',
                    value: ''
                },
                {
                    xtype: 'combo',
                    name: 'medications_sort',
                    queryMode: 'local',
                    width: 55,
                    editable: false,
                    emptyText: _('sort'),
                    store: ['ASC', 'DESC']
                }
            ]
        }
    ]
});
