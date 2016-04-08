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

Ext.define('App.view.administration.IpAccess', {
    extend: 'App.ux.RenderPanel',
    xtype: 'ipaccesspanel',
    itemId: 'IpAccessPanel',
    border: false,
    pageLayout: {
        type: 'vbox',
        align: 'stretch'
    },
    pageTitle: _('network_ip_access'),
    initComponent: function() {
        var me = this;

        me.IpAccessRulesStore = Ext.create('App.store.administration.IpAccessRules', {
            remoteFilter: false,
            autoLoad: false
        });

        me.IpAccessLogStore = Ext.create('App.store.administration.IpAccessLog', {
            remoteFilter: true,
            autoLoad: false
        });

        me.GeoIpLocation = Ext.create('App.store.administration.GeoIpLocation', {
            remoteFilter: true,
            autoLoad: false
        });

        me.pageBody = [
            {
                xtype: 'grid',
                flex: 1,
                frame: true,
                title: _('network_rules'),
                itemId: 'IpAccessRulesGrid',
                columnLines: true,
                store: me.IpAccessRulesStore,
                plugins: {
                    ptype: 'rowediting',
                    clicksToEdit: 2
                },
                columns: [
                    {
                        text: _('ip'),
                        dataIndex: 'ip',
                        width: 400,
                        editor: {
                            xtype: 'textfield',
                            allowBlank: false
                        }
                    },
                    {
                        text: _('country_code'),
                        dataIndex: 'country_code',
                        editor: {
                            xtype: 'combo',
                            allowBlank: false,
                            store: GeoIpLocation,
                            queryMode: 'remote',
                            displayField: 'country',
                            valueField: 'id'
                        }
                    },
                    {
                        text: _('rule'),
                        dataIndex: 'rule',
                        editor: {
                            xtype: 'combo',
                            allowBlank: false
                        }
                    },
                    {
                        text: _('active'),
                        dataIndex: 'active',
                        editor: {
                            xtype: 'checkboxfield'
                        }
                    }
                ],
                tbar: [
                    '->',
                    '-',
                    {
                        xtype: 'button',
                        text: _('add_new_ip_rule'),
                        itemId: 'addIpRule'
                    },
                    '-',
                    {
                        xtype: 'button',
                        text: _('delete_ip_rule'),
                        disabled: true,
                        itemId: 'deleteIpRule'
                    }
                ]
            },
            {
                xtype: 'grid',
                flex: 1,
                frame: true,
                title: _('network_log'),
                itemId: 'IpAccessLogGrid',
                columnLines: true,
                store: me.IpAccessLogStore,
                columns: [
                    {
                        text: _('ip'),
                        dataIndex: 'ip',
                        width: 400
                    },
                    {
                        text: _('country_code'),
                        dataIndex: 'country_code'
                    },
                    {
                        text: _('event'),
                        dataIndex: 'event'
                    },
                    {
                        text: _('date'),
                        dataIndex: 'create_date'
                    }
                ],
                dockedItems: [{
                    xtype: 'pagingtoolbar',
                    store: me.IpAccessLogStore,
                    dock: 'bottom',
                    displayInfo: true
                }]
            }
        ];

        me.callParent(arguments);

    }

});
