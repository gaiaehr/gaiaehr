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

class Address
{
	public static function fullAddress($line1 = null, $line2 = null, $city = null, $state = null, $zip = null, $plusFour = null, $country = null)
	{
		$foo = '';
		$foo .= self::isValid($line1) ? $line1 . ' ' : '';
		$foo .= self::isValid($line2) ? $line2 . ' ' : '';
		$foo .= self::isValid($city) ? $city . ', ' : '';
		$foo .= self::isValid($state) ? $state . ' ' : '';
		$foo .= self::isValid($zip) ? $zip : '';
		$foo .= self::isValid($plusFour) ? '-' . $plusFour . ' ' : ' ';
		$foo .= self::isValid($country) ? $country : '';

		return trim($foo);
	}

	private static function isValid($value){
		return !is_null($value) && !empty($value);
	}

}
