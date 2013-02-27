<?php
/**
 * Matcha::connect
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

class MatchaUtils
{
    /**
     * function t($times = NULL):
     * Method to product TAB characters
     * @param null $times
     * @return string
     */
    static public function t($times = NULL)
    {
        $tabs = '';
        for ($i = 1; $i <= $times; $i++) $tabs .= chr(9);
        return $tabs;
    }

    /**
     * function __recursiveArraySearch($needle,$haystack):
     * An recursive array search method
     */
    static public function __recursiveArraySearch($needle,$haystack)
    {
        foreach($haystack as $key=>$value)
        {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && MatchaUtils::__recursiveArraySearch($needle,$value) !== false)) return $current_key;
        }
        return false;
    }

	static public function __objectToArray($obj) {
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? self::__objectToArray($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}

	/**
	 * convert Array to Object recursively
	 * @param array $array
	 * @param stdClass $parent
	 * @return stdClass
	 */
	static public function __arrayToObject(array $array, stdClass $parent = NULL)
	{
		if ($parent === null) $parent = new stdClass;
		foreach ($array as $key => $val){
			if (is_array($val)){
				$parent->$key = self::__arrayToObject($val, new stdClass);
			}else{
				$parent->$key = $val;
			}
		}
		return $parent;
	}
}