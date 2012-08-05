/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patient.charts.WeightForAge', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patient.charts.WeightForAge'],
	model   : 'App.model.patient.charts.WeightForAge'
});