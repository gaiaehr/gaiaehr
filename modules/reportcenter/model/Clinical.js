/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('Modules.reportcenter.model.Clinical', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'pid', type: 'int'},
        {name: 'fullname', type: 'string'},
        {name: 'age', type: 'string'},
		{name: 'sex'},
		{name: 'ethnicity'},
		{name: 'race'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : Clinical.getClinicalList
		}
	}
});