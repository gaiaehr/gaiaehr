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

/** PHPLinq_OrderByExpression */
require_once('PHPLinq/Expression.php');


/**
 * PHPLinq_OrderByExpression
 *
 * @category   PHPLinq
 * @package    PHPLinq
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
class PHPLinq_OrderByExpression extends PHPLinq_Expression {
	/**
	 * Internal function reference
	 *
	 * @var PHPLinq_Function
	 */
	private $_functionReference;
	
	/**
	 * Default name
	 * 
	 * @var string
	 */
	private $_defaultName = null;
	
	/**
	 * Internal expression reference
	 * 
	 * @var PHPLinq_Expression
	 */
	private $_expression = null;
	
	/**
	 * Descending?
	 *
	 * @var bool
	 */
	private $_descending = false;
	
	/**
	 * Construct expression
	 *
	 * @param 	string 	$expression		Expression to create
	 * @param 	string 	$defaultName	Default name in expression
	 * @param  	bool	$descending		Descending order?
	 * @param  	string 	$comparer		Comparer function
	 */
	public function __construct($expression, $defaultName = '', $descending = false, $comparer = null) {
		// Default name
		$this->_defaultName = $defaultName;
		
		// Internal expression
		$this->_expression = new PHPLinq_Expression($expression, $defaultName);
		
		// Comparer function set?
		if (is_null($comparer)) {
			$comparer = 'strcmp';
		}
		
		// Descending?
		if ($descending) {
			$comparer = '-1 * ' . $comparer;
		}
		$this->_descending = $descending;
		
		// Compile comparer function
		//     Check http://www.php.net/manual/nl/function.create-function.php#14322 for this chr(0).'$f' approach...
		$f = substr($this->_expression->getFunctionReference(), 1); 
		$this->_functionReference = new PHPLinq_Function(
				$defaultName . 'A, ' . $defaultName . 'B',
				"return $comparer(
					call_user_func(chr(0).'$f', {$defaultName}A),
					call_user_func(chr(0).'$f', {$defaultName}B)
				);"
		);	
	}
	
	/**
	 * Execute expression
	 * 
	 * @param 	mixed	$value	Value to use as expression parameter
	 * @return 	mixed			Expression result
	 */
	public function execute($value) {
		// Unused...
		return $value;
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
	
	/**
	 * Get expression
	 *
	 * @return PHPLinq_Expression
	 */
	public function getExpression() {
		return $this->_expression;
	}
	
	/**
	 * Descending?
	 *
	 * @return boolean
	 */
	public function isDescending() {
		return $this->_descending;
	}
}