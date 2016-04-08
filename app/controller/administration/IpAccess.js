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

Ext.define('App.controller.administration.IpAccess', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            ref:'IpAccessPanel',
            selector:'ipaccesspanel'
        }
    ],

    init: function() {
        var me = this;

        me.control({
            'ipaccesspanel #IpAccessRulesGrid':{
                activate: me.onIpAccessRulesGridActive
            },
            'ipaccesspanel #IpAccessLogGrid':{
                activate: me.onIpAccessLogGridActive
            },
            'ipaccesspanel #addIpRule':{
                click: me.onAddIpRuleClick
            }
        });

    },

    onAddIpRuleClick: function(btn){
        var grid = btn.down('grid'),
            store = grid.getStore();
        store.add();
    },

    onIpAccessRulesGridActive: function(grid){
        say('Hit: onIpAccessRulesGridActive');
        grid.getStore().load();
    },

    onIpAccessLogGridActive: function(grid){
        say('Hit: onIpAccessLogGridActive');
        grid.getStore().load();
    }

});
