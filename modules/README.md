models directory is where all the modules will be place

directory structure:

modules/
-------/modulename/
-------/----------/dataProvider -----> PHP classes Directory
-------/----------/model        -----> ExtJS Models Directory
-------/----------/store        -----> ExtJS Stores Directory
-------/----------/view         -----> ExtJS Views Directory
-------/----------/conf.json    -----> Module Configuration File (example below)
-------/----------/Main.js      -----> Main javascript logic, this will run on app render

Every module have to have a conf.json file storing the module metadata
format example:

{
	"module":{
		"title":"Module Name",
		"name":"modulename",
		"dir":"modulename",
		"version":"1.1.2",
		"extjs_version":"4.1.1",
		"author":"Author Name",
		"description":"Module Description...",
		"files":{
			"extjs":[
				{
					"file":"ModuleModel.js",
					"path":"model/"
				},
				{
					"file":"store/ModuleStore.js",
					"path":"store/"
				},
				{
					"file":"view/ModuleView.js",
					"path":"view/"
				}
			],
			"data":[
				{
					"file":"ModuleClass.php",
					"path":"data/"
				},
				{
					"file":"ModuleClass2.php",
					"path":"data/"
				}
			]
		}
	}
}
