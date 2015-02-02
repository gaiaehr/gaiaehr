/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

Ext.define('App.ux.PatientEncounterCombo', {
    extend: 'Ext.form.ComboBox',
    alias: 'widget.patientEncounterCombo',
    hideLabel: true,

    initComponent: function(){
        var me = this;

        Ext.define('patientEncounterComboModel', {
            extend: 'Ext.data.Model',
            fields: [
                { name: 'eid' },
                { name: 'brief_description' },
                { name: 'service_date' },
                { name: 'close_date' }
            ],
            proxy: {
                type: 'direct',
                api: {
                    read: 'Encounter.getEncounters'
                },
                reader: {
                    totalProperty: 'totals',
                    root: 'rows'
                }
            }
        });

        me.store = Ext.create('Ext.data.Store', {
            model: 'patientEncounterComboModel',
            pageSize: 10,
            autoLoad: false
        });

        Ext.apply(this, {
            store: me.store,
            displayField: 'brief_description',
            valueField: 'brief_description',
            emptyText: i18n('search') + '...',
            width: 400,
            editable: false,
            listConfig: {
                loadingText: i18n('searching') + '...',
                getInnerTpl: function(){
                    return '<div class="search-item-encounter"><h3>{brief_description} ({service_date})</h3></div>';
                }
            },
            pageSize: 10
        });

        me.callParent();

    }

});