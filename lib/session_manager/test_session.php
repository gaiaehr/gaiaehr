<?php

include("class_session.inc.php");
$session = new Session();

if($session->isValid()){//Test if the session has not been stolen
	print_r($session->getVar("test"));

	unset($session);
	session_destroy();
}
else 
	echo $session->getLastError();

?>