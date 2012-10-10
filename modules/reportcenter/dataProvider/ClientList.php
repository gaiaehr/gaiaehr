<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) 
{
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once('Reports.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Fees.php');
include_once($_SESSION['site']['root'] . '/dataProvider/i18nRouter.php');


class ClientList extends Reports
{
    private $db;
    private $user;
    private $patient;
    private $fees;
	
	/*
	 * The first thing all classes do, the construct.
	 */
    function __construct()
    {
	    parent::__construct();
        $this->db       = new dbHelper();
        $this->user     = new User();
        $this->patient  = new Patient();
        $this->fees     = new Fees();
        return;
    }

    public function CreateClientList(stdClass $params)
    {
	    $html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../../resources/css/printReport.css\">
            <h3>Client List Report (Patient List)</h3>
	        <table>
	            <tr>
	                <th>".i18nRouter::t('last_visit')."</th>
	                <th>".i18nRouter::t('patient')."</th>
	                <th>".i18nRouter::t('id')."</th>
	                <th>".i18nRouter::t('street')."</th>
	                <th>".i18nRouter::t('city')."</th>
	                <th>".i18nRouter::t('state')."</th>
	                <th>".i18nRouter::t('zipcode')."</th>
	                <th>".i18nRouter::t('home_phone')."</th>
	                <th>".i18nRouter::t('work_phone')."</th>
	            </tr>";
        foreach($this->getClientList($params->from,$params->to) AS $eData) 
        {
            $html .= $this->htmlClientList($eData);
        }
	    $html .= "</table><div style=\"page-break-after:always\"></div>";
        ob_end_clean();
	    $Url = $this->ReportBuilder($html);
        return array('success' => true, 'html' => $html, 'url' => $Url);
    }

    public function getClientList($from,$to)
    {
	    $sql = "SELECT form_data_demographics.title,
		               CONCAT(form_data_demographics.fname,' ', form_data_demographics.mname,' ', form_data_demographics.lname) As PatientName,
		               form_data_demographics.pid,
		               form_data_demographics.city,
		               form_data_demographics.address,
		               form_data_demographics.state,
		               form_data_demographics.zipcode,
		               form_data_demographics.home_phone,
		               form_data_demographics.work_phone,
		               form_data_encounter.close_date
	    		  FROM form_data_demographics
             LEFT JOIN form_data_encounter ON form_data_demographics.pid = form_data_encounter.pid
	    		 WHERE form_data_encounter.close_date IS NOT NULL ";
	    if($from != null && $to != null) $sql .= "AND close_date BETWEEN $from AND $to";
        $this->db->setSQL($sql);
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }


    public function htmlClientList($eData)
    {
	    $html = '';
        foreach($eData as $row) 
        {
		    $html .= "<tr>
            <td>".date('m/d/Y', strtotime($row['close_date']))."</td>
            <td>".$row['PatientName']."</td>
            <td>".$row['pid']."</td>
            <td>".$row['address']."</td>
            <td>".$row['city']."</td>
            <td>".$row['state']."</td>
            <td>".$row['zipcode']."</td>
            <td>".$row['home_phone']."</td>
            <td>".$row['work_phone']."</td>
        	</tr>";
        }
	    return $html;
    }
}
//$e = new ClientList();
//$params = new stdClass();
//$params->pid = 1;
//$params->from = '2011-09-05';
//$params->to = '2013-09-05';
//echo '<pre>';
//print_r($e->CreateClientList($params));