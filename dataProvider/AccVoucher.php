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
include_once ($_SESSION['root'] . '/dataProvider/AccBilling.php');
class AccVoucher extends AccBilling {


    function __construct()
    {
	    parent::__construct();
    }

	/**
	 * Voucher
	 */
	public function getVoucher($params){

		if($params->type == 'visit'){

			$params->voucherlines = $this->getVisitVoucherLines($params);
		}

		return array();
    }
    public function createVoucher($params){

	    return $params;
    }
    public function updateVoucher($params){

	    return $params;
    }
    public function destroyVoucher($params){

	    return $params;
    }


	/**
	 * Voucher Lines
	 */
    public function getVoucherLines($params){

	    return $params;
    }
    public function createVoucherLine($params){

	    return $params;
    }
    public function updateVoucherLine($params){

	    return $params;
    }
    public function destroyVoucherLine($params){

	    return $params;
    }
}
