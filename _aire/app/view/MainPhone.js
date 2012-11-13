Ext.define('App.view.MainPhone', {
    extend: 'Ext.Container',
    requires: ['Ext.TitleBar'],

    config: {
        layout:{
            type:'vbox',
            align:'stretch'
        },
        items:[
            {
                xtype : 'titlebar',
                title : 'Patients'
            },
            {
                xtype : 'patientlist',
                flex:1
            }
        ]
    }
});