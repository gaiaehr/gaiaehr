<?php
/*
 GaiaEHR (Electronic Health Records)
 Messages.php
 Messages dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/Time.php');
include_once ($_SESSION['root'] . '/dataProvider/Person.php');
class Messages extends dbHelper
{

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getMessages(stdClass $params)
	{
		$currUser = $_SESSION['user']['id'];

		if (isset($params -> get))
		{
			if ($params -> get == 'inbox')
			{
				$wherex = "messages.to_deleted = '0' AND users.id = '$currUser'";
			}
			elseif ($params -> get == 'sent')
			{
				$wherex = "messages.from_deleted = '0' AND messages.from_id = '$currUser'";
			}
			elseif ($params -> get == 'trash')
			{
				$wherex = "messages.to_deleted = '1' OR messages.from_deleted = '1' AND users.id = '$currUser'";
			}
		}
		else
		{
			$wherex = "messages.to_deleted = '0' AND users.id = '$currUser'";
		}

		$this -> setSQL("SELECT messages.* ,
                              users.title AS user_title,
                              users.fname AS user_fname,
                              users.mname AS user_mname,
                              users.lname AS user_lname,
                              patient_demographics.fname AS patient_fname,
                              patient_demographics.mname AS patient_mname,
                              patient_demographics.lname AS patient_lname
                         FROM messages
              LEFT OUTER JOIN patient_demographics ON messages.pid = patient_demographics.pid
              LEFT OUTER JOIN users ON messages.to_id = users.id
                        WHERE $wherex
                     ORDER BY messages.date
                        LIMIT $params->start, $params->limit");
		$messages = array();
		foreach ($this->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			$row['patient_name'] = Person::fullname($row['patient_fname'], $row['patient_mname'], $row['patient_lname']);

			$id = $row['from_id'];
			$this -> setSQL("SELECT title, fname, mname, lname FROM users WHERE id ='$id' ");
			$record = $this -> fetchRecord();

			$row['from_user'] = $record['user_title'] . ' ' . Person::fullname($record['fname'], $record['mname'], $record['lname']);
			$row['to_user'] = $row['user_title'] . ' ' . Person::fullname($row['user_fname'], $row['user_mname'], $row['user_lname']);
			array_push($messages, $row);
		}
		$total = count($messages);
		return array(
			'totals' => $total,
			'messages' => $messages
		);
	}

	/**
	 * @param stdClass $params
	 * @return array|stdClass
	 */
	public function sendNewMessage(stdClass $params)
	{

		$t = Time::getLocalTime('l jS \of F Y h:i:s A');
		$row['body'] = 'On ' . $t . ' - <spam style="font-weight:bold">' . $_SESSION['user']['name'] . '</spam> - Wrote:<br><br>' . $params -> body;
		$row['pid'] = $params -> pid;
		$row['from_id'] = $_SESSION['user']['id'];
		$row['to_id'] = $params -> to_id;
		$row['facility_id'] = $_SESSION['site']['dir'];
		$row['authorized'] = $params -> authorized;
		$row['message_status'] = $params -> message_status;
		$row['subject'] = $params -> subject;
		$row['note_type'] = $params -> note_type;
		$sql = $this -> sqlBind($row, 'messages', 'I');
		$this -> setSQL($sql);
		$ret = $this -> execLog();
		if ($ret[2])
		{
			return array('success' => false);
		}
		else
		{
			return array('success' => true);
		}

	}

	/**
	 * @param stdClass $params
	 * @return array|stdClass
	 */
	public function replyMessage(stdClass $params)
	{

		$t = Time::getLocalTime('l jS \of F Y h:i:s A');
		$row['body'] = 'On ' . $t . ' - <spam style="font-weight:bold">' . $_SESSION['user']['name'] . '</spam> - Wrote:<br><br>' . $params -> body . '<br><br>';
		$row['from_id'] = $_SESSION['user']['id'];
		$row['to_id'] = $params -> to_id;
		$row['message_status'] = $params -> message_status;
		$row['subject'] = $params -> subject;
		$row['note_type'] = $params -> note_type;
		$row['to_deleted'] = 0;
		$row['from_deleted'] = 0;

		$sql = $this -> sqlBind($row, 'messages', 'U', array('id' => $params -> id));
		$this -> setSQL($sql);
		$ret = $this -> execLog();

		if ($ret[2])
		{
			return array('success' => false);
		}
		else
		{
			return array('success' => true);
		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function deleteMessage(stdClass $params)
	{
		$id = $params -> id;
		$currUser = $_SESSION['user']['id'];

		$this -> setSQL("SELECT to_id, from_id FROM messages WHERE id = '$id'");
		$record = $this -> fetchRecord();

		if ($record['to_id'] == $currUser)
		{
			$sql = "UPDATE messages SET to_deleted = '1' WHERE id='$id'";
		}
		elseif ($record['from_id'] == $currUser)
		{
			$sql = "UPDATE messages SET from_deleted = '1' WHERE id='$id'";
		}

		$this -> setSQL($sql);
		$ret = $this -> execLog();

		if ($ret[2])
		{
			return array('success' => false);
		}
		else
		{
			return array('success' => true);
		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updateMessage(stdClass $params)
	{

		$row[$params -> col] = $params -> val;

		$sql = $this -> sqlBind($row, 'messages', 'U', array('id' => $params -> id));
		$this -> setSQL($sql);
		$ret = $this -> execLog();
		if ($ret[2])
		{
			return array('success' => false);
		}
		else
		{
			return array('success' => true);
		}

	}

}
