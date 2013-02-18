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
class AccJournal extends AccAccount {


    function __construct()
    {
        parent::__construct();
    }

    public function createJournal(){

    }

    public function createJournalEntryByJournalId(){

    }


    /**
     * GETTERS!
     */


    public function getJournal(){

    }

    public function getJournalEntriesByRef(){

    }

}
