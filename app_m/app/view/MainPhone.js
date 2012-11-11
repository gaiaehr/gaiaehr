Ext.define('App.view.MainPhone', {
    extend: 'Ext.dataview.NestedList',
    requires: ['Ext.TitleBar'],

    id: 'mainNestedList',

    config: {
        fullscreen: true,
        title: 'GaiaEHR Air',
        useTitleAsBackText: false,
        layout: {
            animation: {
                duration: 250,
                easing: 'ease-in-out'
            }
        },

        store: 'Patients',

        toolbar: {
            id: 'mainNavigationBar',
            xtype : 'titlebar',
            docked: 'top',
            title : 'Welcome'
        }
    }
});