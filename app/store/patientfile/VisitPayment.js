/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 4/13/12
 * Time: 11:32 PM
 */

Ext.define('App.store.patientfile.VisitPayment', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patientfile.VisitPayment'],
	model   : 'App.model.patientfile.VisitPayment'
});