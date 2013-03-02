<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
		foreach($array as $key => $val)
        {
			$offset = 0;
			$found  = false;
			foreach($temp_array as $tmp_key => $tmp_val)
            {
				if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
                {
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

	static function recursiveArraySearch($needle, $haystack)
	{
		foreach($haystack as $key=>$value){
			$current_key = $key;
			if($needle === $value || (is_array($value) && self::recursiveArraySearch($needle, $value) !== false)){
				return $current_key;
			}
		}
		return false;
	}
}
