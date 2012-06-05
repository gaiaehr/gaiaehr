/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patientfile.Encounters', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patientfile.Encounters'],
	pageSize: 25,
	model   : 'App.model.patientfile.Encounters',
    remoteSort:true
});