/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('Modules.reportcenter.model.ClientList', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'pid', type: 'int'},
        {name: 'fullname', type: 'string'},
        {name: 'fulladdress', type: 'string'},
		{name: 'start_date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'home_phone'}
	],
	proxy : {
		type: 'direct',
		api : {
			read  : ClientList.getClientList
		}
	}
});