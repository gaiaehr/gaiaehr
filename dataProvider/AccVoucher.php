<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once (dirname(__FILE__) . '/AccAccount.php');

class AccVoucher extends AccAccount {

    /**
     * MatchaCup
     */
	private $voucher = NULL;
	private $voucherLine = NULL;

    function __construct()
    {
	    parent::__construct();
	    if($this->voucher == NULL) $this->voucher = MatchaModel::setSenchaModel('App.model.account.Voucher');
//	    if($this->voucherLine == NULL) $this->voucherLine = MatchaModel::setSenchaModel('App.model.account.VoucherLine');
        return;
    }

	/**
	 * Voucher
	 */
	public function getVoucher($params)
    {
		return $this->voucher->load($params)->one();
    }
    public function createVoucher($params)
    {

	    $data = get_object_vars($params);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucher','I'));
	    $this->db->execLog();
	    $params->id = $this->db->lastInsertId;
	    return $params;
    }
    public function updateVoucher($params)
    {
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
    public function getVoucherLines($params)
    {
        return $this->voucherLine->load($params);
    }
    public function createVoucherLine($params)
    {
	    $data = get_object_vars($params);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucherline','I'));
	    $this->db->execLog();
	    $params->id = $this->db->lastInsertId;
	    return $params;
    }
    public function updateVoucherLine($params)
    {
	    $data = get_object_vars($params);
	    unset($data['id']);
	    $this->db->setSQL($this->db->sqlBind($data,'accvoucherline','U', array('id'=>$params->id)));
	    $this->db->execLog();
	    return $params;
    }
    public function destroyVoucherLine($params)
    {
	    return $params;
    }
}
