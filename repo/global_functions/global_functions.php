<?php
// *****************************************************************
// Function to parse the fullname order
// *****************************************************************
function fullname($fname, $mname, $lname){
    if($_SESSION['global_settings'] && $_SESSION['global_settings']['fullname']){
        switch($_SESSION['global_settings']['fullname']){
            case '0':
                $fullname = $lname.', '.$fname.' '.$mname;
            break;
            case '1':
               $fullname = $fname.' '.$mname.' '.$lname;
            break;
        }
    }else{
        $fullname =  $lname.', '.$fname.' '.$mname;
    }
    $fullname = ($fullname == ',  ') ? '' : $fullname;
return $fullname;
}
?>