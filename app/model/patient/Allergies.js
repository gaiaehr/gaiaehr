/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.Allergies', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'created_uid', type: 'int'},
		{name: 'updated_uid', type: 'int'},
		{name: 'create_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'allergy_type', type: 'string'},
		{name: 'allergy', type: 'string'},
		{name: 'allergy1', type: 'string'},
		{name: 'allergy2', type: 'string'},
		{name: 'allergy_name', type: 'int'},
		{name: 'begin_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'end_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'reaction', type: 'string'},
		{name: 'reaction1', type: 'string'},
		{name: 'reaction2', type: 'string'},
		{name: 'reaction3', type: 'string'},
		{name: 'reaction4', type: 'string'},
		{name: 'location', type: 'string'},
		{name: 'severity', type: 'string'},
        {name: 'alert', type: 'bool'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Medical.getPatientAllergies,
			create: Medical.addPatientAllergies,
			update: Medical.updatePatientAllergies
		}
	}
});