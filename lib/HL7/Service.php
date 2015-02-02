<?php

//No timeouts, Flush Content immediately
set_time_limit(0);
ob_implicit_flush();

//Service Settings 
$phpPath = "C:\\Program Files (x86)\\iis express\\PHP\v5.4";
$ServiceName = 'GaiaHERHL7Server';
$ServiceDisplay = 'GaiaEHR HL7 Server';
$ServiceDescription = 'GaiaEHR HL7 Server Windows Service';

////Windows Service Control
////$ServiceAction = "status";
//$ServiceAction = "debug";
//if ( isset($_GET['ServiceAction']) and strlen($_GET['ServiceAction']) ) {
//	$ServiceAction = addslashes($_GET['ServiceAction']);
//} else if ( isset($argv) and isset($argv[1]) and strlen($argv[1]) ) {
//	$ServiceAction = $argv[1];
//}
//if( $ServiceAction == "status" ) {
//	$ServiceStatus = win32_query_service_status($ServiceName);
//	if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_STOPPED ) {
//		echo "Service Stopped\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_START_PENDING ) {
//		echo "Service Start Pending\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_STOP_PENDING ) {
//		echo "Service Stop Pending\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_RUNNING ) {
//		echo "Service Running\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_CONTINUE_PENDING ) {
//		echo "Service Continue Pending\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_PAUSE_PENDING ) {
//		echo "Service Pause Pending\n\n";
//	} else if ( $ServiceStatus['CurrentState'] == WIN32_SERVICE_PAUSED ) {
//		echo "Service Paused\n\n";
//	} else{
//		echo "Service Unknown\n\n";
//	}
//	exit;
//} else if ( $ServiceAction == "install" ) {
	//Install Windows Service
	$error = win32_create_service( Array(
		'service' => $ServiceName,
		'display' => $ServiceDisplay,
		'description' => $ServiceDescription,
		'params' => __FILE__ . " run"
//		'path' => $phpPath."\\php.exe",
	));

	print var_dump($error);
	print "Service Installed\n\n";
	exit;
//} else if ( $ServiceAction == "uninstall" ) {
//	//Remove Windows Service
//	win32_delete_service($ServiceName);
//	echo "Service Removed\n\n";
//	exit;
//} else if( $ServiceAction == "start") {
//	//Start Windows Service
//	win32_start_service($ServiceName);
//	echo "Service Started\n\n";
//	exit;
//} else if( $ServiceAction == "stop" ) {
//	//Stop Windows Service
//	win32_stop_service($ServiceName);
//	echo "Service Stopped\n\n";
//	exit;
//} else if ( $ServiceAction == "run" ) {
//	//Run Windows Service
//	win32_start_service_ctrl_dispatcher($ServiceName);
//	win32_set_service_status(WIN32_SERVICE_RUNNING);
//} else if ( $ServiceAction == "debug" ) {
//	//Debug Windows Service
//	set_time_limit(20);
//} else {
//	exit();
//}
//
////Server Loop
//while (1) {
//	//Handle Windows Service Request
//	usleep(100*1000);
//	if ( $ServiceAction == "run" ) {
//		switch ( win32_get_last_control_message() ) {
//			case WIN32_SERVICE_CONTROL_CONTINUE:
//				break;
//			case WIN32_SERVICE_CONTROL_INTERROGATE:
//				win32_set_service_status(WIN32_NO_ERROR);
//				break;
//			case WIN32_SERVICE_CONTROL_STOP:
//				win32_set_service_status(WIN32_SERVICE_STOPPED);
//				exit;
//			default:
//				win32_set_service_status(WIN32_ERROR_CALL_NOT_IMPLEMENTED);
//		}
//	}
//	//User Loop
//	sleep(1);
//	echo "\n<BR>YOUR CODE HERE";
//}
//
////Exit
//if ( $ServiceAction == "run" ) {
//	win32_set_service_status(WIN32_SERVICE_STOPPED);
//}
//exit();