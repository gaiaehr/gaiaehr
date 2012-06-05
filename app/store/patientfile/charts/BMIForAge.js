/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patientfile.charts.BMIForAge', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patientfile.charts.BMIForAge'],
	model   : 'App.model.patientfile.charts.BMIForAge'
});