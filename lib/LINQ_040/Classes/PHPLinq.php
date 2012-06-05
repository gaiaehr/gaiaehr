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


/** PHPLinq */
require_once('PHPLinq.php');

/** PHPLinq_Exception */
require_once('PHPLinq/Exception.php');

/** PHPLinq_ILinqProvider */
require_once('PHPLinq/ILinqProvider.php');

/** PHPLinq_Initiator */
require_once('PHPLinq/Initiator.php');


/**
 * LinqToObjects initiator - Set variable name to use as default
 *
 * @param string $name
 * @return PHPLinq_Initiator
 */
function linqfrom($name) {
	return new PHPLinq_Initiator($name);
}

// Create easy-to-use initiator ("from")
if (!function_exists('from')) {
	/**
	 * Set variable name to use as default
	 *
	 * @param string $name
	 * @return PHPLinq_Initiator
	 */
	function from($name) {
		return linqfrom($name);
	}
} else {
	error_log('A function with the name "from" already exists. Use linqfrom($name) instead of from($name).', E_NOTICE);
}