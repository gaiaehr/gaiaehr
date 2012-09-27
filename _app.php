<?php
/* Main Screen Application
 *
 * Description: This is the main application file, all the global
 * vars are defined here inluding "var app" witch refers to
 * the applciation Viewport.
 *
 *
 * version 1.0.0
 * revision: N/A
 * author: GI Technologies, 2011
 * modified: Ernesto J Rodriguez (Certun)
 *
 * @namespace App.data.REMOTING_API
 */
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
$_SESSION['site']['flops'] = 0;

/*
 * This is used for the correct loading of the language file 
 * of Sencha ExtJS.
 */
include_once('dataProvider/i18nRouter.php');
$lang = i18nRouter::getTranslation();

?>
<html>
	<head>
		<script type="text/javascript">
			var app,
				perm = {},
				user = {},
				settings = {},
				i18n = {},
				ext = 'extjs-4.1.1a';
		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>GaiaEHR :: (Electronic Health Records)</title>
		<link rel="stylesheet" type="text/css" href="resources/css/dashboard.css">
		<link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar-colors.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/recurrence.css"/>
		<link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
		<link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<!-- Loading Mask -->
		<div id="mainapp-loading-mask" class="x-mask mitos-mask"></div>
		<div id="mainapp-x-mask-msg">
			<div id="mainapp-loading" class="x-mask-msg mitos-mask-msg">
				<div>Loading GaiaEHR...</div>
			</div>
		</div>
		<!-- slide down message div -->
		<span id="app-msg" style="display:none;"></span>
		<!-- Ext library -->
		<script type="text/javascript" src="lib/extjs-4.1.1a/ext-all-debug.js"></script>
		<!-- Ext Localization file -->
		<script type="text/javascript" src="lib/extjs-4.1.1a/locale/<?php print $lang['i18nExtFile'] ?>"></script>

		<script src="data/api.php"></script>
		<script type="text/javascript">
			Ext.Loader.setConfig({
				enabled       : true,
				disableCaching: false,
				paths         : {
					'Ext'       : 'lib/extjs-4.1.1a/src',
					'Ext.ux'    : 'app/classes/ux',
					'App'       : 'app',
					'Modules'   : 'modules',
					'Extensible': 'lib/extensible-1.5.1/src'
				}
			});
		</script>
		<script type="text/javascript" src="lib/webcam_control/swfobject.js"></script>
		<script type="text/javascript" src="lib/extensible-1.5.1/src/Extensible.js"></script>
		<script type="text/javascript" src="lib/jpegcam/htdocs/webcam.js"></script>
		<script type="text/javascript" src="app/classes/Overrides.js"></script>
		<script type="text/javascript" src="app/classes/VTypes.js"></script>
		<script type="text/javascript">
			function say(a) {
				console.log(a);
			}
			function copyToClipBoard(token) {
				app.msg('Sweet!', token + ' copied to clipboard, Ctrl-V or Paste where need it.');
				if(window.clipboardData) {
					window.clipboardData.setData('text', token);
					return null;
				} else {
					return (token);
				}
			}
			function onWebCamComplete(msg) {
				app.onWebCamComplete(msg);
			}
			function printQRCode(pid) {
				var src = settings.site_url + '/patients/' + app.currPatient.pid + '/patientDataQrCode.png?';
				app.QRCodePrintWin = window.open(src, 'QRCodePrintWin', 'left=20,top=20,width=800,height=600,toolbar=0,resizable=0,location=1,scrollbars=0,menubar=0,directories=0');
				Ext.defer(function() {
					app.QRCodePrintWin.print();
				}, 1000);
			}
			Ext.onReady(function() {
				Ext.direct.Manager.addProvider(App.data.REMOTING_API);
				CronJob.run();
				Globals.setGlobals(function(provider, response) {
					settings.site_url = response.result.site.url + '/sites/' + response.result.site.site;
				});
				ACL.getAllUserPermsAccess(function(provider, response) {
					var permissions = response.result;
					for(var i = 0; i < permissions.length; i++) {
						perm[permissions[i].perm] = permissions[i].value;
					}
				});
				// localization remoting procedures.
				// ie: i18n['dashboard'] = Dashboard (en_US)
				// ie: i18n['dashboard'] = Tablero (es_PR)
				i18nRouter.getTranslation(function(provider, response)
				{
					i18n = response.result;
				});
				User.getCurrentUserBasicData(function(provider, response) {
					var userData = response.result;
					user.id = userData.id;
					user.name = userData.title + ' ' + userData.lname;
					/**
					 * lets create the Application Viewport (render the application),
					 * and store the application viewport instance in "app".
					 * @type {*}
					 */
					app = Ext.create('App.view.Viewport');
				});
			});
		</script>
	</body>
</html>