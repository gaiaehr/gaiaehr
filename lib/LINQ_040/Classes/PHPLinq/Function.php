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


/**
 * PHPLinq_Function
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
class PHPLinq_Function {
	/**
	 * Parameter names
	 *
	 * @var string
	 */
	private $_parameterNames;
	
	/**
	 * Function code
	 *
	 * @var string
	 */
	private $_functionCode;
	
	/**
	 * Function reference
	 *
	 * @var mixed
	 */
	private $_functionReference = null;
	
	/**
	 * Construct function
	 *
	 * @param string $parameterNames Parameter names
	 * @param string $functionCode Function code
	 * @throws Exception
	 */
	public function __construct($parameterNames = '', $functionCode = '') {
		// Check parameters
		if (strpos($parameterNames, '$') === false) {
			throw new PHPLinq_Exception('Missing arguments in parameter $parameterNames.');
		}
		if (strpos($functionCode, 'return') === false) {
			throw new PHPLinq_Exception('Missing return statement in parameter $functionCode.');
		}
		
		// Store parameters
		$this->_parameterNames 		= $parameterNames;
		$this->_functionCode 		= $functionCode;
		$this->_functionReference 	= null;
	}
	
	/**
	 * Get function reference
	 *
	 * @return mixed
	 */
	public function getFunctionReference() {
		if (is_null($this->_functionReference)) {
			// Compile anonymous function
			$this->_functionReference = create_function($this->_parameterNames, $this->_functionCode);
		}
		
		return $this->_functionReference;
	}
	
	/**
	 * Get parameters
	 *
	 * @return string
	 */
	public function getParameterNames() {
		return $this->_parameterNames;
	}
	
	/**
	 * Get source code
	 *
	 * @return string
	 */
	public function getFunctionCode() {
		return $this->_functionCode;
	}
}