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
class Merge {

	/**
	 * @var PDO
	 */
	private $conn;

	private $tables;

	function __construct(){
		$this->conn = Matcha::getConn();
		$this->setTables();

	}

	/**
	 * @param $primaryPid
	 * @param $transferPid
	 *
	 * @return bool
	 */
	public function merge($primaryPid, $transferPid){
		try{
			$this->conn->beginTransaction();
			foreach($this->tables as $t){
				$this->conn->exec("UPDATE `$t` SET `pid` = '$primaryPid' WHERE `pid` = '$transferPid'");
			}
			$this->conn->exec("DELETE FROM `patient` WHERE `pid` = '$transferPid'");
			$this->conn->commit();
			return true;
		}catch (Exception $e){
			//error_log($e->getMessage());
			$this->conn->rollBack();
			return false;
		}
	}

	/**
	 * @param $primaryPubpid
	 * @param $transferPubpid
	 * @param bool $transferRecordIfPrimaryNotFound
	 * @return bool
	 */
	public function mergeByPubpid($primaryPubpid, $transferPubpid, $transferRecordIfPrimaryNotFound = false){
		$sth = $this->conn->prepare('SELECT pid FROM patient WHERE pubpid = ?');
		$sth->execute(array($primaryPubpid));
		$primary = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->execute(array($transferPubpid));
		$transfer = $sth->fetch(PDO::FETCH_ASSOC);
		unset($sth);
		if($primary !== false && $transfer !== false){
			return $this->merge($primary['pid'], $transfer['pid']);
		}elseif($primary === false && $transfer !== false && $transferRecordIfPrimaryNotFound){
			$sth = $this->conn->prepare('UPDATE `patient` SET `pubpid` = ? WHERE `pid` = ?');
			$sth->execute(array($primaryPubpid, $transfer['pid']));
			return true;
		}else{
			return false;
		}
	}


	private function  setTables(){
		$this->tables = array(
			'encounters',
			'encounter_1500_options',
			'encounter_dictation',
			'encounter_dx',
//			'encounter_history',
			'encounter_procedures',
			'encounter_review_of_systems',
			'encounter_review_of_systems_check',
			'encounter_services',
			'encounter_soap',
			'encounter_vitals',

			'patient_account',
			'patient_active_problems',
			'patient_allergies',
			'patient_chart_checkout',
			'patient_dental',
			'patient_dental_plans',
//			'patient_dental_plan_items',
			'patient_dental_prob_charts',
//			'patient_dental_prob_chart_items',
			'patient_disclosures',
			'patient_doctors_notes',
			'patient_documents',
//			'patient_documents_temp',
			'patient_images',
			'patient_immunizations',
			'patient_insurances',
			'patient_labs',
//			'patient_labs_results',
			'patient_medications',
			'patient_notes',
			'patient_orders',
//			'patient_order_results',
//			'patient_order_results_observations',
			'patient_pools',
			'patient_prescriptions',
			'patient_referrals',
			'patient_reminders',
			'patient_social_history',
			'patient_surgery',
			'patient_zone',
			
			'payments',
			'emergencies',
			'audit_log',
			'audit_transaction_log',
		);
	}

} 