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
class Amendments {

	/**
	 * @var MatchaCUP
	 */
	private $a;

	function __construct() {
		$this->a = MatchaModel::setSenchaModel('App.model.miscellaneous.Amendment');
		$this->a->setOrFilterProperties(['assigned_to_uid']);
	}

	public function getAmendment($params) {
		Matcha::pauseLog(true);
		$records = $this->a->load($params)->leftJoin([
			'title' => 'response_title',
			'fname' => 'response_fname',
			'mname' => 'response_mname',
			'lname' => 'response_lname'
		], 'users', 'response_uid', 'id')->one();
		Matcha::pauseLog(false);
		return $records;
	}

	public function getAmendments($params) {
		Matcha::pauseLog(true);
		$records =  $this->a->load($params)->leftJoin([
			'title' => 'response_title',
			'fname' => 'response_fname',
			'mname' => 'response_mname',
			'lname' => 'response_lname'
		], 'users', 'response_uid', 'id')->all();
		Matcha::pauseLog(false);
		return $records;
	}

	public function addAmendment($params) {
		return $this->a->save($params);
	}

	public function updateAmendment($params) {
		return $this->a->save($params);
	}

	public function destroyAmendment($params) {
		return $this->a->destroy($params);
	}

	public function getUnreadAmendments($getUnAssigned){
		$filter = new stdClass();
		$filter->filter[0] = new stdClass();
		$filter->filter[0]->property = 'is_read';
		$filter->filter[0]->value = '0';

		if(!$getUnAssigned){
			$filter->filter[1] = new stdClass();
			$filter->filter[1]->property = 'assigned_to_uid';
			$filter->filter[1]->value = $_SESSION['user']['id'];
		}else{
			$filter->filter[1] = new stdClass();
			$filter->filter[1]->property = 'assigned_to_uid';
			$filter->filter[1]->value = $_SESSION['user']['id'];

			$filter->filter[2] = new stdClass();
			$filter->filter[2]->property = 'assigned_to_uid';
			$filter->filter[2]->value = '0';
		}

		return $this->getAmendments($filter);

	}

	public function getUnViewedAmendments($getUnAssigned){
		$filter = new stdClass();
		$filter->filter[0] = new stdClass();
		$filter->filter[0]->property = 'is_viewed';
		$filter->filter[0]->value = '0';

		if(!$getUnAssigned){
			$filter->filter[1] = new stdClass();
			$filter->filter[1]->property = 'assigned_to_uid';
			$filter->filter[1]->value = $_SESSION['user']['id'];
		}else{
			$filter->filter[1] = new stdClass();
			$filter->filter[1]->property = 'assigned_to_uid';
			$filter->filter[1]->value = $_SESSION['user']['id'];

			$filter->filter[2] = new stdClass();
			$filter->filter[2]->property = 'assigned_to_uid';
			$filter->filter[2]->value = '0';
		}

		return $this->getAmendments($filter);

	}

}

