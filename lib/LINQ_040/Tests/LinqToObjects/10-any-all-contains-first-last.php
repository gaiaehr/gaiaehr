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

/** PHPLinq_LinqToObjects */
require_once 'PHPLinq/LinqToObjects.php';

// Create data source
$employees = null;
require_once('employees.inc.php');

// Query data
$result = from('$employee')->in($employees)->any('$employee => $employee->Age == 24');
echo 'Any($employee => $employee->Age == 24): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employees)->any('$employee => $employee->Age == 12');
echo 'Any($employee => $employee->Age == 12): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employees)->all('$employee => strpos($employee->Email, "example.com") !== false');
echo 'All($employee => strpos($employee->Email, "example.com") !== false): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employees)->all('$employee => $employee->Email == "test@example.com"');
echo 'All($employee => $employee->Email == "test@example.com"): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employees)->contains($employees[1]);
echo 'Contains($employees[1]): ' . ($result ? 'true' : 'false') . "\r\n";

$result = from('$employee')->in($employees)->contains(new Employee());
echo 'Contains(new Employee()): ' . ($result ? 'true' : 'false') . "\r\n";


// Add some extra info to the employees array
$employees[] = 54321;
$employees[] = "String included";
$employees[] = 12345;
$employees[] = "Another string";

// Query data
$result = from('$employee')->in($employees)
			->ofType('Employee')
			->first();
echo 'First Employee: ' . "\r\n";	
print_r($result);	

$result = from('$employee')->in($employees)
			->last();
echo 'Last element (Another string): ' . $result . "\r\n";		

