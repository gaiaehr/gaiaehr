{
	"classAlias": "widget.matchafield",
	"className": "Ext.ux.MatchaField",
	"inherits": "Ext.data.Field",
	"autoName": "MyMatchaField",
	"toolbox": {
	"name": "Matcha Field",
		"category": "Data Models",
		"groups": ["Data"]
	},
	"configs": [{
		"name": 'type',
		"type": 'string',
		"hidden": false,
		"initialValue": 'string',
		"editor": "options",
		"required": true,
		"options": [
			"auto",
			"bool",
			"date",
			"float",
			"int",
			"string"
		],
		"merge": false
	},{
		"name": 'store',
		"type": 'boolean',
		"hidden": false,
		"initialValue": null,
		"merge": false
	},{
		"name": 'dataType',
		"type": 'string',
		"hidden": false,
		"editor": "options",
		"options": [
			"ARRAY",
			"CHAR",
			"VARCHAR",
			"BINARY",
			"VARBINARY",
			"TINYBLOB",
			"BLOB",
			"MEDIUMBLOB",
			"TINYTEXT",
			"TEXT",
			"MEDIUMTEXT",
			"LONGTEXT",
			"BIT",
			"TINYINT",
			"SMALLINT",
			"MEDIUMINT",
			"INT",
			"INTEGER",
			"BIGINT",
			"REAL",
			"DOUBLE",
			"FLOAT",
			"DECIMAL",
			"NUMERIC",
			"DATE",
			"TIME",
			"TIMESTAMP",
			"DATETIME",
			"YEAR"
		],
		"initialValue": null,
		"merge": false
	},{
		"name": 'len',
		"type": 'number',
		"hidden": false,
		"initialValue": null,
		"merge": false
	},{
		"name": 'encrypt',
		"type": 'boolean',
		"hidden": false,
		"initialValue": null,
		"merge": false
	}]
}