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
 
class MatchaThreads extends Thread
{
	
	public $sqlStatement = '';
	
	/**
	 * function injectSQLThread($sqlStatement):
	 * Method to send BIG SQL injections to the database
	 * think of it throw and forget injection
	 */
	public function run()
	{
		try
		{
			Matcha::$__conn->query($this->sqlStatement);
		}
		catch(PDOException $e)
		{
			MatchaErrorHandler::__errorProcess($e);
			return false;
		}
	}

}
 