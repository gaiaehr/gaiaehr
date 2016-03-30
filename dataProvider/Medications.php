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

class Medications {


	/**
	 * @var MatchaCUP
	 */
	private $m;

    /**
     * @var PDO
     */
    private $db;

	function __construct()
    {
        if(!isset($this->m))
            $this->m = MatchaModel::setSenchaModel('App.model.patient.Medications');
		$this->m->setOrFilterProperties(['id']);
        $this->db = Matcha::getConn();
	}

	public function getPatientMedications($params) {
		if(isset($params->reconciled) && $params->reconciled == true)
        {
			$groups = new stdClass();
			$groups->group[0] = new stdClass();
			$groups->group[0]->property = 'RXCUI';
			return $this->m->load($params)
				->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
				->group($groups)->all();
		}

		return $this->m->load($params)
			->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
			->all();
	}

    public function getPatientMedicationsOrders($params){

        $params->filter[1] = new stdClass();
        $params->filter[1]->property = 'date_ordered';
        $params->filter[1]->operator = 'IS NOT NULL';

        if(isset($params->reconciled) && $params->reconciled == true){
            $groups = new stdClass();
            $groups->group[0] = new stdClass();
            $groups->group[0]->property = 'RXCUI';

            return $this->m->load($params)
                ->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
                ->group($groups)->all();
        }

        return $this->m->load($params)
            ->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
            ->all();
    }

	public function getPatientMedication($params)
    {
		return $this->m->load($params)
			->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
			->one();
	}

	public function addPatientMedication($params)
    {
		return $this->m->save($params);
	}

	public function updatePatientMedication($params)
    {
		return $this->m->save($params);
	}

	public function destroyPatientMedication($params)
    {
		return $this->m->destroy($params);
	}

	public function getPatientMedicationsByPid($pid, $reconciled = false)
    {
		$this->m->addFilter('pid', $pid);
		if($reconciled)
        {
			$groups = new stdClass();
			$groups->group[0] = new stdClass();
			$groups->group[0]->property = 'RXCUI';
			return $this->m->load()
				->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
				->group($groups)
				->all();
		}

		return $this->m->load()
			->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')
			->all();
	}

	public function getPatientMedicationsByEid($eid)
    {
		$this->m->addFilter('eid', $eid);
		return $this->m->load()->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')->all();
	}

	public function getPatientActiveMedicationsByPid($pid, $reconciled = false)
    {
		$records = $this->getPatientMedicationsByPid($pid, $reconciled);
		foreach($records as $i => $record)
        {
			if(
				$record['end_date'] == null ||
				$record['end_date'] == '0000-00-00' ||
				strtotime($record['end_date']) <= strtotime(date('Y-m-d'))
			) continue;

			unset($records[$i]);
		}
		return $records;
	}

	public function getPatientAdministeredMedicationsByPid($pid, $eid)
    {
		$this->m->addFilter('pid', $pid);
		$this->m->addFilter('administered_uid', null, '!=');
		$this->m->addFilter('administered_uid', 0, '!=');
		return $this->m->load()->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')->all();
	}

	public function getPatientAdministeredMedicationsByPidAndEid($pid, $eid)
    {
		$this->m->addFilter('pid', $pid);
		$this->m->addFilter('eid', $eid);
		$this->m->addFilter('administered_uid', null, '!=');
		$this->m->addFilter('administered_uid', 0, '!=');
		return $this->m->load()->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')->all();
	}

	public function getPatientActiveMedicationsByPidAndCode($pid, $code)
    {
		$this->m->addFilter('pid', $pid);
		$this->m->addFilter('RXCUI', $code);
		$records = $this->m->load()->leftJoin(['title', 'fname', 'mname', 'lname'], 'users', 'administered_uid', 'id')->all();
		foreach($records as $i => $record)
        {
			if($record['end_date'] != '0000-00-00' && strtotime($record['end_date']) < strtotime(date('Y-m-d')))
            {
				unset($records[$i]);
			}
		}

		return $records;
	}

}

