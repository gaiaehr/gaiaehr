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

/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/' . PATH_SEPARATOR . './resources/');

/** PHPLinq_LinqToZendDb */
require_once 'PHPLinq/LinqToZendDb.php';
PHPLinq_LinqToZendDb::setQueryCallback('print'); // Output generated queries to console

// Create data source
require_once 'employees.db.inc.php';

// Query data
$result = from('$employee')->in($employeeTable)->any('$employee => $employee->Age == 24');
echo 'Any($employee => $employee->Age == 24): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employeeTable)->any('$employee => $employee->Age == 12');
echo 'Any($employee => $employee->Age == 12): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employeeTable)->all('$employee => $employee->Age > 20');
echo 'All($employee => $employee->Age > 20): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employeeTable)->all('$employee => $employee->Email == "test@example.com"');
echo 'All($employee => $employee->Email == "test@example.com"): ' . ($result ? 'true' : 'false') . "\r\n";

$someEmployee = from('$employee')->in($employeeTable)->take(1)->select();
$someEmployee = $someEmployee[0];
$result = from('$employee')->in($employeeTable)->contains($someEmployee);
echo 'Contains($someEmployee): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employeeTable)->contains(new Employee());
echo 'Contains(new Employee()): ' . ($result ? 'true' : 'false') . "\r\n";


// Query data
$result = from('$employee')->in($employeeTable)
			->first();
echo 'First Employee: ' . "\r\n";	
print_r($result);	

$result = from('$employee')->in($employeeTable)
			->last();
echo 'Last Employee: ' . "\r\n";	
print_r($result);		

