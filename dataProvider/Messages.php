<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once (dirname(__FILE__) . '/../classes/Time.php');
include_once (dirname(__FILE__) . '/Person.php');

class Messages extends MatchaHelper
{
    /**
     * Data Objects
     */
    private $Patient = null;
	private $User = null;
    private $Messages = null;

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	public function getMessages(stdClass $params)
	{
        $messages = array();
        $Where = new stdClass();

        if($this->Patient == null) $this->Patient = MatchaModel::setSenchaModel('App.model.patient.Patient');
        if($this->User == null) $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
        if($this->Messages == null) $this->Messages = MatchaModel::setSenchaModel('App.model.messages.Messages');

        $uid = $_SESSION['user']['id'];

        if(isset($params->get))
        {
            if($params->get == 'inbox')
            {
                $Where->to_deleted = 0;
                $Where->to_id = $uid;
            }
            if($params->get == 'sent')
            {
                $Where->from_deleted =0;
                $Where->from_id = $uid;
            }
            if($params->get == 'trash')
            {
                $Where->to_deleted = 1;
                $Where->to_id = $uid;
                $Where->from_deleted= 1;
            }
        }
        else
        {
	        $Where = null;
        }
		foreach($this->Messages->load($Where)->all() as $row)
        {
            $UserTo = $this->User->load(array('id' => $row['to_id']))->one();
            $row['to_user'] = $UserTo['title'] . ' ' . Person::fullname($UserTo['fname'], $UserTo['mname'], $UserTo['lname']);

            $Patient = $this->Patient->load(array('pid'=>$row['pid']))->one();
			$row['patient_name'] = Person::fullname($Patient['fname'], $Patient['mname'], $Patient['lname']);

            $UserFrom = $this->User->load(array('id'=>$row['from_id']))->one();
			$row['from_user'] = $UserFrom['title'] . ' ' . Person::fullname($UserFrom['fname'], $UserFrom['mname'], $UserFrom['lname']);
			array_push($messages, $row);
		}
        return $messages;
	}

	public function sendNewMessage(stdClass $params)
	{
        if($this->Messages == null) $this->Messages = MatchaModel::setSenchaModel('App.model.messages.Messages');
        $row = new stdClass();
		$t = Time::getLocalTime('l jS \of F Y h:i:s A');
		$row->body = 'On ' . $t . ' - <span style="font-weight:bold">' . $_SESSION['user']['name'] . '</span> - Wrote:<br><br>' . $params->body;
		$row->pid = $params->pid;
		$row->from_id = $_SESSION['user']['id'];
		$row->to_id = $params->to_id;
		$row->facility_id = $_SESSION['site']['dir'];
		$row->authorized = $params->authorized;
		$row->message_status = $params->message_status;
		$row->subject = $params->subject;
		$row->note_type = $params->note_type;
        $this->Messages->save($row);
	}

	public function replyMessage(stdClass $params)
	{
        if($this->Messages == null) $this->Messages = MatchaModel::setSenchaModel('App.model.messages.Messages');
        $row = new stdClass();
		$t = Time::getLocalTime('l jS \of F Y h:i:s A');
        $row->body = 'On ' . $t . ' - <span style="font-weight:bold">' . $_SESSION['user']['name'] . '</span> - Wrote:<br><br>' . $params->body . '<br><br>';
        $row->from_id = $_SESSION['user']['id'];
		$row->to_id = $params->to_id;
		$row->message_status = $params->message_status;
		$row->subject = $params->subject;
		$row->note_type = $params->note_type;
		$row->to_deleted = 0;
		$row->from_deleted = 0;
        $row->id = $params->id;
        $this->Messages->save($row);
	}

	public function deleteMessage(stdClass $params)
	{
        if($this->Messages == null) $this->Messages = MatchaModel::setSenchaModel('App.model.messages.Messages');
        $row = new stdClass();
        $row->id = $params->id;
        $Message = $this->Messages->load(array('id'=>$params->id), array('to_id', 'from_id'))->one();
		if($Message['to_id'] == $_SESSION['user']['id'])
        {
            $row->to_deleted = 1;
		}
        elseif($Message['from_id'] == $_SESSION['user']['id'])
        {
            $row->from_deleted = 1;
		}
        $this->Messages->save($row);
	}

	public function updateMessage(stdClass $params)
	{
        if($this->Messages == null) $this->Messages = MatchaModel::setSenchaModel('App.model.messages.Messages');
        $this->Messages->save($params);
	}

}