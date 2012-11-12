/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientList', {
    extend: 'Ext.List',
    xtype:'patientlist',

    config: {
        store: Ext.create('App.store.Patients'),
        limit: 20,

        plugins: [
            { xclass: 'Ext.plugin.ListPaging' },
            { xclass: 'Ext.plugin.PullRefresh' }
        ],

        emptyText: '<p class="no-searches">No Patients found</p>',

        itemTpl: Ext.create('Ext.XTemplate',
            '<img src="{profile_image_url}" />',
            '<div class="tweet">',
            '<h2>{from_user}</h2>',
            '<p>{text}</p>',
            '</div>'
        )
    }
});