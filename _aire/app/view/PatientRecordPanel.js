/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/19/12
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientRecordPanel',{
    extend:'Ext.Panel',
    xtype:'patientrecordpanel',
    config:{
        nav: 'medicalrecordnav',
        tier: 3,
        html:'<p>PatientRecordPanel</p>'
    }
});