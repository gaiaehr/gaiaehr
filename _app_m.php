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
 *
 */
// Reset session count
$_SESSION['site']['flops'] = 0;
/*
 * Include the necessary libraries, so the web application
 * can work.
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>GaiaEHR - Demo</title>
		<link rel="stylesheet" href="lib/sencha-touch-2.0.1.1/resources/css/android.css" type="text/css">
		<link rel="stylesheet" href="app_m/resources/css/sink.css?2" type="text/css">
		<script type="text/javascript"  src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript"  src="lib/sencha-touch-2.0.1.1/sencha-touch.js"></script>
		<script type="text/javascript"  src="app_m/all-classes.js"></script>
		<script type="text/javascript"  src="app_m/app.js"></script>
	</head>
	<body></body>
</html>
