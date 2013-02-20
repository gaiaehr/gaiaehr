<?php

 /**
  * Matcha::connect (Main Class)
  * MatchaInject.php
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
 
class MatchaInjext extends Matcha
{
	static public function __InjectSQLForked($sqlStatement)
	{
		try
		{
			switch ($pid = pcntl_fork()) {
				case -1: // fork failed
					throw new Exception('Fork failed');
					break;
		
				case 0: // fork success 
					//.. run the injection
					break;
		
				default:
					pcntl_waitpid($pid, $status);
					break;
		   }
		}
		catch(Exception $e)
		{
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}	
}

 