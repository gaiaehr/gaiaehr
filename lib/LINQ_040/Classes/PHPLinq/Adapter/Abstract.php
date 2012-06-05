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
 * @package    PHPLinq_Adapter
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 * @license    http://www.gnu.org/licenses/lgpl.txt	LGPL
 * @version    0.4.0, 2009-01-27
 */


/** PHPLinq_Adapter_Exception */
require_once 'PHPLinq/Adapter/Exception.php';

/** Zend_Db_Adapter_Abstract */
require_once 'Zend/Db/Adapter/Abstract.php';


/**
 * PHPLinq_Adapter_Abstract
 *
 * @category   PHPLinq
 * @package    PHPLinq_Adapter
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
abstract class PHPLinq_Adapter_Abstract {
	
	// CHECK http://en.wikibooks.org/wiki/SQL_dialects_reference/Functions_and_expressions/String_functions
	
	/**
	 * Zend_Db_Adapter_Abstract
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_adapter = null;
	
	/**
	 * Constructor
	 *
	 * @param Zend_Db_Adapter_Abstract $adapter
	 */
	public function __construct(Zend_Db_Adapter_Abstract $adapter = null) {
		$this->_adapter = $adapter;
	}
	
	/**
     * Safely quotes a value for an SQL statement.
     *
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string.
     *
     * @param mixed $value The value to quote.
     * @param mixed $type  OPTIONAL the SQL datatype name, or constant, or null.
     * @return mixed An SQL-safe quoted value (or string of separated values).
     */
    public function quote($value, $type = null)
    {
    	return $this->_adapter->quote($value, $type);
    }
    
    /**
     * Quotes an identifier.
     *
     * Accepts a string representing a qualified indentifier. For Example:
     * <code>
     * $adapter->quoteIdentifier('myschema.mytable')
     * </code>
     * Returns: "myschema"."mytable"
     *
     * Or, an array of one or more identifiers that may form a qualified identifier:
     * <code>
     * $adapter->quoteIdentifier(array('myschema','my.table'))
     * </code>
     * Returns: "myschema"."my.table"
     *
     * The actual quote character surrounding the identifiers may vary depending on
     * the adapter.
     *
     * @param string|array|Zend_Db_Expr $ident The identifier.
     * @param boolean $auto If true, heed the AUTO_QUOTE_IDENTIFIERS config option.
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($ident, $auto=false)
    {
    	return $this->_adapter->quoteIdentifier($ident, $auto);
    }
	
	/**
	 * List of functions
	 *
	 * @var array
	 */
	protected static $_functions = array(
		'custom',
		'ord',
		'chr',
		'substr',
		'strlen',
		'count',
		'max',
		'min',
		'abs',
		'strtolower',
		'strtoupper',
		'ltrim',
		'rand',
		'str_replace',
		'round',
		'rtrim',
		'trim',
		'strpos',
		'stripos',
		'lcfirst',
		'ucfirst',
		'md5',
		'sha1',
		'soundex',
		'addslashes',
		'str_repeat'
	);
	
	/**
	 * Get list of functions
	 * 
	 * @return array;
	 */
	public static function getFunctions() {
		return self::$_functions;
	}
	
	/**
	 * Convert operator
	 *
	 * @param string $operator
	 * @return string
	 */
	public function operator($operator) {
		switch ($operator) {
			case '=':
			case '!=':
			case '>=':
			case '<=':
			case '>':
			case '<':
			case '+':
			case '-':
			case '*':
			case '/':
			case '%':
			case '.':
				return $operator;
		}
	}
	
	/**
	 * Convert operand
	 *
	 * @param string $operand
	 * @return string
	 */
	public function operand($operand) {
		switch ($operand) {
			case 'AND':
			case 'OR':
				return $operand;
		}
	}
	
	/**
	 * Execute a custom function (outside of PHPLinq_Adapter package).
	 *
	 * @param array $function Function to call
	 */
	public function custom() {
		$arguments = func_get_args();
		$functionName = array_shift($arguments);
		$functionName = trim($functionName, '"\'');
		
		return call_user_func_array( $functionName, $arguments );
	}
	
	/**
	 * Return ASCII value of character
	 *
	 * @param string $string A character
	 * @return string
	 */
	abstract public function ord($string);
	
	/**
	 * Return a specific character
	 *
	 * @param string $string The ascii code
	 * @return string
	 */
	abstract public function chr($string);
	
	/**
	 * Return part of a string
	 *
	 * @param string $string
	 * @param int $start
	 * @param int $length
	 * @return string
	 */
	abstract public function substr($string, $start, $length = '');
	
	/**
	 * Get string length
	 *
	 * @param string $string
	 * @return string
	 */
	abstract public function strlen($string);
	
	/**
	 * Count elements in an array, or properties in an object
	 *
	 * @param mixed $var
	 * @return string
	 */
	abstract public function count($var);
	
	/**
	 * Find highest value
	 *
	 * @param mixed $values
	 * @return string
	 */
	abstract public function max($values);
	
	/**
	 * Find lowest value
	 *
	 * @param mixed $values
	 * @return string
	 */
	abstract public function min($values);
	
	/**
	 * Absolute value
	 *
	 * @param mixed $number
	 * @return string
	 */
	abstract public function abs($number);
	
	/**
	 * Make a string lowercase
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function strtolower($str);
	
	/**
	 * Make a string uppercase
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function strtoupper($str);
	
	/**
	 * Strip whitespace (or other characters) from the beginning of a string
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function ltrim($str);
	
	/**
	 * Generate a random integer
	 * 
	 * @return string
	 */
	abstract public function rand();
	
	/**
	 * Replace all occurrences of the search string with the replacement string
	 *
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $subject
	 * @return string
	 */
	abstract public function str_replace($search, $replace, $subject);
	
	/**
	 * Rounds a float
	 *
	 * @param float $val
	 * @param int $precision
	 * @return string
	 */
	abstract public function round($val, $precision = 0);
	
	/**
	 * Strip whitespace (or other characters) from the end of a string
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function rtrim($str);
	
	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function trim($str);
	
	/**
	 * Find position of first occurrence of a string
	 *
	 * @param string $haystack
	 * @param mixed $needle
	 * @param int $offset
	 * @return string
	 */
	abstract public function strpos($haystack, $needle, $offset = 0);
	
	/**
	 * Find position of first occurrence of a case-insensitive string
	 *
	 * @param string $haystack
	 * @param mixed $needle
	 * @param int $offset
	 * @return string
	 */
	abstract public function stripos($haystack, $needle, $offset = 0);
	
	/**
	 * Make a string's first character lowercase
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function lcfirst($str);
	
	/**
	 * Make a string's first character uppercase
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function ucfirst($str);
	
	/**
	 * Calculate the md5 hash of a string
	 *
	 * @param string $str
	 * @param boolean $raw_output
	 * @return string
	 */
	abstract public function md5($str, $raw_output = false);
	
	/**
	 * Calculate the sha1 hash of a string
	 *
	 * @param string $str
	 * @param boolean $raw_output
	 * @return string
	 */
	abstract public function sha1($str, $raw_output = false);
	
	/**
	 * Calculate the soundex key of a string
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function soundex($str);
	
	/**
	 * Quote string with slashes
	 *
	 * @param string $str
	 * @return string
	 */
	abstract public function addslashes($str);
	
	/**
	 * Repeat a string
	 *
	 * @param string $input
	 * @param int $multiplier
	 * @return string
	 */
	abstract public function str_repeat($input, $multiplier);
}