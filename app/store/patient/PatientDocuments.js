/*
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patient.PatientDocuments', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patient.PatientDocuments',
	remoteSort: false,
	autoLoad  : false,
	autoSync:true
});