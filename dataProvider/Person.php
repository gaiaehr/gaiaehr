<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Person.php
 * Date: 1/21/12
 * Time: 3:18 PM
 */
if(!isset($_SESSION)){
    session_name ('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

class Person {

    /**
     * @param $fname
     * @param $mname
     * @param $lname
     * @return string
     */
    public static function fullname($fname, $mname, $lname)
    {
        if($_SESSION['global_settings'] && $_SESSION['global_settings']['fullname']){
            switch($_SESSION['global_settings']['fullname']){
                case '0':
                    $fullname = $lname.', '.$fname.' '.$mname;
                    break;
                case '1':
                   $fullname = $fname.' '.$mname.' '.$lname;
                    break;
	            default:
		            $fullname = $fname.' '.$mname.' '.$lname;
		            break;
            }
        }else{
            $fullname =  $lname.', '.$fname.' '.$mname;
        }
        $fullname = ($fullname == ',  ') ? '' : $fullname;

        return $fullname;
    }

    public static  function fulladdress($street, $streetb, $city, $state ,$zip )
    {

        if($street != NULL || $street  != "" ) {
            $street = $street . "<br>";
        } else {
            $street = $street;
        }

        if($streetb != NULL || $streetb != "" ) {
            $streetb = $streetb . "<br>";
        } else {
            $streetb = $streetb;
        }

        if($city != NULL || $city != "" ) {
            $city = $city. ", ";
        } else {
            $city = $city ;
        }

        return $street.$streetb.$city.' '.$state.' '.$zip;

    }

    public static function ellipsis($text, $max=100, $append='&hellip;')
    {
        if (strlen($text) <= $max) return $text;
        $out = substr($text,0,$max);
        return $out.$append;
        //return preg_replace('/\w+$/','',$out).$append;
    }

}
