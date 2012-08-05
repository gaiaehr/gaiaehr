/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientImmunization', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'immunization_name', type: 'string'},
		{name: 'immunization_id', type: 'int'},
		{name: 'administered_date', type: 'date', dateFormat: 'c'},
		{name: 'manufacturer', type: 'string'},
		{name: 'lot_number', type: 'string'},
		{name: 'administered_by', type: 'string'},
		{name: 'education_date', type: 'date', dateFormat: 'c'},
		{name: 'dosis'},
		{name: 'create_date', type: 'date', dateFormat: 'c'},
		{name: 'note', type: 'string'},
        {name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientImmunizations,
			create: Medical.addPatientImmunization,
			update: Medical.updatePatientImmunization
		}
	}
});