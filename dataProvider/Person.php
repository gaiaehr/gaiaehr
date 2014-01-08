<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

class Person
{

	public static function fullname($fname, $mname, $lname)
	{
		if (isset($_SESSION['globals']) && isset($_SESSION['globals']['fullname']))
		{
			switch($_SESSION['globals']['fullname'])
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
	}

}
