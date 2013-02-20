/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 * @namespace Patient.getPatientsByPoolArea
 */
Ext.define('App.model.areas.PoolDropAreas', {
	extend   : 'Ext.data.Model',
	table: {
		name:'pooldropareas',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Pool Drop Areas'
	},
	fields   : [
		{name: 'name', type: 'string'},
		{name: 'pid', type: 'int'},
		{name: 'pic', type: 'string'}
	]
});