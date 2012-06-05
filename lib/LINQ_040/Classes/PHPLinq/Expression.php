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


/** PHPLinq_Function */
require_once('PHPLinq/Function.php');


/**
 * PHPLinq_Expression
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
class PHPLinq_Expression {
	/**
	 * Default name
	 * 
	 * @var string
	 */
	private $_defaultName = null;
	
	/**
	 * Internal function reference
	 *
	 * @var PHPLinq_Function
	 */
	private $_functionReference;
	
	/**
	 * Construct expression
	 *
	 * @param 	string $expression		Expression to create
	 * @param 	string $defaultName		Default name in expression
	 */
	public function __construct($expression, $defaultName = '') {
		// Default name
		$this->_defaultName = $defaultName;
		
		// Split out variable name
		if (strpos($expression, '=>') !== false && strpos($expression, '$') < strpos($expression, '=>')) {
			list($defaultName, $expression) = explode('=>', $expression, 2);
		}

		// Clean expression
		$defaultName 	= trim($defaultName);
		$expression 	= trim($expression);
		
		// Convert anonymous constructors
		$expression		= str_ireplace('new {', 'new{', $expression);
		$expression		= str_ireplace('new{', '(object)array(', $expression);
		$expression		= str_ireplace('}', ')', $expression);

		// Create PHPLinq_Function instance
		$this->_functionReference = new PHPLinq_Function($defaultName, 'return ' . $expression . ';');
	}
	
	/**
	 * Execute expression
	 * 
	 * @param 	mixed	$value	Value to use as expression parameter
	 * @return 	mixed			Expression result
	 */
	public function execute($value) {
		if (is_array($value)) {
			return call_user_func_array($this->getFunctionReference(), $value);
		} else {
			return call_user_func($this->getFunctionReference(), $value);
		}
	}
	
	/**
	 * Get default name
	 *
	 * @return string
	 */
	public function getDefaultName() {
		return $this->_defaultName;
	}
	
	/**
	 * Get function
	 *
	 * @return PHPLinq_Function
	 */
	public function getFunction() {
		return $this->_functionReference;
	}
	
	/**
	 * Get function reference
	 *
	 * @return mixed
	 */
	public function getFunctionReference() {
		return $this->_functionReference->getFunctionReference();
	}
}