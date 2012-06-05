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

// List of tests
$aTests = array(
	  'LinqToObjects/01-simple.php'
	, 'LinqToObjects/02-objects.php'
	, 'LinqToObjects/03-anonymousclass.php'
	, 'LinqToObjects/04-simple-orderby.php'
	, 'LinqToObjects/05-advanced-orderby.php'
	, 'LinqToObjects/06-advanced-orderby2.php'
	, 'LinqToObjects/07-rss-example.php'
	, 'LinqToObjects/08-distinct.php'
	, 'LinqToObjects/09-oftype.php'
	, 'LinqToObjects/10-any-all-contains-first-last.php'
	, 'LinqToObjects/11-join.php'
	
	, 'LinqToZendDb/01-simple.php'
	, 'LinqToZendDb/02-objects.php'
	, 'LinqToZendDb/03-anonymousclass.php'
	, 'LinqToZendDb/04-simple-orderby.php'
	, 'LinqToZendDb/05-advanced-orderby.php'
	, 'LinqToZendDb/06-advanced-orderby2.php'
	, 'LinqToZendDb/07-custom.php'
	, 'LinqToZendDb/08-distinct.php'
	, 'LinqToZendDb/09-oftype.php'
	, 'LinqToZendDb/10-any-all-contains-first-last.php'
	, 'LinqToZendDb/11-join.php'
);

// Run all tests
foreach ($aTests as $sTest) {
	echo '============== TEST ==============' . "\r\n";
	echo 'Test name: ' . $sTest . "\r\n";
	echo "\r\n";
	echo shell_exec('php ' . $sTest);
	echo "\r\n";
	echo "\r\n";
}
