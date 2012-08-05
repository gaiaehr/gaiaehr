/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patient.Encounter', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patient.Encounter'],
	pageSize: 10,
	model   : 'App.model.patient.Encounter'
});