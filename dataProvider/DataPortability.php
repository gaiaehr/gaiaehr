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

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
if(!defined('_GaiaEXEC')){
	define('_GaiaEXEC', 1);
	require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
}
if(!isset($_REQUEST['token']) || str_replace(' ', '+', $_REQUEST['token']) != $_SESSION['user']['token']) die('Not Authorized!');

include_once(ROOT . '/sites/' . $_REQUEST['site'] . '/conf.php');
include_once(ROOT . '/classes/MatchaHelper.php');

include_once (ROOT. '/dataProvider/Patient.php');
include_once (ROOT. '/dataProvider/CCDDocument.php');

class DataPortability {

	private $Patient;
	private $CCDDocument;

	function __construct(){
		$this->Patient = new Patient();
		$this->CCDDocument = new CCDDocument();
	}

	function export($params = null){

		$patients = $this->Patient->getPatients($params);
		unset($this->Patient);

		$zip = new ZipArchive();
		$file = 'GaiaEHR-Patients-Export-'. time() .'.zip';
		if($zip->open($file, ZipArchive::CREATE) !== true){
			throw new Exception("cannot open <$file>");
		}
		$zip->addFromString('cda2.xsl', file_get_contents(ROOT . '/lib/CCRCDA/schema/cda2.xsl'));


		foreach($patients as $i => $patient){
			$patient = (object) $patient;
			$this->CCDDocument->setPid($patient->pid);
			$this->CCDDocument->createCCD();
			$this->CCDDocument->setTemplate('toc');
			$this->CCDDocument->createCCD();
			$ccd = $this->CCDDocument->get();

			$zip->addFromString($patient->pid . '-Patient-CDA' . '.xml', $ccd);

			unset($patients[$i], $ccd);
		}
		$zip->close();
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($file));
		header('Content-Disposition: attachment; filename="' . $file . '"');
		readfile($file);
	}
}

$D = new DataPortability();
$D->export();
