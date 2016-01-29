Ext.define('App.view.Login', {
    extend: 'Ext.TabPanel',
    xtype: 'loginWindow',
    requires: [
        'Ext.Img',
        'Ext.field.*',
        'Ext.form.*'
    ],
    config: {
        tabBar:{
            docked:'bottom',
            layout:{
                pack:'center'
            }
        },
        items:[
            {
                title:'Login',
                iconCls: 'user',
                xtype:'formpanel',
                action:'login',
                items: [
                    {
                        xtype: 'image',
                        src: 'resources/images/gaiaehr_aire.png',
                        height: 86,
                        margin: '0 0 20 0'
                    },
                    {
                        xtype: 'fieldset',
                        instructions: 'Welcome to GaiaEHR Mobile',
                        items: [
                            {
                                xtype: 'textfield',
                                name : 'authUser',
                                label: 'Username',
                                value: 'admin'
                            },
                            {
                                xtype: 'passwordfield',
                                name: 'authPass',
                                label: 'Password',
                                value: 'pass'
                            },
                            {
                                xtype: 'selectfield',
                                name: 'lang',
                                label: 'Language',
                                valueField: 'value',
                                displayField: 'title',
                                store: {
                                    data: [
                                        { title: 'English (US)', value: 'en_US'},
                                        { title: 'Spanish', value: 'es'}
                                    ]
                                }
                            }
                        ]
                    },
                    {
                        xtype:'button',
                        text:'Submit',
                        ui: 'confirm',
                        action:'login'
                    }
                ]
            },
            {
                title:'Settings',
                iconCls: 'settings',
                xtype:'formpanel',
                action:'settings',
                items: [
                    {
                        xtype: 'image',
                        src: 'resources/images/gaiaehr_aire.png',
                        height: 86
                    },
                    {
                        xtype: 'fieldset',
                        action:'settings',
                        instructions: '1. Site ID: If you dont have a Site ID leave the default value<br>' +
                            '2. URL: Type server URL without extra parameters.<br>' +
                            'Example: http://www.server.com/ or http://www.server.com/gaiaehr/<br>' +
                            '3. Key: You can generate a Key from Administration->Applications',
                        items: [
                            {
                                xtype: 'textfield',
                                name : 'site',
                                label: 'Site ID'
                            },
                            {
                                xtype: 'textfield',
                                name: 'url',
                                label: 'Server URL'
                            },
                            {
                                xtype: 'textfield',
                                name: 'pvtKey',
                                label: 'Key',
                                action: 'pvtKey'
                            }
                        ]
                    }
                ]
            }
        ]
    }
});
