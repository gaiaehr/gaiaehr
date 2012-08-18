<?php
if(!isset($_SESSION)){
    session_name ("GaiaEHR" );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
/**
     * @brief     log class
     * @details   This class wil handle all the log logic
     *
     * @author    Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
     * @version   Vega 1.0
     * @copyright Gnu Public License (GPLv3)
     *
     * @todo      move all the log stuff from dbHelper to this class
     *
     */
class Logs extends dbHelper {

    /**
     * @brief     Get all logs
     * @details   This method will return all the logs and filter them by start and limit
     *            to be used with the Ext.ux.SlidingPager plugin.
     *
     * @author    Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
     * @version   Vega 1.0
     *
     * @warning   getLogs 'len' in /data/cinfig.php must be set to 1 in order for Ext.direct to send the Params
     *
     * @param stdClass $params Params sent from sencha
     * @return mixed array of records with totals count
     */
    public function getLogs(stdClass $params){
        $this->setSQL("SELECT * FROM log ORDER BY id DESC");
        $records    = $this->fetchRecords(PDO::FETCH_CLASS);
        $total      = count($records);
        $rows       = array_splice($records, $params->start, $params->limit);
        return array('totals'=>$total ,'rows'=>$rows);
    }
}
