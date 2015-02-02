/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.MedicalRecordMenu', {
    extend: 'Ext.List',
    xtype:'medicalrecordmenu',
    requires:[
        'App.store.Patients',
        'Ext.plugin.PullRefresh'
    ],
    cls:'MedicalRecordMenu',
    config: {
        data: [
            {
                text:'Demographics',
                action:'patientdemographicspanel',
                cls:''
            },
            {
                text:'Progress Notes',
                action:'patientprogressnotespanel',
                cls:''
            },
            {
                text:'Charts',
                action:'patientchartspanel',
                cls:''
            },
            {
                text:'Images',
                action:'patientimagespanel',
                cls:''
            },
            {
                text:'Documents',
                action:'patientdocumentspanel',
                cls:''
            },
            {
                text:'Lab Results',
                action:'patientlabresultspanel',
                cls:''
            },
            {
                text:'Clinical Orders',
                action:'patientclinicalorderspanel',
                cls:''
            }
        ],
        itemTpl: Ext.create('Ext.XTemplate',
            '<div class="MedicalRecordMenuList {action}">{text}</div>'
        )
    }
});