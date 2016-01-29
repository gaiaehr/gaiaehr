/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.view.administration.Modules', {
    extend: 'App.ux.RenderPanel',
    id: 'panelModules',
    pageTitle: _('modules'),
    initComponent: function(){
        var me = this;

        // *************************************************************************************
        // Module Data Store
        // *************************************************************************************
        me.store = Ext.create('App.store.administration.Modules');

        me.grid = Ext.create('Ext.grid.Panel', {
            store: me.store,
            plugins: [
                me.edditing = Ext.create('Ext.grid.plugin.RowEditing', {
                    clicksToEdit: 2,
                    errorSummary : false
                })
            ],
            columns: [
                {
                    text: _('title'),
                    width: 200,
                    sortable: true,
                    dataIndex: 'title'
                },
                {
                    text: _('description'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'description'
                },
                {
                    text: _('version'),
                    width: 100,
                    sortable: true,
                    dataIndex: 'installed_version'
                },
                {
                    text: _('key_if_required'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'licensekey',
                    editor:{
                        xtype:'textfield'
                    }
                },
                {
                    text: _('enabled?'),
                    width: 60,
                    sortable: true,
                    renderer: me.boolRenderer,
                    dataIndex: 'enable',
                    editor:{
                        xtype:'checkbox'
                    }
                }
            ]
        });
        me.pageBody = [ me.grid ];
        me.callParent(arguments);
    },


    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        this.store.load();
        callback(true);
    }
});
