<?php
if (!defined('_GaiaEXEC'))
	die('No direct access allowed.');

/**
 * Logon Screen Window for mobiles
 * Description: Obviously the Logon Window. I think every WebApp has one.
 *
 * author: Ernesto J. Rodriguez
 * Version 0.0.1
 * Revision: N/A
 */
?>
<html>
	<head>
		<title>GaiaEHR Logon Screen</title>
		<link rel="stylesheet" href="lib/<?php echo $_SESSION['dir']['touch']; ?>/resources/css/sencha-touch.css" type="text/css">
		<script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['touch']; ?>/sencha-touch-debug.js"></script>
		<script type="text/javascript" src="login/login_mobile.js"></script>
	</head>
	<body></body>
</html>