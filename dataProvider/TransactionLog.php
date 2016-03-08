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
class TransactionLog {

	/**
	 * @var MatchaCUP
	 */
	private $t;

	function __construct() {
        if ($this->t == NULL)
            $this->t = MatchaModel::setSenchaModel('App.model.administration.TransactionLog');
	}

	public function getLogs($params)
    {
		$records = $this->t->load($params)->leftJoin([
			'title' => 'user_title',
			'fname' => 'user_fname',
			'mname' => 'user_mname',
			'lname' => 'user_lname'
		], 'users', 'uid', 'id')->leftJoin([
			'title' => 'patient_title',
			'fname' => 'patient_fname',
			'mname' => 'patient_mname',
			'lname' => 'patient_lname'
		], 'patient', 'pid', 'pid')->all();

		foreach($records['data'] as &$record){
			$checksum = crc32(
                $record['uid'] .
                $record['fid'] .
                $record['date'] .
                $record['table_name'] .
                $record['sql_string'] .
                serialize($record['data'])
            );

			$record['is_valid'] = $record['checksum'] == $checksum;
		}

		unset($record);

		return $records;
	}

	public function getLog($params) {
		$record = $this->t->load($params)->leftJoin([
			'title' => 'user_title',
			'fname' => 'user_fname',
			'mname' => 'user_mname',
			'lname' => 'user_lname'
		], 'users', 'uid', 'id')->leftJoin([
			'title' => 'patient_title',
			'fname' => 'patient_fname',
			'mname' => 'patient_mname',
			'lname' => 'patient_lname'
		], 'patient', 'pid', 'pid')->one();

		if($record !== false){
			$checksum = crc32(
                $record['uid'] .
                $record['fid'] .
                $record['data']['date'] .
                $record['data']['table_name'] .
                $record['data']['sql_string'] .
                serialize($record['data']['data'])
            );
			$record['data']['is_valid'] = $record['data']['checksum'] == $checksum;
		}

		return $record;
	}

	public function setLog(stdClass $params) {
		//		$params->date = date('Y-m-d H:i:s');
		//		$params->fid = $_SESSION['user']['facility'];
		//		$params->uid = $_SESSION['user']['id'];
		//		Matcha::pauseLog(true);
		//		$record = $this->l->save($params);
		//		Matcha::pauseLog(false);
		return $params;
	}

}
