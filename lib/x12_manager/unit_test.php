<?php

include("x12_parser.inc.php");
include("x12_validator.inc.php");
include("../XMLParser/XMLParser.inc.php");

$x12 = new x12parse_4010();
$vx12 = new x12valid_837_4010();
$data = file_get_contents('x12files/MC174.TXT');

$x12->setX12($data);
$vx12->setX12($data);

// Get the account information for this plugin
$xml = file_get_contents("ansix12_4010.dicc.xml");
$pluginXML = new XMLParser($xml);
$pluginXML->Parse();

echo "<pre>";
$x12->setDicc($pluginXML);
echo "</pre>";

echo "Document Type: " . $x12->docType() . "<br>";
echo "Document Control Number: " . $x12->docControlNumber() . "<br>";

echo "<br>";
echo "Document Interchange Information <br>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->docISA() );
echo "</pre>";

echo "<br>";
echo "<br>";
echo "Functional Group Information <br>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->docGS() );
echo "</pre>";
echo "<br>";
echo "Beginning of Hierarchical Transaction<br>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->docBHT() );
echo "</pre>";
echo "<br>";
echo "<h3>Submitter Information - Individual or Organizational Name</h3>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->getSubmitter() );
echo "</pre>";
echo "<br>";
echo "<h3>Receiver - Player</h3>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->getReceiver() );
echo "</pre>";
echo "<br>";
echo "<h3>Billing Provider</h3>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->getBillingProvider() );
echo "</pre>";
echo "<br>";
echo "<h2>Healthcare Claims: Total: ". $x12->getTotalClaims() ."</h2>";
echo "--------------------------------------------------------------------------------------------------<br>";
echo "<pre>";
print_r( $x12->getClaims() );
echo "</pre>";
if($vx12->valid4010A() == TRUE){echo "PASSED!";} else { echo "FAILED!<br>" . $vx12->getReason(); }
$vx12->tmpShow();

echo "<pre>";
print_r($x12_dicc);
echo "</pre>";

?>