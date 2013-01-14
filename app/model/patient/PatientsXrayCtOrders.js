/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.PatientsXrayCtOrders', {
	extend: 'Ext.data.Model',
	fields: [
        { name: 'id', type: 'int' },
        { name: 'eid', type: 'int' },
        { name: 'pid', type: 'int' },
        { name: 'uid', type: 'int' },
        { name: 'description', type: 'string' },
        { name: 'date_created', type: 'date', dateFormat:'Y-m-d H:i:s' },
        { name: 'laboratory_id', type: 'int' },
        { name: 'document_id', type: 'int' },
        { name: 'order_type', type: 'string', defaultValue:'rad' },
        { name: 'order_items', type: 'string' },
        { name: 'note', type: 'string' },
		{ name: 'docUrl', type: 'string' }
	],
	proxy : {
		type: 'direct',
		api : {
			read:Orders.getPatientXrayCtOrders,
			create:Orders.addPatientXrayCtOrder,
			update:Orders.updatePatientXrayCtOrder
		}
	}
});