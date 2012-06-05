<?php

include("class_session.inc.php");
$session = new Session();

$session->setVar("test", array("a"=>1, "b"=>2));
$session->setVar("test.c", 3);

echo "<p><a href=\"test_session.php\">Next</a></p>";

?>