<?php

include("x12_validator.inc.php");

$vx12 = new x12valid_837_4010();

$d = dir("x12files/");
while (false !== ($entry = $d->read())) {
	if(is_file("x12files/".$entry)){
		$x12_content = file_get_contents("x12files/".$entry);
		$vx12->setX12($x12_content);
		if($vx12->valid4010A()){echo $entry." : PASSED!<br>";} else { echo $entry." : " . $vx12->getReason() . "<br>"; }
		ob_flush ();
	} 
}

?>