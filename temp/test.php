<?php
echo "<pre>";

$SenchaModel = file_get_contents('c:\xampp\htdocs\gaiaehr\app\model\administration\Address.js');

// Fist convert to CFLF to LF of the Sencha Model
// This will deal with Linux, Apple and Windows
$SenchaModel = str_replace("\r\n", "\n", $SenchaModel);
$SenchaModel = str_replace("\r", "\n", $SenchaModel);
// Removes all Sencha and Custome functions in the model
$Rows = explode("\n", $SenchaModel);
// Reset the count for the Curly Braces
// and Function Found
$CurlyBraceCount = 0;
$FunctionFound = false;
foreach($Rows as $Row => $Data) {
    $CanTerminate = false;
    // Ok, found a function
    if(stripos($Data, 'function') !== false) $FunctionFound = true;
    // If a function is found start deleting lines and count
    // curly braces.
    if($FunctionFound) {
        // if the function is in between or at bottom
        // remove the above comma
        if(isset($Rows[$Row-1])) {
            if (strpos($Rows[$Row - 1], ',') !== false)
                $Rows[$Row - 1] = substr($Rows[$Row - 1], 0, -1);
        }
        // Delete lines until we find the closing curly brace
        // this will be possible if the CurlyBraceCount is 0
        if (strpos($Data, '{') !== false) $CurlyBraceCount++;
        if ($CurlyBraceCount >= 1) {
            unset($Rows[$Row]);
            $CanTerminate = true;
        }elseif($FunctionFound){
            unset($Rows[$Row]);
        }
        if (strpos($Data, '}') !== false) $CurlyBraceCount--;
        if ($CurlyBraceCount == 0 && $CanTerminate) $FunctionFound = false;
    }
}
$Rows = array_values($Rows);
$SenchaModel = implode("\n", $Rows);
print_r($SenchaModel);
echo "</pre>";