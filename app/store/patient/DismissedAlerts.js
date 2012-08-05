/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('App.store.patient.DismissedAlerts', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patient.DismissedAlerts',
    remoteSort: false,
	autoLoad  : false,
    autoSync  : true
});