Ext.define('App.view.MainTablet', {
    extend: 'Ext.Container',
    xtype: 'maintabletview',

    requires: [
        'Ext.navigation.Bar',
        'App.view.PatientList',
        'App.view.Home',
        'App.view.PatientSummary'
    ],

    config: {
        cls:'mainTabletView',
        layout: 'fit',
        items: [
            {
                scrollable: false,
                action:'mainPanel',
                cls:'mainPanel',
                layout: {
                    type: 'card',
                    animation: {
                        type: 'slide',
                        direction: 'left',
                        duration: 250
                    }
                },
                defaults:{ padding:10, scrollable:true },
                items:[
                    {
                        title:'Home',
                        xtype:'homePanel'
                    },
                    {
                        title:'Patient Name',
                        scrollable:false,
                        xtype:'patientSummaryPanel'
                    },
                    {
                        title:'Patient Name',
                        xtype:'panel',
                        action:'pEncounter',
                        html:'<h1>Encounter Placeholder'
                    },
                    {
                        title:'Patient Name',
                        xtype:'panel',
                        action:'pDocuments',
                        html:'<h1>Documents Placeholder'
                    }
                ]
            },
            {
                xtype:'container',
                docked: 'left',
                layout:'vbox',
                cls:'leftNav',
                items:[
                    {
                        action: 'leftNavBar',
                        xtype : 'titlebar',
                        docked: 'top',
                        title : 'Patients'
                    },
                    {
                        xtype : 'patientlist',
                        width:350,
                        flex:1
                    }
                ]

            },
            {
                action: 'mainNavBar',
                xtype : 'titlebar',
                docked: 'top',
                title : 'Home',
                items: [
                    {
                        iconCls: 'reply',
                        iconMask: true,
                        align: 'left',
                        ui:'back',
                        action:'back',
                        hidden:true
                    },
                    {
                        iconCls: 'home',
                        iconMask: true,
                        align: 'left',
                        action:'home',
                        hidden:true
                    },
                    {
                        iconCls: 'action',
                        iconMask: true,
                        ui:'decline',
                        align: 'right',
                        action:'logout'
                    }
                ]
            }
        ]
    }
});