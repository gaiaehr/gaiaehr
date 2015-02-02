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

/**
 * This class is for testing,
 * Is an easy way to test and run Ext.Direct methods
 * from the browser console.
 *
 * Class Test
 */
class Test {


	/**
	 * Method with no arguments
	 * @return array
	 */
	public function t1(){
		$msg = 'Hello World!';

		return array('message' => $msg);
	}

	/**
	 * Methods with arguments
	 * @param $params
	 * @return mixed
	 */
	public function t2($params){
		$params->msg = 'Hello World!';

		return $params;
	}
}