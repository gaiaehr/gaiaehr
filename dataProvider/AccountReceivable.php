<?php
/*
 GaiaEHR (Electronic Health Records)
 Services.php
 Services dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if (!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/Immunizations.php');

/**
 * @brief       Billing Class.
 * @details     This class will handle all Billing
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
class AccountReceivable
{
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * @var Services
     */
    private $services;


    function __construct()
    {
        $this->db = new dbHelper();
        $this->services = new Services();
    }

    public function getArSessionBalanceByEid($eid)
    {
        $sid = $this->getArSidByEid($eid);
        $services = $this->services->getCptByEid($eid);
        $activities = $this->getArActivitiesBySid($sid);


        print_r($services);
        print_r($activities);


    }

    /**
     * @param $sid
     * @return array
     */
    public function getArActivitiesBySid($sid)
    {
        $this->db->setSQL("SELECT * FROM ar_session_activity WHERE id = '$sid'");
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }

    /**
     * @param $eid
     * @return mixed
     */
    public function getArSidByEid($eid){
        $this->db->setSQL("SELECT id FROM ar_session WHERE eid = '$eid'");
        $rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        return $rec['id'];
    }

    /**
     * @param $pid
     * @param $eid
     * @param $uid
     * @return array
     */
    public function openArSession($pid, $eid, $uid)
    {
        $data = array();
        $data['pid'] = $pid;
        $data['eid'] = $eid;
        $data['uid'] = $uid;
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'I'));
        return $this->db->execOnly();
    }

    /**
     * @param $eid
     * @return array
     */
    public function closeArSessionByEid($eid)
    {
        $data = array();
        $data['close_time'] = Time::getLocalTime();
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'U', array('eid' => $eid)));
        return $this->db->execOnly();
    }

    /**
     * @param $sid
     * @return array
     */
    public function closeArSessionBySid($sid)
    {
        $data = array();
        $data['close_time'] = Time::getLocalTime();
        $this->db->setSQL($this->db->sqlBind($data, 'ar_session', 'U', array('id' => $sid)));
        return $this->db->execOnly();
    }
}

//
//$params = new stdClass();
//$params->filter = 2;
//$params->pid = '7';
//$params->eid = '1';
//$params->start = 0;
//$params->limit = 25;
//
$t = new AccountReceivable();
print '<pre>';
$t->getArSessionBalanceByEid(3);
