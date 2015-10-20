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

Ext.define('Modules.reportcenter.reports.AutomatedMeasureCalculation.filtersForm', {
    extend: 'Ext.form.Panel',
    requires:[
        'Ext.form.field.Date'
    ],
    xtype: 'reportFilter',
    region: 'north',
    title: _('filters'),
    itemId: 'MedicationReport',
    collapsible: true,
    height: 200,
    border: true,
    fieldDefaults: {
        labelWidth: 90,
        margin: 5,
        anchor: '50%'
    },
    bodyPadding: 2,
    items:[
        {
            xtype: 'datefield',
            name: 'begin_date',
            fieldLabel: _('begin_date'),
            allowBlank: false
        },
        {
            xtype: 'datefield',
            name: 'end_date',
            fieldLabel: _('end_date'),
            allowBlank: false
        }
    ]
});