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
                        src: '../resources/images/gaiaehr_aire.png',
                        height: 86,
                        margin: '0 0 20 0'
                    },
                    {
                        xtype: 'fieldset',
                        instructions: 'Welcome to GaiaEHR Mobile',
                        items: [
                            {
                                xtype: 'textfield',
                                name : 'username',
                                label: 'Username'
                            },
                            {
                                xtype: 'passwordfield',
                                name: 'password',
                                label: 'Password'
                            },
                            {
                                xtype: 'selectfield',
                                name: 'lang',
                                label: 'Language',
                                valueField: 'value',
                                displayField: 'title',
                                store: {
                                    data: [
                                        { title: 'English (US)', value: 'Master'},
                                        { title: 'Spanish', value: 'Student'}
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
                        src: '../resources/images/gaiaehr_aire.png',
                        height: 86
                    },
                    {
                        xtype: 'fieldset',
                        action:'settings',
                        instructions: '1. Site ID: If you dont have a Site ID leave the default value<br>' +
                            '2. URL: Type server URL without extra parameters. Example: http://www.server.com<br>' +
                            '3. Pvt Key: You can generate a Pvt Key from menu administration -> Applications',
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
                                label: 'Pvt Key',
                                action: 'pvtKey'
                            }
                        ]
                    }
                ]
            }
        ]
    }
});
