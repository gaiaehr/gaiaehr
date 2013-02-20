/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.ActiveProblems', {
	extend: 'Ext.data.Model',
	table: {
		name:'activeproblems',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Active Problems'
	},
	fields: [
		{name: 'code_text' },
		{name: 'code' }
	]

});