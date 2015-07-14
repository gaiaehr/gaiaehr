<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

class Arrays {

	private static $xml = null;
	private static $encoding = 'UTF-8';

	static function array_slice_assoc($array, $key, $length, $preserve_keys = true) {
		$offset = array_search($key, array_keys($array));
		if(is_string($length)){
			$length = array_search($length, array_keys($array)) - $offset;
		}
		return array_slice($array, $offset, $length, $preserve_keys);
	}

	static function sksort(&$array, $subkey = 'id', $sort_ascending = false) {
		$temp_array = array();
		if(count($array))
			$temp_array[key($array)] = array_shift($array);
		foreach($array as $key => $val){
			$offset = 0;
			$found = false;
			foreach($temp_array as $tmp_key => $tmp_val){
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])){
					$temp_array = array_merge((array)array_slice($temp_array, 0, $offset), array($key => $val), array_slice($temp_array, $offset));
					$found = true;
				}
				$offset++;
			}
			if(!$found)
				$temp_array = array_merge($temp_array, array($key => $val));
		}
		if($sort_ascending){
			$array = array_reverse($temp_array);
		} else $array = $temp_array;
	}

	static function sortmulti($array, $index, $order, $natsort = false, $case_sensitive = false) {

		if(is_array($array) && count($array) > 0){

			foreach(array_keys($array) as $key){
				$temp[$key] = $array[$key][$index];
			}

			if(!$natsort){
				if($order == 'asc'){
					asort($temp);
				}else{
					arsort($temp);
				}
			} else {

				if($case_sensitive === true){
					natsort($temp);
				} else{
					natcasesort($temp);
				}

				if($order != 'asc'){
					$temp = array_reverse($temp, true);
				}
			}
			$sorted = array();
			foreach(array_keys($temp) as $key){
				if(is_numeric($key)){
					$sorted[] = $array[$key];
				}else{
					$sorted[$key] = $array[$key];
				}
			}
			return $sorted;
		}

		return $array;
	}

	static function arrayToObject($array) {
		if(!is_array($array)){
			return $array;
		}
		$object = new stdClass();
		if(is_array($array) && count($array) > 0){
			foreach($array as $name => $value){
				$name = strtolower(trim($name));
				if(!empty($name)){
					$object->$name = self::arrayToObject($value);
				}
			}
			return $object;
		} else {
			return false;
		}
	}

	static function recursiveArraySearch($needle, $haystack) {
		foreach($haystack as $key => $value){
			$current_key = $key;
			if($needle === $value || (is_array($value) && self::recursiveArraySearch($needle, $value) !== false)){
				return $current_key;
			}
		}
		return false;
	}

	/**
	 * Initialize the root XML node [optional]
	 * @param $version
	 * @param $encoding
	 * @param $format_output
	 */
	public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
		self::$xml = new DomDocument($version, $encoding);
		self::$xml->formatOutput = $format_output;
		self::$encoding = $encoding;
	}

	/**
	 * Convert an Array to XML
	 * @param string $node_name - name of the root node to be converted
	 * @param array $arr - aray to be converterd
	 * @return DomDocument
	 */
	public static function &createXML($node_name, $arr = array()) {
		$xml = self::getXMLRoot();
		$xml->appendChild(self::convert($node_name, $arr));

		self::$xml = null; // clear the xml node in the class for 2nd time use.
		return $xml;
	}

	/**
	 * Convert an Array to XML
	 * @param string $node_name - name of the root node to be converted
	 * @param array $arr - array to be converted
	 * @throws Exception
	 * @return DOMNode
	 */
	private static function &convert($node_name, $arr = array()) {

		//print_arr($node_name);
		$xml = self::getXMLRoot();
		$node = $xml->createElement($node_name);

		if(is_array($arr)){
			// get the attributes first.;
			if(isset($arr['@attributes'])){
				foreach($arr['@attributes'] as $key => $value){
					if(!self::isValidTagName($key)){
						throw new Exception('[Array2XML] Illegal character in attribute name. attribute: ' . $key . ' in node: ' . $node_name);
					}
					$node->setAttribute($key, self::bool2str($value));
				}
				unset($arr['@attributes']); //remove the key from the array once done.
			}

			// check if it has a value stored in @value, if yes store the value and return
			// else check if its directly stored as string
			if(isset($arr['@value'])){
				$node->appendChild($xml->createTextNode(self::bool2str($arr['@value'])));
				unset($arr['@value']); //remove the key from the array once done.
				//return from recursion, as a note with value cannot have child nodes.
				return $node;
			} else if(isset($arr['@cdata'])){
				$node->appendChild($xml->createCDATASection(self::bool2str($arr['@cdata'])));
				unset($arr['@cdata']); //remove the key from the array once done.
				//return from recursion, as a note with cdata cannot have child nodes.
				return $node;
			}
		}

		//create subnodes using recursion
		if(is_array($arr)){
			// recurse to get the node for that key
			foreach($arr as $key => $value){
				if(!self::isValidTagName($key)){
					throw new Exception('[Array2XML] Illegal character in tag name. tag: ' . $key . ' in node: ' . $node_name);
				}
				if(is_array($value) && is_numeric(key($value))){
					// MORE THAN ONE NODE OF ITS KIND;
					// if the new array is numeric index, means it is array of nodes of the same kind
					// it should follow the parent key name
					foreach($value as $k => $v){
						$node->appendChild(self::convert($key, $v));
					}
				} else {
					// ONLY ONE NODE OF ITS KIND
					$node->appendChild(self::convert($key, $value));
				}
				unset($arr[$key]); //remove the key from the array once done.
			}
		}

		// after we are done with all the keys in the array (if it is one)
		// we check if it has any text value, if yes, append it.
		if(!is_array($arr)){
			$node->appendChild($xml->createTextNode(self::bool2str($arr)));
		}

		return $node;
	}

	/**
	 * Get the root XML node, if there isn't one, create it.
	 * @return DomDocument
	 */
	private static function getXMLRoot() {
		if(empty(self::$xml)){
			self::init();
		}
		return self::$xml;
	}

	/*
	 * Get string representation of boolean value
	 */
	private static function bool2str($v) {
		//convert boolean to text value.
		$v = $v === true ? 'true' : $v;
		$v = $v === false ? 'false' : $v;
		return $v;
	}

	/*
	 * Check if the tag name or attribute name contains illegal characters
	 * Ref: http://www.w3.org/TR/xml/#sec-common-syn
	 */
	private static function isValidTagName($tag) {
		$pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
		return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
	}

}
