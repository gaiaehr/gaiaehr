Ext.define('App.view.Login', {
    extend: 'Ext.Panel',
    xtype: 'loginWindow',
    requires: [
        'Ext.Img',
        'Ext.field.*',
        'Ext.form.*'
    ],
    config: {
        scrollable: true,
        layout:'fit',
        items:[
            {
                xtype:'formpanel',
                items: [
                    {
                        xtype: 'image',
                        src: '../resources/images/gaiaehr_aire.png',
                        height: 86
                    },
                    {
                        xtype: 'fieldset',
                        title: 'User Loin',
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
            }
        ]
    }
});
