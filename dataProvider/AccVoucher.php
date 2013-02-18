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
include_once ($_SESSION['root'] . '/dataProvider/AccAccount.php');
class AccVoucher extends AccAccount {


    function __construct()
    {
	    parent::__construct();
	    $this->db->SenchaModel('App.model.account.Voucher');
	    $this->db->SenchaModel('App.model.account.VoucherLine');
    }

	/**
	 * Voucher
	 */
	public function getVoucher($params){
		$this->db->setSQL("SELECT * FROM `accvoucher` WHERE eid = '$params->eid'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }
    public function createVoucher($params){
	    $data = get_object_vars($params);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucher','I'));
	    $this->db->execLog();
	    $params->id = $this->db->lastInsertId;
	    return $params;
    }
    public function updateVoucher($params){
	    $data = get_object_vars($params);
	    unset($data['id']);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucher','U', array('id'=>$params->id)));
	    $this->db->execLog();
	    return $params;
    }
    public function destroyVoucher($params){

	    return $params;
    }


	/**
	 * Voucher Lines
	 */
    public function getVoucherLines($params){
	    $this->db->setSQL("SELECT * FROM `accvoucherline` WHERE voucherId = '$params->voucherId'");
	    return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }
    public function createVoucherLine($params){
	    $data = get_object_vars($params);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucherline','I'));
	    $this->db->execLog();
	    $params->id = $this->db->lastInsertId;
	    return $params;
    }
    public function updateVoucherLine($params){
	    $data = get_object_vars($params);
	    unset($data['id']);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucherline','U', array('id'=>$params->id)));
	    $this->db->execLog();
	    return $params;
    }
    public function destroyVoucherLine($params){

	    return $params;
    }
}
