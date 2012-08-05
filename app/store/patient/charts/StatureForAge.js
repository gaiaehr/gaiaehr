/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patient.charts.StatureForAge', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patient.charts.StatureForAge'],
	model   : 'App.model.patient.charts.StatureForAge'
});