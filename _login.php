<?php
/* Logon Screen Window
 * Description: Obviously the Logon Window. I think every WebApp has one.
 * 
 * author: GI Technologies, 2011
 * Version 0.0.3
 * Revision: N/A
 */
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
$lang = (isset($_SESSION['site']['localization']) ? $_SESSION['site']['localization'] : 'en_US');
$site = (isset($_SESSION['site']['dir']) ? $_SESSION['site']['dir'] : false);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>GaiaEHR Logon Screen</title>
    <script type="text/javascript" src="lib/extjs-4.1.1a/ext-all.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
    <link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
    <link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">

    <link rel="shortcut icon" href="favicon.ico">
    <script src="JSrouter.php"></script>
    <script src="data/api.php"></script>
    <script type="text/javascript">
        var app, site = '<?php print $site ?>', localization = '<?php print $lang ?>';
        function i18n(key){ return lang[key] || key; }
        function say(a){ console.log(a); }
        Ext.Loader.setConfig({
            enabled: true,
            disableCaching: true,
            paths: {
                'App': 'app'
            }
        });
        for(var x = 0; x < App.data.length; x++){
            Ext.direct.Manager.addProvider(App.data[x]);
        }
        Ext.onReady(function(){
            app = Ext.create('App.view.login.Login');
        });
    </script>
</head>
<body id="login">
<div id="copyright">GaiaEHR | <a href="javascript:void(0)" onClick="Ext.getCmp('winCopyright').show();">Copyright
    Notice</a></div>
</body>
</html>