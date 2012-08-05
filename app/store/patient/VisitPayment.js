/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 4/13/12
 * Time: 11:32 PM
 */

Ext.define('App.store.patient.VisitPayment', {
	extend: 'Ext.data.Store',
	requires: ['App.model.patient.VisitPayment'],
	model   : 'App.model.patient.VisitPayment'
});