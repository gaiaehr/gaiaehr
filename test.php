<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>GaiaEHR :: (Image Base Forms Test)</title>
		<link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
		<link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
		<link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<script type="text/javascript" src="lib/extjs-4.1.1a/ext-all.js"></script>
		<script type="text/javascript">
			Ext.Loader.setConfig(
			{
				enabled       : true,
				disableCaching: false,
				paths         : 
				{
					'Ext'       : 'lib/extjs-4.1.1a/src',
					'Ext.ux'    : 'app/classes/ux',
					'App'       : 'app',
					'Modules'   : 'modules',
					'Extensible': 'lib/extensible-1.5.1/src'
				}
			});
		</script>
		<script type="text/javascript" src="app/classes/Overrides.js"></script>
		<script type="text/javascript">
			function say(a){console.log(a);}
			Ext.onReady(function() 
			{
				Ext.create('Ext.Window',
				{
					width:500,
					height:500,
					layout:'fit',
					items:[
					{
						xtype:'container',
						style:''
					}
					]
				}).show();

			});
		</script>
	</body>
</html>