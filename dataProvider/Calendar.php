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

include_once(dirname(__FILE__) . '/Person.php');
class Calendar {
	/**
	 * @var MatchaHelper
	 */
	private $db;
	/**
	 * @var MatchaCUP
	 */
	private $u;
	private $c;
	private $e;

	/**
	 * Creates the MatchaHelper instance
	 */
	function __construct(){
		$this->db = new MatchaHelper();
		$this->u = MatchaModel::setSenchaModel('App.model.administration.User');
		$this->c = MatchaModel::setSenchaModel('App.model.calendar.Category');
		$this->e = MatchaModel::setSenchaModel('App.model.calendar.Events');

		return;
	}

	/**
	 *
	 * getCalendars function
	 * Calendars = Providers or Users configured to be in the calendar
	 *
	 * @return array
	 */
	public function getCalendars(){
		$calendars = array();
		$color = -4;
		$users = $this->u->load(array('calendar' => 1, 'active' => 1))->all();
		foreach($users as $user){
			if($color > 32){
				$color = $color - 30;
			}
			$color = $color + 5;
			$calendar = array();
			$calendar['id'] = $user['id'];
			$calendar['title'] = $user['title'] . ' ' . $user['lname'];
			$calendar['color'] = strval($color);
			$calendars[] = $calendar;
		}
		return $calendars;
	}

	/**
	 * Events are the patient appointments
	 *
	 * @param stdClass $params
	 * @return array
	 */
	public function getEvents(stdClass $params){
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'start';
		$filters->filter[0]->operator = '>=';
		$filters->filter[0]->value = $params->startDate . ' 00:00:00';
		$filters->filter[1] = new stdClass();
		$filters->filter[1]->property = 'end';
		$filters->filter[1]->operator = '<=';
		$filters->filter[1]->value = $params->endDate . ' 23:59:59';
		$events = $this->e->load($params)->all();
		unset($filters);

		$rows = array();
		foreach($events as $event){
			$user = $this->u->load($event['uid'])->one();
			if($user !== false)	$row['title'] = Person::fullname($user['fname'], $user['mname'], $user['lname']);
			unset($user);

			$rows[] = $event;
		}

		return array('success' => true, 'message' => 'Loaded data', 'data' => $rows);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addEvent($params){
		return array('success' => true, 'message' => 'Loaded data', 'data' => $this->e->save($params));
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updateEvent(stdClass $params){
		return array('success' => true, 'message' => 'Updated data', 'data' => $this->e->save($params));
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function deleteEvent(stdClass $params){
		return $this->e->destroy($params);
	}

	public function getPatientFutureEvents(stdClass $params){
		return $this->getPatientFutureEventsByPid($params->pid);
	}

	public function getPatientFutureEventsByPid($pid){
		$date = Time::getLocalTime();
		$tomorrow = date('Y-m-d 0000:00:00', strtotime($date . ' + 1 days'));
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'pid';
		$filters->filter[0]->operator = '=';
		$filters->filter[0]->value = $pid;
		$filters->filter[1] = new stdClass();
		$filters->filter[1]->property = 'start';
		$filters->filter[1]->operator = '>=';
		$filters->filter[1]->value = $tomorrow;
		return $this->e->load($filters)->all();

	}

}
