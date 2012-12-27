models directory is where all the modules will be place

directory structure:

modules/
-------/modulename/
-------/----------/dataProvider -----> PHP classes Directory
-------/----------/model        -----> ExtJS Models Directory
-------/----------/store        -----> ExtJS Stores Directory
-------/----------/view         -----> ExtJS Views Directory
-------/----------/conf.json    -----> **REQUIRED** Module Configuration File (example below)
-------/----------/Main.js      -----> **REQUIRED** Main javascript logic, this will run on app render

Every module have to have a Main.js that will run during app render and,
conf.json file storing the module metadata, format example:
{
	"title":"Module Name",              // Module Title
	"name":"modulename",                // Module name
	"dir":"modulename",                 // directory name (should be same as module name)
	"version":"1.0.0",                  // Module version
	"extjs_version":"4.1.1a",           // ExtJS version required
	"author":"Author Full Name",
	"description":"Module Description...",
	"active":true,                      // Normally set to true. This is to disable across all sites this module
	"debug":false,                      // this will enable module verbose
    "actionsAPI":{                      // Actions / Classes to unique to this module and inside "modules/modulename/dataProvider/" directory
		"Class1":{                      // PHP class name
			"methods":{                 // Methods you want to access using Ext.Direct
				"Method1":              // Public method name **MOST be public
				{
					"len":1             // set to 1 if you will be sending parameters
				}
			}
		},
		"Class2":{                      // second class example
			"methods":{
				"Method1":
				{
					"len":1
				},
				"Method2":
				{
					"len":0
				}
			}
		}
	},
	"install":[                         // files to run during installation
		{
			"file":"sql/install_schema.sql"
		}
	]
}
