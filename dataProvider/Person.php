<?php
/*
 GaiaEHR (Electronic Health Records)
 Person.php
 Person dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

class Person
{

	/**
	 * @param $fname
	 * @param $mname
	 * @param $lname
	 * @return string
	 */
	public static function fullname($fname, $mname, $lname)
	{
		if ($_SESSION['global_settings'] && $_SESSION['global_settings']['fullname'])
		{
			switch($_SESSION['global_settings']['fullname'])
			{
				case '0' :
					$fullname = $lname . ', ' . $fname . ' ' . $mname;
					break;
				case '1' :
					$fullname = $fname . ' ' . $mname . ' ' . $lname;
					break;
				default :
					$fullname = $fname . ' ' . $mname . ' ' . $lname;
					break;
			}
		}
		else
		{
			$fullname = $lname . ', ' . $fname . ' ' . $mname;
		}
		$fullname = ($fullname == ',  ') ? '' : $fullname;

		return $fullname;
	}

	public static function fulladdress($street, $streetb = null, $city, $state, $zip)
	{

		if ($street != NULL || $street != "")
		{
			$street = $street . "<br>";
		}
		else
		{
			$street = $street;
		}

		if ($streetb != NULL || $streetb != "")
		{
			$streetb = $streetb . "<br>";
		}
		else
		{
			$streetb = $streetb;
		}

		if ($city != NULL || $city != "")
		{
			$city = $city . ", ";
		}
		else
		{
			$city = $city;
		}

		return $street . $streetb . $city . ' ' . $state . ' ' . $zip;

	}

	public static function ellipsis($text, $max = 100, $append = '&hellip;')
	{
		if (strlen($text) <= $max)
			return $text;
		$out = substr($text, 0, $max);
		return $out . $append;
		//return preg_replace('/\w+$/','',$out).$append;
	}

}
