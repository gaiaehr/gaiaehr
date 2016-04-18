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

class Phone
{
	private static $areaCodeSeparator;
	private static $numberSeparator;

	public static function fullPhone($countryCode = null, $areaCode = null, $prefix = null, $number = null)
	{
		self::setAreaCodeSeparator();
		self::setNumberSeparator();

		$PhoneConstruct = '';
        $PhoneConstruct .= self::isValid($countryCode) ? '+' . $countryCode . ' ' : '';

		if(strlen(self::$areaCodeSeparator) == 2){
			$s = str_split(self::$areaCodeSeparator);
            $PhoneConstruct .= self::isValid($areaCode) ? $s[0] . $areaCode . $s[1] . ' ' : '';
		}else{
            $PhoneConstruct .= self::isValid($areaCode) ? $areaCode . self::$areaCodeSeparator : '';
		}

        $PhoneConstruct .= self::isValid($prefix) ? $prefix . self::$numberSeparator : '';
        $PhoneConstruct .= self::isValid($number) ? $number : '';

		return trim($PhoneConstruct);
	}


	private static function isValid($value){
		return !is_null($value) && !empty($value);
	}


	// setters and getters
	public static function setAreaCodeSeparator($separator = '()'){
		self::$areaCodeSeparator = $separator;
	}

	public static function getAreaCodeSeparator(){
		return self::$areaCodeSeparator;
	}
	public static function setNumberSeparator($separator = '-'){
		self::$numberSeparator = $separator;
	}

	public static function getNumberSeparator(){
		return self::$numberSeparator;
	}
}
