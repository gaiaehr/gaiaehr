<?php
/*
 GaiaEHR (Electronic Health Records)
 AddressBook.php
 Address Book dataProvider
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
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}

include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Person.php');

class AddressBook
{

	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * Creates the dbHelper instance
	 */
	function __construct()
	{
		$this -> db = new dbHelper();
		return;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 * NOTES: Address of who?,
	 *  Naming: "getAddressesFromPatient" ???
	 */
	public function getAddresses(stdClass $params)
	{
		$this -> db -> setSQL("SELECT *
                         FROM users
                        WHERE users.active = 1 AND ( users.authorized = 1 OR users.username = '' )
                        LIMIT $params->start,$params->limit");
		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		$total = count($records);
		$rows = array();
		foreach ($records as $row)
		{
			$row['fullname'] = Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$row['fulladdress'] = Person::fulladdress($row['street'], $row['streetb'], $row['city'], $row['state'], $row['zip']);
			array_push($rows, $row);
		}
		return array(
			'totals' => $total,
			'rows' => $rows
		);
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 * NOTES: Add contact to who?
	 *  Naming: "AddContactToPatient"
	 */
	public function addContact(stdClass $params)
	{
		$data = get_object_vars($params);
		$sql = $this -> db -> sqlBind($data, "users", "I");
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		$params -> id = $this -> db -> lastInsertId;
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 * NOTES: Update contact address to who?
	 * Naming: "updatePatientAddress"
	 */
	public function updateAddress(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id'], $data['fullname'], $data['fulladdress']);
		$sql = $this -> db -> sqlBind($data, "users", "U", "id='" . $params -> id . "'");
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		$params -> fullname = Person::fullname($params -> fname, $params -> mname, $params -> lname);
		$params -> fulladdress = Person::fulladdress($params -> street, $params -> streetb, $params -> city, $params -> state, $params -> zip);
		return $params;
	}

}
