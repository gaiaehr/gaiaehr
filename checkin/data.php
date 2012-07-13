<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: data.php
 * Date: 1/13/12
 * Time: 8:48 AM
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}

include_once("../classes/authProcedures.php");


if($_REQUEST['task'] == 'unAuth'){

    authProcedures::unAuth();

} elseif($_REQUEST['task'] == 'ckAuth'){

    authProcedures::ckAuth();

}

