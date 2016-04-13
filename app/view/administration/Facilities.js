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

Ext.define('App.view.administration.Facilities', {
    extend: 'App.ux.RenderPanel',
    pageTitle: _('facilities_active'),

    initComponent: function(){
        var me = this;

        // *************************************************************************************
        // Facilities Stores
        // *************************************************************************************
        me.FacilityStore = Ext.create('App.store.administration.Facility');

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
                items: [
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
                                        layout: 'hbox',
                                        items: [
                                            {
	                                            xtype: 'textfield',
	                                            fieldLabel: i18n('ssn'),
                                                name: 'ssn',
	                                            margin: '0 10 0 0'
                                            },
                                            {
                                                xtype: 'textfield',
	                                            fieldLabel: i18n('ein'),
	                                            labelWidth: 40,
                                                name: 'ein'
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                items: [
	                                {
		                                fieldLabel: i18n('billing_attn'),
		                                name: 'attn',
		                                anchor: '100%'
	                                },
                                    {
                                        xtype: 'mitos.poscodescombo',
                                        fieldLabel: i18n('pos_code'),
                                        name: 'pos_code',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: i18n('clia_number'),
                                        name: 'clia',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: i18n('npi'),
                                        name: 'npi',
                                        anchor: '100%'
                                    },
                                    {
                                        fieldLabel: i18n('fda_number'),
                                        name: 'fda',
                                        anchor: '100%'
                                    },
	                                {
		                                xtype: 'checkbox',
		                                fieldLabel: i18n('active'),
		                                name: 'active'
	                                },
	                                {
		                                xtype: 'checkbox',
		                                fieldLabel: i18n('service_location'),
		                                name: 'service_location'
	                                },
	                                {
		                                xtype: 'checkbox',
		                                fieldLabel: i18n('billing_location'),
		                                name: 'billing_location'
	                                },
	                                {
		                                xtype: 'checkbox',
		                                fieldLabel: i18n('accepts_assignment'),
		                                name: 'accepts_assignment'
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
        this.updateTitle(i18n('facilities') + ' (' + Ext.String.capitalize(btn.action) + ')');

        this.FacilityStore.load({
            filters:[
                {
                    property:'active',
                    value:btn.action == 'active' ? 1 : 0
                }
            ]
        });
    },

    addFacility: function(){
        var me = this,
	        grid = me.FacilityGrid,
	        store = grid.store;

	    grid.editingPlugin.cancelEdit();
        store.insert(0, {
            active: 1,
            service_location: 1,
            billing_location: 0,
            accepts_assignment: 0
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
