/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.DismissedAlerts', {
	extend: 'Ext.data.Model',
	table: {
		name:'dismissedalerts',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Dismissed Alerts'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'preventive_care_id', type: 'int'},
		{name: 'reason', type: 'string'},
		{name: 'observation', type: 'string'},
		{name: 'dismiss', type: 'bool'},
		{name: 'description', type: 'string'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : PreventiveCare.getPreventiveCareDismissedAlertsByPid,
			update: PreventiveCare.updatePreventiveCareDismissedAlertsByPid
		}
	}
});