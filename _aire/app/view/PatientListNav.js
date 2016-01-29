/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientListNav', {
    extend: 'Ext.List',
    xtype:'patientlistnav',
    requires:[
        'App.store.Patients',
        'Ext.plugin.PullRefresh'
    ],
    config: {
        store: Ext.create('App.store.Patients'),
        limit: 20,
        grouped     : true,
        plugins: [
            { xclass: 'Ext.plugin.PullRefresh' }
        ],

        emptyText: '<p class="no-searches">No Patients found</p>',

        itemTpl: Ext.create('Ext.XTemplate',
            '<div class="patientList">',
            '   <img src="{photoSrc}" width="80" height="80"/>',
            '   <div class="info">',
            '       <p>{name}</p>',
            '       <p>#{pid}</p>',
            '   </div>',
            '</div>'
        )
    }
});