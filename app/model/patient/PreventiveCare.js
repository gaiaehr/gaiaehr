/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PreventiveCare', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'uid', type: 'int'},
		{name: 'description'},
		{name: 'date', dateFormat: 'Y-m-d'},
		{name: 'observation'},
		{name: 'type'},
		{name: 'dismiss'},
		{name: 'reason'},
		{name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			update: PreventiveCare.addPreventivePatientDismiss,
			read  : PreventiveCare.getPreventiveCareCheck
		}
	}
});