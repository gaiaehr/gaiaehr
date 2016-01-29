<?php

require ('sphinxapi.php');
$cl = new SphinxClient ();
$cl->SetServer('localhost');
$cl->SetConnectTimeout(5);
$cl->SetMatchMode(SPH_MATCH_ANY);
$cl->SetLimits(0, 25, 1000);
$cl->SetArrayResult(true);
$result = $cl->Query('Codeine');

if ($result === false ){
	echo "Query failed: " . $cl->GetLastError() . ".\n";
}else{
	if($cl->GetLastWarning()){
		echo "WARNING: ".$cl->GetLastWarning();
	}
	print '<pre>';
//	if(!empty($result["matches"])){
//		print '<pre>';
//		foreach ( $result["matches"] as $doc => $docinfo ) {
			print_r($result["matches"]);
//		}
//	}
}

