/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.MedicalRecordNav', {
    extend: 'Ext.Container',
    xtype:'medicalRecordNav',
    requires:[
        'App.view.MedicalRecordMenu'
    ],
    cls:'medicalRecordNav',
    config: {
        layout:'vbox',
        items:[
            {
                xtype:'container',
                action:'medicalRecordNavHeader',
                data:{
                    pid:'1'
                },
                tpl: Ext.create('Ext.XTemplate',
                    '<div class="medicalRecordNavHeader">',
                    '   <img src="http://localhost/gaiaehr/sites/default/patients/{pid}/patientPhotoId.jpg{photoSrc}" width="80" height="80"/>',
                    '</div>'
                )
            },
            {
                xtype:'medicalRecordMenu',
                flex:1
            }
        ]
    }
});