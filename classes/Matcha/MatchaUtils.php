<?php

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
            if($needle===$value OR (is_array($value) && self::__recursiveArraySearch($needle,$value) !== false)) return $current_key;
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