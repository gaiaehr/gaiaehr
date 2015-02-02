models directory is where all the modules will be place

directory structure:

modules/
-------/modulename/
-------/----------/conf.json    -----> Module Configuration File (example below)
-------/----------/data         -----> PHP classes Directory
-------/----------/model        -----> ExtJs Models Directory
-------/----------/store        -----> ExtJs Stores Directory
-------/----------/view         -----> ExtJs Views / Panels Directory


conf.json file,

Every module have to have a conf.json file storing the module metadata
format example:

{
	"module":{
		"name":"Module Name",
		"version":"1.1.2",
		"extjs_version":"4.1.1",
		"author":"Author Name",
		"description":"Module Description...",
		"dir":"modulename",
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
