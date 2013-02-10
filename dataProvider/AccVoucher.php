<?php
if(!isset($_SESSION)){
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 2/8/13
 * Time: 10:20 PM
 * To change this template use File | Settings | File Templates.
 */
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class AccVoucher {

    /**
     * @var dbHelper
     */
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
    }

    public function createVoucher($pid, $ref, $services){

    }

    public function createVoucherLines($lines){

    }


    /**
     * GETTERS!
     */

    public function getVoucherById($id){

    }

    public function getVoucherByRef($ref){

    }

    public function getVoucherLinesByVoucherId($vid){

    }
}
