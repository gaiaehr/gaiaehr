{
	"classAlias": "widget.matchamodel",
	"className": "Ext.ux.MatchaModel",
	"inherits": "Ext.data.Model",
	"autoName": "MyMatchaModel",
	"toolbox": {
	"name": "Matcha Model",
		"category": "Data Models",
		"groups": ["Data"]
	},
	"configs": [{
		"name": 'table',
		"type": 'object',
		"hidden": false,
		"initialValue": {
			"name": "db_table_name",
			"comment": "This is a table comment"
		},
		"merge": false
	}]
}