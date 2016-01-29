Ext.define('App.view.MainPhoneView', {
    extend: 'Ext.Container',
    xtype: 'mainphoneview',
    requires: [
        'Ext.TitleBar',
        'App.view.PatientListNav'

    ],

    config: {
        layout:{
            type:'vbox',
            align:'stretch'
        },
        items:[
            {
                xtype : 'titlebar',
                title : 'Patients',
                items: [
                    {
                        iconCls: 'home',
                        iconMask: true,
                        align: 'left',
                        action:'home'
                    },
                    {
                        ui:'decline',
                        text: 'Logout',
                        align: 'right',
                        action:'logout'
                    }
                ]
            },
            {
                xtype : 'patientlist',
                flex:1
            }
        ]
    }
});