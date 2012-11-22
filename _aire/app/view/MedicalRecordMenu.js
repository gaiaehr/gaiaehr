/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.MedicalRecordMenu', {
    extend: 'Ext.List',
    xtype:'medicalRecordMenu',
    requires:[
        'App.store.Patients',
        'Ext.plugin.PullRefresh'
    ],
    cls:'MedicalRecordMenu',
    config: {
        data: [
            {
                text:'Demographics',
                action:'demographics',
                cls:''
            },
            {
                text:'Progress Notes',
                action:'progressNotes',
                cls:''
            },
            {
                text:'Images',
                action:'images',
                cls:''
            },
            {
                text:'Documents',
                action:'documents',
                cls:''
            },
            {
                text:'Lab Results',
                action:'labResults',
                cls:''
            },
            {
                text:'Clinical Orders',
                action:'clinicalOrders',
                cls:''
            }
        ],
        itemTpl: Ext.create('Ext.XTemplate',
            '<div class="MedicalRecordMenuList {action}">{text}</div>'
        )
    }
});