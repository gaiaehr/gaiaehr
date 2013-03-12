/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

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

Ext.define('App.view.administration.Users', {
    extend: 'App.ux.RenderPanel',
    id: 'panelUsers',
    pageTitle: i18n('users'),

    initComponent: function(){
        var me = this;

        me.userStore = Ext.create('App.store.administration.User',{
            autoSync:false
        });

        me.userGrid = Ext.create('Ext.grid.Panel', {
            store: me.userStore,
            columns: [
                {
                    text: 'id',
                    sortable: false,
                    dataIndex: 'id',
	                width: 25
                },
                {
                    width: 100,
                    text: i18n('username'),
                    sortable: true,
                    dataIndex: 'username'
                },
                {
                    width: 200,
                    text: i18n('name'),
                    sortable: true,
                    dataIndex: 'fullname'
                },
                {
                    flex: 1,
                    text: i18n('aditional_info'),
                    sortable: true,
                    dataIndex: 'info'
                },
                {
                    text: i18n('active'),
                    sortable: true,
                    dataIndex: 'active',
                    renderer: me.boolRenderer
                },
                {
                    text: i18n('authorized'),
                    sortable: true,
                    dataIndex: 'authorized',
                    renderer: me.boolRenderer
                },
                {
                    text: i18n('calendar_q'),
                    sortable: true,
                    dataIndex: 'calendar',
                    renderer: me.boolRenderer
                }
            ],
	        plugins:[
		        me.formEditing = Ext.create('App.ux.grid.RowFormEditing',{
			        clicksToEdit:1,
			        formItems:[
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        msgTarget: 'under',
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('username') + ': '
						        },
						        {
							        width: 180,
							        xtype: 'textfield',
							        name: 'username',
							        allowBlank: false,
							        validateOnBlur:true,
							        vtype:'usernameField'
						        },
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('password') + ': '
						        },
						        {
							        width: 175,
							        xtype: 'textfield',
							        name: 'password',
							        inputType: 'password'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        msgTarget: 'under',
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('first_middle_last')
						        },
						        {
							        width: 50,
							        xtype: 'mitos.titlescombo',
							        name: 'title'
						        },
						        {
							        width: 150,
							        xtype: 'textfield',
							        name: 'fname',
							        allowBlank: false
						        },
						        {
							        width: 100,
							        xtype: 'textfield',
							        name: 'mname'
						        },
						        {
							        width: 150,
							        xtype: 'textfield',
							        name: 'lname'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        msgTarget: 'under',
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        items: [
						        {
							        width: 150,
							        xtype: 'mitos.checkbox',
							        fieldLabel: i18n('active'),
							        name: 'active'
						        },
						        {
							        width: 150,
							        xtype: 'mitos.checkbox',
							        fieldLabel: i18n('authorized'),
							        name: 'authorized'
						        },
						        {
							        width: 150,
							        xtype: 'mitos.checkbox',
							        fieldLabel: i18n('calendar_q'),
							        name: 'calendar'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        msgTarget: 'under',
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('default_facility') + ': '
						        },
						        {
							        width: 180,
							        xtype: 'mitos.facilitiescombo',
							        name: 'facility_id'
						        },
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('authorizations') + ': '
						        },
						        {
							        width: 175,
							        xtype: 'mitos.authorizationscombo',
							        name: 'see_auth'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('access_control') + ': '
						        },
						        {
							        width: 180,
							        xtype: 'mitos.rolescombo',
							        name: 'role_id',
							        allowBlank: false
						        },
						        // not implemented yet
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('taxonomy') + ': '
						        },
						        {
							        width: 175,
							        xtype: 'textfield',
							        name: 'taxonomy'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('federal_tax_id') + ': '
						        },
						        {
							        width: 180,
							        xtype: 'textfield',
							        name: 'federaltaxid'
						        },
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('fed_drug_id') + ': '
						        },
						        {
							        width: 175,
							        xtype: 'textfield',
							        name: 'federaldrugid'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('upin') + ': '
						        },
						        {
							        width: 180,
							        xtype: 'textfield',
							        name: 'upin'
						        },
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('npi') + ': '
						        },
						        {
							        width: 175,
							        xtype: 'textfield',
							        name: 'npi'
						        }
					        ]
				        },
				        {
					        xtype: 'fieldcontainer',
					        defaults: {
						        hideLabel: true
					        },
					        layout: {
						        type: 'hbox',
						        defaultMargins: {
							        top: 0,
							        right: 5,
							        bottom: 0,
							        left: 0
						        }
					        },
					        items: [
						        {
							        width: 100,
							        xtype: 'displayfield',
							        value: i18n('job_description') + ': '
						        },
						        {
							        width: 465,
							        xtype: 'textfield',
							        name: 'specialty'
						        }
					        ]
				        },
				        {
					        width: 570,
					        height: 50,
					        xtype: 'textfield',
					        name: 'info',
					        emptyText: i18n('additional_info')
				        }
			        ]
		        })
	        ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            text: i18n('add_new_user'),
                            iconCls: 'save',
	                        scope:me,
                            handler: me.onNewUser
                        }
                    ]
                }
            ]
        });

        me.pageBody = [ me.userGrid ];
        me.callParent(arguments);

    },

	onNewUser: function(){
	    var me = this;

		me.formEditing.cancelEdit();
        me.userStore.insert(0,{
	        create_date: new Date(),
	        update_date: new Date(),
	        create_uid: app.user.id,
	        update_uid: app.user.id,
	        active: 1,
	        authorized: 0,
	        calendar: 0
        });
	    me.formEditing.startEdit(0,0);
    },

    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        this.userStore.load();
        callback(true);
    }
});
