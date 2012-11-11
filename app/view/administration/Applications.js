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
Ext.define('App.view.administration.Applications', {
    extend: 'App.ux.RenderPanel',
    id: 'panelApplications',
    pageTitle: i18n('applications'),
    initComponent: function(){
        var me = this;
        Ext.define('ApplicationsModel', {
            extend: 'Ext.data.Model',
            fields: [
                { name: 'id', type: 'int' },
                { name: 'app_name', type: 'string' },
                { name: 'pvt_key', type: 'string' },
                { name: 'active', type: 'bool' }
            ],
            proxy: {
                type: 'direct',
                api: {
                    read: Applications.getApplications,
                    create: Applications.addApplication,
                    update: Applications.updateApplication,
                    destroy: Applications.deleteApplication
                }
            }
        });
        me.store = Ext.create('Ext.data.Store', {
            model: 'ApplicationsModel',
            remoteSort: false
        });
        // *************************************************************************************
        // Facility Grid Panel
        // *************************************************************************************
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
                    xtype:'actioncolumn',
                    width:20,
                    items: [
                        {
                            icon: 'resources/images/icons/cross.png',  // Use a URL in the icon config
                            tooltip: 'Remove',
                            scope:me,
                            handler: me.removeApplication
                        }
                    ]

                },
                {
                    text: i18n('name'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'app_name',
                    editor:{
                        xtype:'textfield',
                        allowBlank:false
                    }
                },
                {
                    text: i18n('private_key'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'pvt_key'
                },
                {
                    text: i18n('active?'),
                    width: 50,
                    sortable: true,
                    renderer: me.boolRenderer,
                    dataIndex: 'active',
                    editor:{
                        xtype:'checkbox'
                    }
                }
            ],
            tbar:[
                {
                    text:i18n('add'),
                    iconCls:'icoAdd',
                    scope:me,
                    handler:me.addApplication
                }
            ]
        });
        me.pageBody = [me.grid];
        me.callParent(arguments);
    },

    removeApplication:function(grid, rowIndex, colIndex){
        var me = this,
            record = me.store.getAt(rowIndex);
        Ext.Msg.show({
            title:'Wait!',
            msg: 'This action is final. Are you sure you want to remove <span style="font-weight: bold">"'+record.data.app_name+'"</span>?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.WARNING,
            fn:function(btn){
                if(btn == 'yes'){
                    me.edditing.cancelEdit();
                    me.store.remove(record);
                    me.store.sync({
                        callback:function(){
                            me.msg('Sweet!', i18n('record_removed'))
                        }
                    });
                }
            }
        });
    },

    addApplication:function(){
        var me = this;
        me.edditing.cancelEdit();
        me.store.insert(0,{active:1});
        me.edditing.startEdit(0,0);
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
