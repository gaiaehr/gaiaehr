Ext.define('App.view.MainPhone', {
    extend: 'Ext.Container',
    xtype: 'mainphoneview',
    requires: ['Ext.TitleBar'],

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