<?php
/**
 * PHPLinq
 *
 * Copyright (c) 2008 - 2009 PHPLinq
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 * @license    http://www.gnu.org/licenses/lgpl.txt	LGPL
 * @version    0.4.0, 2009-01-27
 */

// Employee class
class Employee {
	public $Id;
	public $DepartmentId;
	public $ManagerId;
	public $Name;
	public $Email;
	public $Age;
  
	/**
	 * Constructor
	 *
	 * @param int $id
	 * @param int $departmentId
	 * @param int $managerId
	 * @param string $name
	 * @param string $email
	 * @param int $age
	 */
	public function __construct($id = 0, $departmentId = 0, $managerId = 0, $name = '', $email = '', $age = '') {
		$this->Id				= $id;
		$this->DepartmentId 	= $departmentId;
		$this->ManagerId 		= $managerId;
		$this->Name 			= $name;
		$this->Email 			= $email;
		$this->Age				= $age;
	}
	
	/**
	 * Create instance from array
	 *
	 * @param array $values
	 * @return Employee
	 */
	public static function fromArray(array $values) {
		return new Employee(
			$values['Id'],
			$values['DepartmentId'],
			$values['ManagerId'],
			$values['Name'],
			$values['Email'],
			$values['Age']
		);
	}
}

// Employee data source
$employees = array(
	new Employee(1, 1, 5, 'Maarten', 'maarten@example.com', 24),
	new Employee(2, 1, 5, 'Paul', 'paul@example.com', 30),
	new Employee(3, 2, 5, 'Bill', 'bill.a@example.com', 29),
	new Employee(4, 3, 5, 'Bill', 'bill.g@example.com', 28),
	new Employee(5, 2, 0, 'Xavier', 'xavier@example.com', 40)
);