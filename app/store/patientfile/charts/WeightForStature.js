/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patientfile.charts.WeightForStature', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patientfile.charts.WeightForStature'],
	model   : 'App.model.patientfile.charts.WeightForStature'
});