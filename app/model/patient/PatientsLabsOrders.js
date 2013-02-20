/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsLabsOrders', {
	extend: 'Ext.data.Model',
	table: {
		name:'patientslabsorders',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Patients Labs Orders'
	},
	fields: [
        { name: 'id', type: 'int' },
        { name: 'eid', type: 'int' },
        { name: 'pid', type: 'int' },
        { name: 'uid', type: 'int' },
        { name: 'description', type: 'string' },
        { name: 'date_created', type: 'date', dateFormat:'Y-m-d H:i:s' },
        { name: 'laboratory_id', type: 'int' },
        { name: 'document_id', type: 'int' },
        { name: 'order_type', type: 'string', defaultValue:'lab' },
        { name: 'order_items', type: 'auto' },
        { name: 'note', type: 'string' },
        { name: 'docUrl', type: 'string' }

	],
	proxy : {
		type: 'direct',
		api : {
			read:Orders.getPatientLabOrders,
			create:Orders.addPatientLabOrder,
			update:Orders.updatePatientLabOrder
		}
	}
});