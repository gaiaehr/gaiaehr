/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('Modules.reportcenter.model.MedicationReport', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'pid', type: 'int'},
        {name: 'fullname', type: 'string'},
        {name: 'medication', type: 'string'},
        {name: 'take_pills', type: 'string'},
        {name: 'type', type: 'string'},
        {name: 'instructions', type: 'string'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Rx.getPrescriptionsFromAndToAndPid
		}
	}
});