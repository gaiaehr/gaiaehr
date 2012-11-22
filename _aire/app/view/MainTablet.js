Ext.define('App.view.MainTablet', {
    extend: 'Ext.Container',
    xtype: 'maintabletview',

    requires: [
        'Ext.navigation.Bar',
        'App.view.LeftNav',
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
//                style: 'width: 100%; height: 100%; position: absolute; opacity: 1; z-index: 5',
//                draggable: {
//                    direction: 'horizontal',
//                    constraint: {
//                        min: { x: -270, y: 0 },
//                        max: { x: 0, y: 0 }
//                    }
//                },
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
                xtype:'leftNav',
                docked: 'left'
            },
            {
                action: 'mainTitleBar',
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