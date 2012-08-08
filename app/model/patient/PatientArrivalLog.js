/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientArrivalLog', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'id', type: 'int'},
        {name: 'area_id', type: 'int'},
        {name: 'pid', type: 'int'},
		{name: 'time', type: 'string'},
		{name: 'name', type: 'string'},
		{name: 'status', type: 'string'},
		{name: 'area', type: 'string'},
		{name: 'warning', type: 'bool'},
		{name: 'warningMsg', type: 'string'},
		{name: 'isNew', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read: PoolArea.getPatientsArrivalLog,
			create: PoolArea.addPatientArrivalLog,
			update: PoolArea.updatePatientArrivalLog,
			destroy: PoolArea.removePatientArrivalLog
		}
	}
});