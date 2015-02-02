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

class publicMethods
{

	/**
	 * @var
	 */
	private $OperatingSystem;

	/**
	 *
	 */
	function __construct()
	{

	}

	/**
	 * @return string
	 */
	public function detectOS()
	{
		switch (strtolower(PHP_OS))
		{
			case "winnt" :
				return "WIN";
				break;
			case "Windows" :
				return "WIN";
				break;
			case "WIN32" :
				return "WIN";
				break;
			case "Unix" :
				return "UNIX";
				break;
			case "OpenBSD" :
				return "UNIX";
				break;
			case "SunOS" :
				return "UNIX";
				break;
			case "NetBSD" :
				return "UNIX";
				break;
			case "Linux" :
				return "UNIX";
				break;
			case "IRIX64" :
				return "UNIX";
				break;
			case "HP-UX" :
				return "UNIX";
				break;
			case "FreeBSD" :
				return "UNIX";
				break;
			case "Darwin" :
				return "UNIX";
				break;
			case "CYGWIN_NT-5.1" :
				return "UNIX";
				break;
		}
	}

	/**
	 * @param string $directory
	 * @return string
	 */
	public function parsePath(string $directory)
	{

		if ($this -> detectOS() == "WIN")
		{
			return (string)str_replace($directory, "/", "\"");
		}
		else
		{
			return (string)$directory;
		}
	}

}
?>