/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/19/12
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientDocumentsPanel',{
    extend:'Ext.Panel',
    xtype:'patientdocumentspanel',
    config:{
        nav: 'medicalrecordnav',
        tier:4,
        html:'<p>PatientDocumentsPanel</p>'
    }
});