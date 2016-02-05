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
        'App.ux.LiveMedicationSearch',
        'App.ux.LiveSnomedProblemSearch'
    ],
    xtype: 'reportFilter',
    region: 'north',
    title: _('filters'),
    itemId: 'PatientList',
    collapsible: true,
    height: 100,
    border: true,
    bodyPadding: 2,
    layout: 'column',
    items:[
        {
            xtype:'fieldset',
            columnWidth: 0.5,
            defaults: {anchor: '100%'},
            layout: 'anchor',
            border: false,
            items :[
                {
                    xtype: 'datefield',
                    name: 'begin_date',
                    fieldLabel: _('begin_date'),
                    allowBlank: false,
                    width: 320,
                    format: g('date_display_format'),
                    submitFormat: 'Y-m-d'
                },
                {
                    xtype: 'datefield',
                    name: 'end_date',
                    fieldLabel: _('end_date'),
                    width: 320,
                    allowBlank: false,
                    format: g('date_display_format'),
                    submitFormat: 'Y-m-d'
                }
            ]
        },
        {
            xtype:'fieldset',
            columnWidth: 0.5,
            defaults: {anchor: '100%'},
            layout: 'anchor',
            border: false,
            items:[
                {
                    xtype: 'activeproviderscombo',
                    fieldLabel: _('provider'),
                    name: 'provider',
                    width: 400,
                    allowBlank: false,
                    displayField: 'option_name',
                    valueField: 'id',
                    listeners: {
                        select: function(combo, records, eOpts){
                            var fieldName = Ext.ComponentQuery.query('reportFilter #provider_name')[0];
                            fieldName.setValue(records[0].data.option_name);
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
                    xtype: 'allergieslivesearch',
                    fieldLabel: _('allergy'),
                    name: 'allergy_code',
                    hideLabel: false,
                    width: 400,
                    displayField: 'allergy',
                    valueField: 'allergy_code',
                    listeners: {
                        select: function(combo, records, eOpts){
                            var fieldName = Ext.ComponentQuery.query('reportFilter #allergy_name')[0];
                            fieldName.setValue(records[0].data.allergy);
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
                    xtype: 'snomedliveproblemsearch',
                    hideLabel: false,
                    fieldLabel: _('problem'),
                    name: 'problem_code',
                    enableKeyEvents: true,
                    flex: 1,
                    value: null,
                    listeners: {
                        select: function(combo, records, eOpts){
                            var fieldName = Ext.ComponentQuery.query('reportFilter #problem_name')[0];
                            fieldName.setValue(records[0].data.FullySpecifiedName);
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
                    xtype: 'medicationlivetsearch',
                    hideLabel: false,
                    fieldLabel: _('medication'),
                    name: 'medication_code',
                    enableKeyEvents: true,
                    flex: 1,
                    value: null,
                    listeners: {
                        select: function(combo, records, eOpts){
                            var fieldName = Ext.ComponentQuery.query('reportFilter #medication_name')[0];
                            fieldName.setValue(records[0].data.PROPRIETARYNAME);
                        }
                    }
                },
                {
                    xtype: 'hiddenfield',
                    itemId: 'medication_name',
                    name: 'medication_name',
                    value: ''
                }
            ]
        }
    ]
});
