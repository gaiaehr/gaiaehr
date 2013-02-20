/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.patient.EventHistory', {
	extend: 'Ext.data.Model',
	table: {
		name:'eventhistory',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Event History'
	},
	fields: [
		{name: 'id', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'pid', type: 'int'},
		{name: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'user', type: 'string'},
		{name: 'event', type: 'string'}
	]
});

