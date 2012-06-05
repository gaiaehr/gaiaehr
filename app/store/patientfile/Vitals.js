/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patientfile.Vitals', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patientfile.Vitals'],
	pageSize: 10,
	model   : 'App.model.patientfile.Vitals'
});