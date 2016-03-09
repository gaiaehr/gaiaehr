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

Ext.define('Modules.reportcenter.reports.TransactionLog.filtersForm', {
    extend: 'Ext.form.Panel',
    requires: [
        'Ext.form.field.Date'
    ],
    xtype: 'reportFilter',
    region: 'west',
    title: _('filters'),
    itemId: 'TransactionLogFilters',
    collapsible: true,
    border: true,
    split: true,
    defaults: {
        xtype: 'fieldset',
        layout: 'anchor',
        defaults: {anchor: '100%'},
        border: false,
        frame: false,
        margin: 2
    },
    items: [
        {
            items: [
                {
                    xtype: 'panel',
                    layout: 'column',
                    border: false,
                    frame: false,
                    items: [
                        {
                            xtype: 'datefield',
                            name: 'begin_date',
                            columnWidth:.5,
                            hideLabel: true,
                            emptyText:  _('begin_date'),
                            allowBlank: true,
                            format: g('date_display_format'),
                            submitFormat: 'Y-m-d'
                        },
                        {
                            xtype: 'timefield',
                            name: 'begin_time',
                            hideLabel: true,
                            emptyText:  _('begin_time'),
                            increment: 30,
                            columnWidth:.5,
                            format: 'H:i:s',
                            submitFormat: 'H:i:s',
                            value: '00:00:00'
                        }
                    ]
                }
            ]
        },
        {
            items: [
                {
                    xtype: 'panel',
                    layout: 'column',
                    border: false,
                    frame: false,
                    items: [
                        {
                            xtype: 'datefield',
                            name: 'end_date',
                            columnWidth:.5,
                            hideLabel: true,
                            emptyText:  _('end_date'),
                            allowBlank: true,
                            format: g('date_display_format'),
                            submitFormat: 'Y-m-d'
                        },
                        {
                            xtype: 'timefield',
                            name: 'end_time',
                            hideLabel: true,
                            emptyText:  _('end_time'),
                            increment: 30,
                            columnWidth:.5,
                            format: 'H:i:s',
                            submitFormat: 'H:i:s',
                            value: '23:00:00'
                        }
                    ]
                }
            ]
        }
    ]
});
