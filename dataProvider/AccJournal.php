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
class AccJournal {

    /**
     * @var dbHelper
     */
    private $db;


    function __construct()
    {
        $this->db = new dbHelper();
    }



    public function createJournal(){

    }

    public function createJournalEntryByJournalId(){

    }



    public function getJournal(){

    }

    public function getJournalEntriesByRef(){

    }

}
