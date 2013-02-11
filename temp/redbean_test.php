<?php

include_once ('../classes/rb.php');

R::setup('mysql:host=localhost;dbname=gaiaehr', 'hrivera', 'edpr787');

$testtable = R::load('testtable');

print_r($book->id);

if (!$testtable->id) 
{
	print_r('No encontre la puta abichuela'); 
}

echo '<pre>';
print_r(getSenchaModel('../app/model/patient/Dental'));
echo '</pre>';

function getSenchaModel($fileModel)
{
	// Getting Sencha model as a namespace
	$senchaModel = file_get_contents($fileModel . '.js');
	
	// Stracting the necesary end-points
	preg_match("/fields:(.*?)]/si", $senchaModel, $matches, PREG_OFFSET_CAPTURE, 3);
	
	// Removing all the unnecesarry characters.
	$subject = str_replace(' ', '', $matches[1][0]);
	$subject = str_replace(chr(13), '', $subject);
	$subject = str_replace(chr(10), '', $subject);
	$subject = str_replace(chr(9), '', $subject);
	$subject = str_replace('[', '', $subject);
	$subject = str_replace("'", '"', $subject);
	$subject = str_replace('name', '"name"', $subject);
	$subject = str_replace('type', '"type"', $subject);
	$subject = str_replace('dateFormat', '"dateFormat"', $subject);
	$subject = '{"items": [' . $subject . ']}';
	
	// Decoding the model
	return json_decode($subject, true);
}
