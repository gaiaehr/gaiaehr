Ext.define('App.view.MainTabletView', {
    extend: 'Ext.Container',
    xtype: 'maintabletview',

    requires: [
        'Ext.navigation.Bar',
        'App.view.MainPanel',
        'App.view.NavPanel'
    ],

    config: {
        cls:'mainTabletView',
        layout: 'fit',
        items: [
            {
                xtype:'mainpanel'
            },
            {
                xtype:'navpanel',
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