<?php
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
/* Logon Screen Window
 * Description: Obviously the Logon Window. I think every WebApp has one.
 * 
 * author: GI Technologies, 2011
 * Version 0.0.3
 * Revision: N/A
 */
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>GaiaEHR Logon Screen</title>
        <script type="text/javascript" src="lib/extjs-4.1.1/ext-all.js"></script>
            <link rel="stylesheet" type="text/css" href="lib/extjs-4.1.1/resources/css/ext-all-gray.css">
        <link rel="stylesheet" type="text/css" href="ui_app/style_newui.css" >
        <link rel="stylesheet" type="text/css" href="ui_app/custom_app.css" >

        <link rel="shortcut icon" href="favicon.ico" >

        <script src="data/logon_api.php"></script>
        <script type="text/javascript" src="login/login.js"></script>
        <script type="text/javascript">
        Ext.onReady(function(){
            Ext.direct.Manager.addProvider(App.data.REMOTING_API);
            Ext.create('App.panel.login.Login');
        }); // End App
        </script>
    </head>
    <body id="login">
        <div id="copyright">GaiaEHR | <a href="javascript:void(0)" onClick="Ext.getCmp('winCopyright').show();" >Copyright Notice</a></div>
    </body>
</html>