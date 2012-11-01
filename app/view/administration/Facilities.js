/*
 GaiaEHR (Electronic Health Records)
 Facilities.js
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.view.administration.Facilities', {
    extend: 'App.ux.RenderPanel',
    id: 'panelFacilities',
    pageTitle: i18n('facilities_active'),
    uses: ['App.ux.GridPanel', 'App.ux.window.Window'],
    initComponent: function(){
        var me = this;
        Ext.define('facilityModel', {
            extend: 'Ext.data.Model',
            fields: [
                {
                    name: 'id',
                    type: 'int'
                },
                {
                    name: 'name',
                    type: 'string'
                },
                {
                    name: 'active',
                    type: 'bool'
                },
                {
                    name: 'phone',
                    type: 'string'
                },
                {
                    name: 'fax',
                    type: 'string'
                },
                {
                    name: 'street',
                    type: 'string'
                },
                {
                    name: 'city',
                    type: 'string'
                },
                {
                    name: 'state',
                    type: 'string'
                },
                {
                    name: 'postal_code',
                    type: 'string'
                },
                {
                    name: 'country_code',
                    type: 'string'
                },
                {
                    name: 'federal_ein',
                    type: 'string'
                },
                {
                    name: 'service_location',
                    type: 'bool'
                },
                {
                    name: 'billing_location',
                    type: 'bool'
                },
                {
                    name: 'accepts_assignment',
                    type: 'bool'
                },
                {
                    name: 'pos_code',
                    type: 'string'
                },
                {
                    name: 'x12_sender_id',
                    type: 'string'
                },
                {
                    name: 'attn',
                    type: 'string'
                },
                {
                    name: 'domain_identifier',
                    type: 'string'
                },
                {
                    name: 'facility_npi',
                    type: 'string'
                },
                {
                    name: 'tax_id_type',
                    type: 'string'
                }
            ],
            proxy: {
                type: 'direct',
                api: {
                    read: Facilities.getFacilities,
                    create: Facilities.addFacility,
                    update: Facilities.updateFacility,
                    destroy: Facilities.deleteFacility
                }
            }
        });
        me.FacilityStore = Ext.create('Ext.data.Store', {
            model: 'facilityModel',
            remoteSort: true
        });
        // *************************************************************************************
        // Facility Grid Panel
        // *************************************************************************************
        me.FacilityGrid = Ext.create('Ext.grid.Panel', {
            store: me.FacilityStore,
            columns: [
                {
                    text: i18n('name'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'name'
                },
                {
                    text: i18n('phone'),
                    width: 100,
                    sortable: true,
                    dataIndex: 'phone'
                },
                {
                    text: i18n('fax'),
                    width: 100,
                    sortable: true,
                    dataIndex: 'fax'
                },
                {
                    text: i18n('city'),
                    width: 100,
                    sortable: true,
                    dataIndex: 'city'
                }
            ],
            plugins: Ext.create('App.ux.grid.RowFormEditing', {
                autoCancel: false,
                errorSummary: false,
                clicksToEdit: 1,
                formItems: [
                    {
                        xtype: 'container',
                        layout: 'column',
                        defaults: {
                            xtype: 'container',
                            columnWidth: 0.5,
                            padding: 5,
                            layout: 'anchor',
                            defaultType: 'textfield'
                        },
                        items: [
                            {
                                defaults: {
                                    anchor: '100%'
                                },
                                items: [
                                    {
                                        fieldLabel: i18n('name'),
                                        name: 'name',
                                        allowBlank: false
                                    },
                                    {
                                        fieldLabel: i18n('phone'),
                                        name: 'phone'
                                    },
                                    {
                                        fieldLabel: i18n('fax'),
                                        name: 'fax'
                                    },
                                    {
                                        fieldLabel: i18n('street'),
                                        name: 'street'
                                    },
                                    {
                                        fieldLabel: i18n('city'),
                                        name: 'city'
                                    },
                                    {
                                        fieldLabel: i18n('state'),
                                        name: 'state'
                                    },
                                    {
                                        fieldLabel: i18n('postal_code'),
                                        name: 'postal_code'
                                    },
                                    {
                                        fieldLabel: i18n('country_code'),
                                        name: 'country_code'
                                    },
                                    {
                                        xtype: 'fieldcontainer',
                                        fieldLabel: i18n('tax_id'),
                                        layout: 'hbox',
                                        items: [
                                            {
                                                xtype: 'mitos.taxidcombo',
                                                name: 'tax_id_type',
                                                width: 50
                                            },
                                            {
                                                xtype: 'textfield',
                                                name: 'federal_ein'
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                items: [
                                    {
                                        xtype: 'mitos.checkbox',
                                        fieldLabel: i18n('active'),
                                        name: 'active'
                                    },
                                    {
                                        xtype: 'mitos.checkbox',
                                        fieldLabel: i18n('service_location'),
                                        name: 'service_location'
                                    },
                                    {
                                        xtype: 'mitos.checkbox',
                                        fieldLabel: i18n('billing_location'),
                                        name: 'billing_location'
                                    },
                                    {
                                        xtype: 'mitos.checkbox',
                                        fieldLabel: i18n('accepts_assignment'),
                                        name: 'accepts_assignment'
                                    },
                                    {
                                        xtype: 'mitos.poscodescombo',
                                        fieldLabel: i18n('pos_code'),
                                        name: 'pos_code',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: i18n('billing_attn'),
                                        name: 'attn',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: i18n('clia_number'),
                                        name: 'domain_identifier',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: 'Facility NPI',
                                        name: 'facility_npi',
                                        anchor: '100%'
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }),
            tbar: Ext.create('Ext.PagingToolbar', {
                pageSize: 30,
                store: me.FacilityStore,
                displayInfo: true,
                plugins: Ext.create('Ext.ux.SlidingPager', {
                    }),
                items: ['-', {
                    text: i18n('add_new_facility'),
                    iconCls: 'save',
                    scope: me,
                    handler: me.addFacility
                }, '-', {
                    text: i18n('show_active_facilities'),
                    action: 'active',
                    scope: me,
                    handler: me.filterFacilitiesby
                }, '-', {
                    text: i18n('show_inactive_facilities'),
                    action: 'inactive',
                    scope: me,
                    handler: me.filterFacilitiesby
                }]

            })
        });
        me.pageBody = [me.FacilityGrid];
        me.callParent(arguments);
    },
    filterFacilitiesby: function(btn){
        this.updateTitle(i18n('Facilities') + ' (' + Ext.String.capitalize(btn.action) + ')');
        this.FacilityStore.proxy.extraParams = {
            active: btn.action == 'active' ? 1 : 0
        };
        this.FacilityStore.load();
    },
    addFacility: function(){
        var me = this, grid = me.FacilityGrid, store = grid.store;
        grid.editingPlugin.cancelEdit();
        store.insert(0, {
            active: 1,
            service_location: 1,
            billing_location: 0,
            accepts_assignment: 0,
            tax_id_type: 'EIN'
        });
        grid.editingPlugin.startEdit(0, 0);
    },
    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        this.FacilityStore.load();
        callback(true);
    }
});
