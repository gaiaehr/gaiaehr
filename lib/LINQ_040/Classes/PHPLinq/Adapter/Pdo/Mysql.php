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


/** PHPLinq_Adapter_Abstract */
require_once 'PHPLinq/Adapter/Abstract.php';


/**
 * PHPLinq_Adapter_Pdo_Mysql
 *
 * @category   PHPLinq
 * @package    PHPLinq_Adapter
 * @copyright  Copyright (c) 2008 - 2009 PHPLinq (http://www.codeplex.com/PHPLinq)
 */
class PHPLinq_Adapter_Pdo_Mysql extends PHPLinq_Adapter_Abstract {
	/**
	 * Constructor
	 *
	 * @param Zend_Db_Adapter_Abstract $adapter
	 */
	public function __construct(Zend_Db_Adapter_Abstract $adapter = null) {
		return parent::__construct($adapter);
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
				return $operator;
			case '.':
				return '||';
		}
	}
	
	/**
	 * Return ASCII value of character
	 *
	 * @param string $string A character
	 * @return string
	 */
	public function ord($string) {
		return 'ASCII(' . $string . ')';
	}
	
	/**
	 * Return a specific character
	 *
	 * @param int $string The ascii code
	 * @return string
	 */
	public function chr($ascii) {
		return 'CHAR(' . $string . ')';
	}
	
	/**
	 * Return part of a string
	 *
	 * @param string $string
	 * @param int $start
	 * @param int $length
	 * @return string
	 */
	public function substr($string, $start, $length = '') {
		if ($length == '') {
			return 'SUBSTRING(' . $string . ', ' . $start . ')';
		}
		
		return 'SUBSTRING(' . $string . ', ' . $start . ', ' . $length . ')';
	}
	
	/**
	 * Get string length
	 *
	 * @param string $string
	 * @return string
	 */
	public function strlen($string) {
		return 'CHAR_LENGTH(' . $string . ')';
	}
	
	/**
	 * Count elements in an array, or properties in an object
	 *
	 * @param mixed $var
	 * @return string
	 */
	public function count($var) { 
		return 'COUNT(' . $var . ')';
	}
	
	/**
	 * Find highest value
	 *
	 * @param mixed $values
	 * @return string
	 */
	public function max($values) { 
		return 'MAX(' . $values . ')';
	}
	
	/**
	 * Find lowest value
	 *
	 * @param mixed $values
	 * @return string
	 */
	public function min($values) { 
		return 'MIN(' . $values . ')';
	}
	
	/**
	 * Absolute value
	 *
	 * @param mixed $number
	 * @return string
	 */
	public function abs($number) {
		return 'ABS(' . $number . ')';
	}
	
	/**
	 * Make a string lowercase
	 *
	 * @param string $str
	 * @return string
	 */
	public function strtolower($str) { 
		return 'LOWER(' . $str . ')';
	}
	
	/**
	 * Make a string uppercase
	 *
	 * @param string $str
	 * @return string
	 */
	public function strtoupper($str) {
		return 'UPPER(' . $str . ')';
	}
	
	/**
	 * Strip whitespace (or other characters) from the beginning of a string
	 *
	 * @param string $str
	 * @return string
	 */
	public function ltrim($str) {
		return 'LTRIM(' . $str . ')';
	}
	
	/**
	 * Generate a random integer
	 * 
	 * @return string
	 */
	public function rand() { 
		return 'RAND()';
	}
	
	/**
	 * Replace all occurrences of the search string with the replacement string
	 *
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $subject
	 * @return string
	 */
	public function str_replace($search, $replace, $subject) {
		return 'REPLACE(' . $subject . ', ' . $search  . ', ' . $replace . ')';
	}
	
	/**
	 * Rounds a float
	 *
	 * @param float $val
	 * @param int $precision
	 * @return string
	 */
	public function round($val, $precision = 0) {
		return 'ROUND(' . $val . ', ' . $precision . ')';
	}
	
	/**
	 * Strip whitespace (or other characters) from the end of a string
	 *
	 * @param string $str
	 * @return string
	 */
	public function rtrim($str) {
		return 'RTRIM(' . $str . ')';
	}
	
	/**
	 * Strip whitespace (or other characters) from the beginning and end of a string
	 *
	 * @param string $str
	 * @return string
	 */
	public function trim($str) {
		return 'TRIM(' . $str . ')';
	}
	
	/**
	 * Find position of first occurrence of a string
	 *
	 * @param string $haystack
	 * @param mixed $needle
	 * @param int $offset
	 * @return string
	 */
	public function strpos($haystack, $needle, $offset = 0) {
		return 'LOCATE(' . $needle . ', ' . $haystack . ',  ' . $offset . ')';
	}
	
	/**
	 * Find position of first occurrence of a case-insensitive string
	 *
	 * @param string $haystack
	 * @param mixed $needle
	 * @param int $offset
	 * @return string
	 */
	public function stripos($haystack, $needle, $offset = 0) {
		return $this->strpos( $this->strtolower($haystack), $this->strtolower($needle), $offset );
	}
	
	/**
	 * Make a string's first character lowercase
	 *
	 * @param string $str
	 * @return string
	 */
	public function lcfirst($str) {
		return 'CONCAT(' . $this->strtolower($this->substr($str, 0, 1)) . ', ' . $this->substr($str, 1, $this->strlen($str) - 1) . ')';
	}
	
	/**
	 * Make a string's first character uppercase
	 *
	 * @param string $str
	 * @return string
	 */
	public function ucfirst($str) {
		return 'CONCAT(' . $this->strtoupper($this->substr($str, 0, 1)) . ', ' . $this->substr($str, 1, $this->strlen($str) - 1) . ')';
	}
	
	/**
	 * Calculate the md5 hash of a string
	 *
	 * @param string $str
	 * @param boolean $raw_output
	 * @return string
	 */
	public function md5($str, $raw_output = false) {
		return 'MD5(' . $str . ')';
	}
	
	/**
	 * Calculate the sha1 hash of a string
	 *
	 * @param string $str
	 * @param boolean $raw_output
	 * @return string
	 */
	public function sha1($str, $raw_output = false) {
		return 'SHA1(' . $str . ')';
	}
	
	/**
	 * Calculate the soundex key of a string
	 *
	 * @param string $str
	 * @return string
	 */
	public function soundex($str) {
		return 'SOUNDEX(' . $str . ')';
	}
	
	/**
	 * Quote string with slashes
	 *
	 * @param string $str
	 * @return string
	 */
	public function addslashes($str) {
		return 'QUOTE(' . $str . ')';
	}
	
	/**
	 * Repeat a string
	 *
	 * @param string $input
	 * @param int $multiplier
	 * @return string
	 */
	public function str_repeat($input, $multiplier) {
		return 'REPEAT(' . $input . ', ' . $multiplier . ')';
	}
}