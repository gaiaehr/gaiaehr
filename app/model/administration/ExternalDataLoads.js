/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.ExternalDataLoads', {
	extend: 'Ext.data.Model',
	table: {
		name:'externaldataloads',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'External Data Loads'
	},
	fields: [
		{name: 'date' },
        {name: 'version' },
		{name: 'path' },
		{name: 'basename' },
		{name: 'codeType' }
	]
});