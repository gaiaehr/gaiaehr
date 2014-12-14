<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once(ROOT . '/classes/XMLParser.class.php');
include_once(ROOT . '/dataProvider/DiagnosisCodes.php');

class Immunizations {
	/**
	 * @var PDO
	 */
	private $conn;

	/**
	 * @var MatchaCUP
	 */
	private $i;

	function __construct() {
		$this->conn = Matcha::getConn();
		$this->i = MatchaModel::setSenchaModel('App.model.patient.PatientImmunization');
		return;
	}

	/**
	 * @return array
	 */
	public function getImmunizationsList(){
		$sth = $this->conn->prepare('SELECT * FROM cvx_codes');
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getPatientImmunizations($params){
		return $this->i->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addPatientImmunization($params){
		return  $this->i->save($params);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updatePatientImmunization($params){
		return $this->i->save($params);

	}

	public function getPatientImmunizationsByPid($pid){
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		return $this->i->load($params)->all();
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getImmunizationsByEncounterID($eid){
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value = $eid;
		return $this->i->load($params)->all();
	}

	public function sendVXU($params){
		$model = MatchaModel::setSenchaModel('App.model.patient.Patient');
		$p = new stdClass();
		$p->filter[0] = new stdClass();
		$p->filter[0]->property = 'pid';
		$p->filter[0]->value = $params->pid;
		$data = array();
		$data['to'] = $params->to;
		$data['patient'] = $model->load($p)->one();
		$data['immunizations'] = array();
		foreach($params->immunizations As $i){
			$data['immunizations'][] = $this->i->load($i)->one();
		}
		return $data;
	}

	public function getCVXCodesByStatus($status = 'Active') {
		$sth = $this->conn->prepare("SELECT * FROM cvx_codes WHERE `status` = ?");
		$sth->execute(array($status));
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	// TODO check to remove this
	public function updateCVXCode(stdClass $params) {


	}

	public function updateCVXCodes() {
		$xml = simplexml_load_file('http://www2a.cdc.gov/vaccines/iis/iisstandards/XML2.asp?rpt=cvx');
		$sth = $this->conn->prepare('TRUNCATE `cvx_codes`');
		$sth->execute();
		unset($sth);
		$sth = $this->conn->prepare('INSERT INTO `cvx_codes` (`cvx_code`, `name`, `description`, `note`, `status`, `update_date`)
										  VALUES (?,?,?,?,?,?)');
		foreach($xml as $cvx){
			$sth->execute(array(
				trim($cvx->CVXCode),
				trim($cvx->ShortDescription),
				trim($cvx->FullVaccinename),
				trim($cvx->Notes),
				trim($cvx->Status),
				(date('Y-m-d H:i:s', strtotime($cvx->LastUpdated)))
			));
		}

		return array('success' => true);
	}

	public function updateMVXCodes() {

		$xml = simplexml_load_file('http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=tradename');
		$sth = $this->conn->prepare('TRUNCATE `cvx_mvx`');
		$sth->execute();
		unset($sth);
		$sth = $this->conn->prepare('INSERT INTO `cvx_mvx` (`cdc_product_name`,`description`,`cvx_code`,`manufacturer`,`mvx_code`,`mvx_status`,`product_name_status`,`update_date`)
										  VALUES (?,?,?,?,?,?,?,?)');
		foreach($xml AS $vac){
			$vac = get_object_vars($vac);
			$sth->execute(array(
				trim($vac['Value'][0]),
				trim($vac['Value'][1]),
				trim($vac['Value'][2]),
				trim($vac['Value'][3]),
				trim($vac['Value'][4]),
				trim($vac['Value'][5]),
				trim($vac['Value'][6]),
				(date('Y-m-d H:i:s', strtotime($vac['Value'][7])))
			));
		}
		return array('success' => true);
	}

	public function getImmunizationLiveSearch(stdClass $params) {
		$sth = $this->conn->prepare("SELECT * FROM cvx_codes
							WHERE (cvx_code   LIKE ?
							   OR `name` 	  LIKE ?
							   OR description LIKE ?)
							  AND (`status` = '1' OR `status` = 'Active')");
		$q = $params->query.'%';
		$qq = '%'.$params->query.'%';
		$sth->execute(array($q , $q, $qq));
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
	}

	public function getMvxByCode($code) {
		$sth = $this->conn->prepare('SELECT * FROM cvx_mvx WHERE mvx_code = ?');
		$sth->execute(array($code));
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public function getMvxForCvx(stdClass $params) {
		$where = (isset($params->cvx_code) ? " WHERE cvx_code = '$params->cvx_code'" : '');
		$sth = $this->conn->prepare("SELECT * FROM cvx_mvx $where");
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getImmunizationsByEid($eid) {
		$sth = $this->conn->prepare('SELECT * FROM patient_immunizations WHERE eid = ?');
		$sth->execute(array($eid));
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCptByCvx($cvx) {
		$sth = $this->conn->prepare('SELECT cvx_cpt.cpt AS `code`,
 										    cpt_codes.code_text_medium AS code_text,
 										    \'CPT4\' AS code_type
									   FROM cvx_cpt
									   JOIN cpt_codes ON cvx_cpt.cpt = cpt_codes.code
									  WHERE cvx_cpt.cvx = ? AND (cvx_cpt.active = \'1\' OR cvx_cpt.active = \'Active\')');
		$sth->execute(array($cvx));
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateCvxCptTable(){
		$foo = file_get_contents('http://www2a.cdc.gov/vaccines/iis/iisstandards/downloads/cpt.txt');
		$lines = explode(PHP_EOL, $foo);
		if(count($lines) == 0){
			throw new \Exception('Empty mapping data from cdc.gov');
		}
		$sth = $this->conn->prepare('TRUNCATE `cvx_cpt`');
		$sth->execute();
		unset($sth);
		$sth = $this->conn->prepare('INSERT INTO `cvx_cpt` (cvx, cpt, active) values (?, ?, ?)');
		foreach($lines as $line){
			$data = explode('|', $line);
			if(count($data) < 4) continue;
			$sth->execute(array(trim($data[4]), trim($data[0]), '1'));
		}

		return array('success' => true);
	}

}

