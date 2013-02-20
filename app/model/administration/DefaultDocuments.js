/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.DefaultDocuments', {
	extend: 'Ext.data.Model',
	table: {
		name:'defaultdocuments',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Default Documents'
	},
	fields: [
		{name: 'id', type:'int' },
        {name: 'title', type:'string' },
		{name: 'body', type:'string' },
		{name: 'template_type', type:'string' },
		{name: 'date', type:'date', dateFormat:'Y-m-d H:i:s' }

	]
});