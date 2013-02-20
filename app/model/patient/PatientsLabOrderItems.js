/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsLabOrderItems', {
	extend: 'Ext.data.Model',
	table: {
		name:'patientslaborderitems',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patients Lab Order Items'
	},
	table: {
		name:'patientslaborderitems',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patients Lab Order Items'
	},
	fields: [
        { name: 'id', type: 'int' },
        { name: 'loinc', type: 'string' },
        { name: 'title', type: 'string' }
	]
});