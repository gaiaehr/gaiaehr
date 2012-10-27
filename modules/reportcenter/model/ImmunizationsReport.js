/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('Modules.reportcenter.model.ImmunizationsReport', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'pid', type: 'int'},
        {name: 'fullname', type: 'string'},
        {name: 'immunization_id'},
        {name: 'immunization_name'},
		{name: 'administered_date', type: 'date', dateFormat: 'Y-m-d H:i:s'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : ImmunizationsReport.getImmunizationsReport
		}
	}
});