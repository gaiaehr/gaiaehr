/**
 * AddressBook Panel
 *
 * Author: Ernesto J Rodriguez
 * Modified: GI Technologies, 2011
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace AddressBook.getAddresses
 * @namespace AddressBook.addContact
 * @namespace AddressBook.updateAddress
 *
 */
Ext.define('App.view.miscellaneous.Addressbook', {
    extend: 'App.ux.RenderPanel',
    id: 'panelAddressbook',
    pageTitle: i18n('address_book'),
    uses: ['App.ux.GridPanel', 'App.ux.combo.Titles', 'App.ux.window.Window', 'App.ux.combo.Types'],
    initComponent: function(){
        var me = this;
        var currRec;
        /**
         * Addresses Store and Model
         */
        Ext.define('addressBookModel', {
            extend: 'Ext.data.Model',
            fields: [
                {
                    name: 'id',
                    type: 'int'
                },
                {
                    name: 'username',
                    type: 'string'
                },
                {
                    name: 'password',
                    type: 'string'
                },
                {
                    name: 'authorized',
                    type: 'string'
                },
                {
                    name: 'info',
                    type: 'string'
                },
                {
                    name: 'source',
                    type: 'int'
                },
                {
                    name: 'fname',
                    type: 'string'
                },
                {
                    name: 'mname',
                    type: 'string'
                },
                {
                    name: 'lname',
                    type: 'string'
                },
                {
                    name: 'fullname',
                    type: 'string'
                },
                {
                    name: 'federaltaxid',
                    type: 'string'
                },
                {
                    name: 'federaldrugid',
                    type: 'string'
                },
                {
                    name: 'upin',
                    type: 'string'
                },
                {
                    name: 'facility',
                    type: 'string'
                },
                {
                    name: 'facility_id',
                    type: 'int'
                },
                {
                    name: 'see_auth',
                    type: 'int'
                },
                {
                    name: 'active',
                    type: 'int'
                },
                {
                    name: 'npi',
                    type: 'string'
                },
                {
                    name: 'title',
                    type: 'string'
                },
                {
                    name: 'specialty',
                    type: 'string'
                },
                {
                    name: 'billname',
                    type: 'string'
                },
                {
                    name: 'email',
                    type: 'string'
                },
                {
                    name: 'url',
                    type: 'string'
                },
                {
                    name: 'assistant',
                    type: 'string'
                },
                {
                    name: 'organization',
                    type: 'string'
                },
                {
                    name: 'valedictory',
                    type: 'string'
                },
                {
                    name: 'fulladdress',
                    type: 'string'
                },
                {
                    name: 'street',
                    type: 'string'
                },
                {
                    name: 'streetb',
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
                    name: 'zip',
                    type: 'string'
                },
                {
                    name: 'street2',
                    type: 'string'
                },
                {
                    name: 'streetb2',
                    type: 'string'
                },
                {
                    name: 'city2',
                    type: 'string'
                },
                {
                    name: 'state2',
                    type: 'string'
                },
                {
                    name: 'zip2',
                    type: 'string'
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
                    name: 'phonew1',
                    type: 'string'
                },
                {
                    name: 'phonew2',
                    type: 'string'
                },
                {
                    name: 'phonecell',
                    type: 'string'
                },
                {
                    name: 'notes',
                    type: 'string'
                },
                {
                    name: 'cal_ui',
                    type: 'string'
                },
                {
                    name: 'taxonomy',
                    type: 'string'
                },
                {
                    name: 'ssi_relayhealth',
                    type: 'string'
                },
                {
                    name: 'calendar',
                    type: 'int'
                },
                {
                    name: 'abook_type',
                    type: 'string'
                },
                {
                    name: 'pwd_expiration_date',
                    type: 'string'
                },
                {
                    name: 'pwd_history1',
                    type: 'string'
                },
                {
                    name: 'pwd_history2',
                    type: 'string'
                },
                {
                    name: 'default_warehouse',
                    type: 'string'
                }
            ],
            proxy: {
                type: 'direct',
                api: {
                    read: AddressBook.getAddresses,
                    create: AddressBook.addContact,
                    update: AddressBook.updateAddress
                },
                reader: {
                    totalProperty: 'totals',
                    root: 'rows'
                }
            }
        });
        me.store = Ext.create('Ext.data.Store', {
            model: 'addressBookModel',
            remoteSort: false
        });
        /**
         * Window and form
         */
        me.win = Ext.create('App.ux.window.Window', {
            width: 755,
            title: i18n('add_or_edit_contact'),
            items: [
                {
                    xtype: 'mitos.form',
                    items: [
                        {
                            xtype: 'fieldset',
                            title: i18n('primary_info'),
                            collapsible: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    defaults: {
                                        hideLabel: true
                                    },
                                    msgTarget: 'under',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Type: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'mitos.typescombobox'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    defaults: {
                                        hideLabel: true
                                    },
                                    msgTarget: 'under',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'First, Middle, Last: '
                                        },
                                        {
                                            width: 55,
                                            xtype: 'mitos.titlescombo',
                                            name: 'title'
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'fname'
                                        },
                                        {
                                            width: 100,
                                            xtype: 'textfield',
                                            name: 'mname'
                                        },
                                        {
                                            width: 280,
                                            xtype: 'textfield',
                                            name: 'lname'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    msgTarget: 'side',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Specialty: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'specialty'
                                        },
                                        {
                                            width: 90,
                                            xtype: 'displayfield',
                                            value: 'Organization: '
                                        },
                                        {
                                            width: 120,
                                            xtype: 'textfield',
                                            name: 'organization'
                                        },
                                        {
                                            width: 80,
                                            xtype: 'displayfield',
                                            value: 'Valedictory: '
                                        },
                                        {
                                            width: 135,
                                            xtype: 'textfield',
                                            name: 'valedictory'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: i18n('primary_address'),
                            collapsible: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Address: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'street'
                                        },
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Addrress Cont: '
                                        },
                                        {
                                            width: 335,
                                            xtype: 'textfield',
                                            name: 'streetb'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'City: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'city'
                                        },
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'State: '
                                        },
                                        {
                                            width: 120,
                                            xtype: 'textfield',
                                            name: 'state'
                                        },
                                        {
                                            width: 80,
                                            xtype: 'displayfield',
                                            value: 'Postal Code: '
                                        },
                                        {
                                            width: 125,
                                            xtype: 'textfield',
                                            name: 'zip'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: i18n('secondary_address'),
                            collapsible: true,
                            collapsed: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Address: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'street2'
                                        },
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Cont.: '
                                        },
                                        {
                                            width: 335,
                                            xtype: 'textfield',
                                            name: 'streetb2'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'City: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'city2'
                                        },
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'State: '
                                        },
                                        {
                                            width: 120,
                                            xtype: 'textfield',
                                            name: 'state2'
                                        },
                                        {
                                            width: 80,
                                            xtype: 'displayfield',
                                            value: 'Postal Code: '
                                        },
                                        {
                                            width: 125,
                                            xtype: 'textfield',
                                            name: 'zip2'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: i18n('phone_numbers'),
                            collapsible: true,
                            collapsed: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Home Phone: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'phone'
                                        },
                                        {
                                            width: 90,
                                            xtype: 'displayfield',
                                            value: 'Mobile Phone: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'phonecell'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Work Phone: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'phonew1'
                                        },
                                        {
                                            width: 90,
                                            xtype: 'displayfield',
                                            value: 'Work Phone: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'phonew2'
                                        },
                                        {
                                            width: 60,
                                            xtype: 'displayfield',
                                            value: 'FAX: '
                                        },
                                        {
                                            width: 140,
                                            xtype: 'textfield',
                                            name: 'fax'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: i18n('online_info'),
                            collapsible: true,
                            collapsed: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 100,
                                            xtype: 'displayfield',
                                            value: 'Email: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'email'
                                        },
                                        {
                                            width: 90,
                                            xtype: 'displayfield',
                                            value: 'Assistant: '
                                        },
                                        {
                                            width: 130,
                                            xtype: 'textfield',
                                            name: 'assistant'
                                        },
                                        {
                                            width: 60,
                                            xtype: 'displayfield',
                                            value: 'Website: '
                                        },
                                        {
                                            width: 140,
                                            xtype: 'textfield',
                                            name: 'url'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'fieldset',
                            title: i18n('other_info'),
                            collapsible: true,
                            collapsed: true,
                            defaultType: 'textfield',
                            layout: 'anchor',
                            defaults: {
                                labelWidth: 89,
                                anchor: '100%',
                                layout: {
                                    type: 'hbox',
                                    defaultMargins: {
                                        top: 0,
                                        right: 5,
                                        bottom: 0,
                                        left: 0
                                    }
                                }
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    items: [
                                        {
                                            width: 50,
                                            xtype: 'displayfield',
                                            value: 'UPIN: '
                                        },
                                        {
                                            width: 80,
                                            xtype: 'textfield',
                                            name: 'upin'
                                        },
                                        {
                                            width: 50,
                                            xtype: 'displayfield',
                                            value: 'NPI: '
                                        },
                                        {
                                            width: 80,
                                            xtype: 'textfield',
                                            name: 'npi'
                                        },
                                        {
                                            width: 50,
                                            xtype: 'displayfield',
                                            value: 'TIN: '
                                        },
                                        {
                                            width: 80,
                                            xtype: 'textfield',
                                            name: 'federaltaxid'
                                        },
                                        {
                                            width: 80,
                                            xtype: 'displayfield',
                                            value: 'Taxonomy: '
                                        },
                                        {
                                            width: 90,
                                            xtype: 'textfield',
                                            name: 'taxonomy'
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            width: 720,
                            xtype: 'htmleditor',
                            name: 'notes',
                            emptyText: i18n('notes')
                        }
                    ]
                }
            ],
            buttons: [
                {
                    text: i18n('save'),
                    scope: me,
                    handler: me.onAddressSave
                },
                {
                    text: i18n('cancel'),
                    scope: me,
                    handler: me.onCancel
                }
            ],
            listeners: {
                close: me.onWinClose
            }
        });
        // END WINDOW
        // *************************************************************************************
        // Create the GridPanel
        // *************************************************************************************
        me.grid = Ext.create('Ext.grid.GridPanel', {
            store: me.store,
            layout: 'fit',
            frame: true,
            loadMask: true,
            viewConfig: {
                stripeRows: true
            },
            listeners: {
                scope: me,
                itemclick: me.girdItemclick,
                itemdblclick: me.gridItemdblclick

            },
            columns: [
                {
                    header: i18n('name'),
                    width: 150,
                    sortable: true,
                    dataIndex: 'fullname'
                },
                {
                    header: i18n('local'),
                    width: 50,
                    sortable: true,
                    dataIndex: 'username',
                    renderer: me.local
                },
                {
                    header: i18n('type'),
                    sortable: true,
                    dataIndex: 'ab_title'
                },
                {
                    header: i18n('specialty'),
                    sortable: true,
                    dataIndex: 'specialty'
                },
                {
                    header: i18n('work_phone'),
                    sortable: true,
                    dataIndex: 'phonew1'
                },
                {
                    header: i18n('mobile'),
                    sortable: true,
                    dataIndex: 'phonecell'
                },
                {
                    header: i18n('fax'),
                    sortable: true,
                    dataIndex: 'fax'
                },
                {
                    header: i18n('email'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'email'
                },
                {
                    header: i18n('primary_address'),
                    flex: 1,
                    sortable: true,
                    dataIndex: 'fulladdress'
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            text: i18n('add_contact'),
                            iconCls: 'icoAddressBook',
                            scope: me,
                            handler: me.onAddContact
                        }
                    ]
                }
            ]
        });
        me.pageBody = [me.grid];
        me.callParent(arguments);
    },
    onAddContact: function(){
        this.win.show();
    },
    onAddressSave: function(btn){
        var me = this, win = btn.up('window'), form = win.down('form').getForm(), store = me.store;
        if(form.isValid()){
            var record = form.getRecord(), values = form.getValues(), storeIndex = store.indexOf(record);
            if(storeIndex == -1){
                store.add(values);
            }else{
                record.set(values);
            }
            store.sync();
            store.load();
            win.close();
            me.msg('Sweet!', i18n('message_sent'));
        }
    },
    onCancel: function(){
        this.win.close();
    },
    girdItemclick: function(grid, record){
    },
    gridItemdblclick: function(grid, record){
        this.win.down('form').getForm().loadRecord(record);
        this.win.show();
    },
    onWinClose: function(window){
        window.down('form').getForm().reset();
    },
    onCopyClipBoard: function(company){
        var store = Ext.getCmp('grid').store;
        var record = store.getById(company);
        var s = '';
        for(key in record.data){
            s += key + ': ' + record.data[key] + '\n';
        }
        alert(i18n('following_data_copied_to_clipboard') + ':\n\n' + s);
        if(window.clipboardData){
            window.clipboardData.setData('text', s);
        }else{
            return (s);
        }
    },
    local: function(val){
        if(val !== ''){
            return '<img src="resources/images/icons/yes.gif" />';
        }
        return val;
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
