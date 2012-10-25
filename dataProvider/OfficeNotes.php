<?php
/*
 GaiaEHR (Electronic Health Records)
 OfficeNotes.php
 Office Notes dataProvider
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
class OfficeNotes extends dbHelper
{

	public function getOfficeNotes(stdClass $params)
	{
		$wherex = (isset($params -> show)) ? 'WHERE activity = 1' : '';
		$this -> setSQL("SELECT * FROM onotes $wherex ORDER BY date DESC LIMIT $params->start, $params->limit");
		$rows = array();
		foreach ($this->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			array_push($rows, $row);
		}
		return $rows;
	}

	public function addOfficeNotes(stdClass $params)
	{

		$params -> user = $_SESSION['user']['name'];
		$params -> date = date('Y-m-d H:i:s');
		$params -> activity = 1;

		$data = get_object_vars($params);
		$sql = $this -> sqlBind($data, 'onotes', 'I');
		$this -> setSQL($sql);
		$this -> execLog();

		return $params;
	}

	public function updateOfficeNotes(stdClass $params)
	{
		$data = get_object_vars($params);
		$sql = $this -> sqlBind($data, 'onotes', 'U', 'id="' . $params -> id . '"');
		$this -> setSQL($sql);
		$this -> execLog();

		return $params;
	}

}
