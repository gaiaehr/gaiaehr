<?php
/* Main Screen Application
 *
 * Description: This is the main application, with all the panels
 * also this is the viewport of the application, this will call
 * all the app->screen panels
 *
 * version 0.0.3
 * revision: N/A
 * author: GI Technologies, 2011
 * modified: Ernesto J Rodriguez (Certun)
 *
 * @namespace App.data.REMOTING_API
 */
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
/**
 * Reset session flop count
 */
$_SESSION['site']['flops'] = 0;
?>
<html>
    <head>

	    <script type="text/javascript">
		    // Javascript global vars
		    var app,
			    perm = [],
			    user = {},
			    settings = {};

	    </script>


        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>GaiaEHR :: (Electronic Health Records)</title>
        <!--test stuff-->
        <link rel="stylesheet" type="text/css" href="ui_app/dashboard.css" >
        <!--end test stuff-->
        <link rel="stylesheet" type="text/css" href="lib/extjs-4.1.0/resources/css/ext-all-gray.css">
        <!--calendar css-->
        <link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar.css" />
        <link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar-colors.css" />
        <link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/recurrence.css" />
        <!--ens calendar css-->
        <link rel="stylesheet" type="text/css" href="ui_app/style_newui.css" >
        <link rel="stylesheet" type="text/css" href="ui_app/custom_app.css" >
        <link rel="shortcut icon" href="favicon.ico" >
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
        <script type="text/javascript" src="lib/extjs-4.1.0/ext-all.js"></script>
        <script type="text/javascript">
	        Ext.Loader.setConfig({
	             enabled			: true,
	             disableCaching	: false,
	             paths			: {
	                 'Ext'         : 'lib/extjs-4.1.0/src',
	                 'Ext.ux'      : 'app/classes/ux',
	                 'App'         : 'app',
	                 'Extensible'  : 'lib/extensible-1.5.1/src'
	             }
	         });


        </script>
        <script src="data/api.php"></script>
        <script type="text/javascript" src="lib/webcam_control/swfobject.js"></script>
        <script type="text/javascript" src="lib/extensible-1.5.1/src/Extensible.js"></script>
        <script type="text/javascript" src="langs/en_US.js"></script>
        <script type="text/javascript" src="lib/jpegcam/htdocs/webcam.js"></script>


        <script type="text/javascript" src="lib/extjs-4.1.0/examples/ux/LiveSearchGridPanel.js"></script>
        <script type="text/javascript" src="lib/extjs-4.1.0/src/grid/plugin/RowEditing.js"></script>

        <script type="text/javascript" src="app/classes/Overrides.js"></script>
        <script type="text/javascript" src="app/classes/VTypes.js"></script>

        <script type="text/javascript">
            function say(a){
	            console.log(a);
            }


            Ext.onReady(function(){
	            Ext.direct.Manager.addProvider(App.data.REMOTING_API);

	            Globals.setGlobals(function(provider, response){
		            settings.site_url = response.result.site.url + '/sites/' + response.result.site.site;
	            });

	            ACL.getAllUserPermsAccess(function(provider, response){
		            Ext.each(response.result, function(permission){
			            perm[permission.perm] = permission.value;
		            });
		        });


		        User.getCurrentUserBasicData(function(provider, response){
			        Ext.each(response.result, function(userData){
				        user.id = userData.id;
				        user.name = userData.title + ' ' + userData.lname;

			        });

			        app = Ext.create('App.view.Viewport');
		        });

	            //app = Ext.create('App.view.Viewport');

            });


            function copyToClipBoard(token) {
	            app.msg('Sweet!', token + ' copied to clipboard, Ctrl-V or Paste where need it.');
                if(window.clipboardData){
                    window.clipboardData.setData('text', token);
	                return null;
                }else{
	                return (token);
                }
            }
	        function onWebCamComplete(msg){
		        app.onWebCamComplete(msg);
	        }

            function printQRCode(pid){
	            var src = settings.site_url + '/patients/' + app.currPatient.pid + '/patientDataQrCode.png?';
	            app.QRCodePrintWin = window.open(src,'QRCodePrintWin','left=20,top=20,width=150,height=150,toolbar=0,resizable=0,location=1,scrollbars=0,menubar=0,directories=0');
				Ext.defer(function(){
					app.QRCodePrintWin.print();
	            }, 1000);
  	        }
        </script>

        <!-- Override classes -->


    </body>
</html>