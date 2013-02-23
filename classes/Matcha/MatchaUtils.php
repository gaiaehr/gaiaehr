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
}