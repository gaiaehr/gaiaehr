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

// Department class
class Department {
	public $Id;
	public $Name;
  
	/**
	 * Constructor
	 *
	 * @param int $id
	 * @param string $name
	 */
	public function __construct($id = 0, $name = '') {
		$this->Id			= $id;
		$this->Name 		= $name;
	}
	
	/**
	 * Create instance from array
	 *
	 * @param array $values
	 * @return Department
	 */
	public static function fromArray(array $values) {
		return new Department(
			$values['Id'],
			$values['Name']
		);
	}
}

// Department data source
$departments = array(
	new Department(1, 'Development'),
	new Department(2, 'Operations'),
	new Department(3, 'Management')
);