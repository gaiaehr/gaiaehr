<?php
if(!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'] . '/classes/dbHelper.php');
/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 8/19/12
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

class Immunizations
{
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
        return;
    }








}
