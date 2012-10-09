<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 5/11/12
 * Time: 7:38 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
class Arrays
{

	static function array_slice_assoc($array, $key, $length, $preserve_keys = true)
	{
		$offset = array_search($key, array_keys($array));
		if(is_string($length)) {
			$length = array_search($length, array_keys($array)) - $offset;
		}
		return array_slice($array, $offset, $length, $preserve_keys);
	}

	static function sksort(&$array, $subkey = 'id', $sort_ascending = false)
	{
		$temp_array = array();
		if(count($array)) $temp_array[key($array)] = array_shift($array);
		foreach($array as $key => $val) {
			$offset = 0;
			$found  = false;
			foreach($temp_array as $tmp_key => $tmp_val) {
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
					$temp_array = array_merge((array)array_slice($temp_array, 0, $offset),
						array($key => $val),
						array_slice($temp_array, $offset)
					);
					$found      = true;
				}
				$offset++;
			}
			if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
		}
		if($sort_ascending) {
			$array = array_reverse($temp_array);
		} else $array = $temp_array;
	}

	static function arrayToObject($array)
	{
		if(!is_array($array)) {
			return $array;
		}
		$object = new stdClass();
		if(is_array($array) && count($array) > 0) {
			foreach($array as $name=> $value) {
				$name = strtolower(trim($name));
				if(!empty($name)) {
					$object->$name = self::arrayToObject($value);
				}
			}
			return $object;
		} else {
			return false;
		}
	}
}
