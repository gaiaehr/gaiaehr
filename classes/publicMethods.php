<?php
/*
 * publicMethods class
 * The intention of this class is to accomodate all the misc. functions
 * and want to share or repeate during the whole application
 * */
/**
 *
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