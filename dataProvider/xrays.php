<?php
/*
 GaiaEHR (Electronic Health Records)
 Medications.php
 Medications dataProvider
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
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 8/19/12
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

class Xrays
{
	private $db;

	function __construct()
	{
		$this -> db = new dbHelper();
		return;
	}


//todo:The Live Search of xrays codes in theory should be the same of the cpt codes but with a certain list of codes only
//todo:Have to check how to do the live search after filtering the codes
//	public function getXraysLiveSearch(stdClass $params)
//	{
////		$this -> db -> setSQL("SELECT * FROM cvx_codes
////							WHERE cvx_code 	  LIKE '$params->query%'
////							   OR `name` 	  LIKE '$params->query%'
////							   OR description LIKE '$params->query%'");
////		$records = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
////		$total = count($records);
////		$records = array_slice($records, $params -> start, $params -> limit);
////		return array(
////			'totals' => $total,
////			'rows' => $records
////		);
//	}
//todo:fix this query (working but low performance)
	public function getXrays()
	{
		$this -> db -> setSQL("SELECT *
								 FROM cpt_codes
								WHERE code BETWEEN 19000 AND 19000
								   OR code BETWEEN 19102 AND 19102
								   OR code BETWEEN 32000 AND 32000
								   OR code BETWEEN 38792 AND 38792
								   OR code BETWEEN 70110 AND 70260
								   OR code BETWEEN 70450 AND 70492
								   OR code BETWEEN 70540 AND 70551
								   OR code BETWEEN 71010 AND 71111
								   OR code BETWEEN 72010 AND 72265
								   OR code BETWEEN 73000 AND 73140
								   OR code BETWEEN 73200 AND 73723
								   OR code BETWEEN 74000 AND 74022
								   OR code BETWEEN 74150 AND 74185
								   OR code BETWEEN 74220 AND 74455
								   OR code BETWEEN 76003 AND 76020
								   OR code BETWEEN 76080 AND 76096
								   OR code BETWEEN 76360 AND 76360
								   OR code BETWEEN 76700 AND 76881
								   OR code BETWEEN 76942 AND 76942
								   OR code BETWEEN 77055 AND 77057
								   OR code BETWEEN 78195 AND 78195
								   OR code BETWEEN 78215 AND 78290
								   OR code BETWEEN 78300 AND 78315
								   OR code BETWEEN 78464 AND 78480
								   OR code BETWEEN 78580 AND 78806
								   OR code BETWEEN 93015 AND 93018
								   OR code BETWEEN 93307 AND 93350
								   OR code BETWEEN 93925 AND 93931");

		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
	}

}

$e = new Xrays();
echo '<pre>';
	$here=$e->getXrays();
print_r($here);
