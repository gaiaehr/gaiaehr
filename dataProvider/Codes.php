<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/FileManager.php');
class Codes
{
	private $db;
	private $codeType;
	private $zippedCodes;
	private $codeDir;

	function __construct()
	{
		$this->db   = new dbHelper();
		$this->file = new FileManager();
		return;
	}

	public function updateCodesWithUploadFile($params, $file)
	{
		$this->codeType = $params->codeType;
		if($this->file->moveUploadedFileToTempDir($file)) {
			$this->zippedCodes = $this->file->src;
			return array('success' => true, 'error' => array('zippedCodes' => $this->zippedCodes, 'params' => $params, 'file' => $file));
		} else {
			return array('success' => false, 'error' => 'Could Not Upload File');
		}
	}

	/******************************************************************************************************************/
	/**  Code Database Helper Methods  ********************************************************************************/
	/******************************************************************************************************************/

	/**
	 * This method was originally created by:
	 * -(Mac) Kevin McAloon (OpenEMR) <mcaloon@patienthealthcareanalytics.com>
     * -Rohit Kumar (OpenEMR) <pandit.rohit@netsity.com>
     * -Brady Miller (OpenEMR) <brady@sparmy.com>
	 * And modified by:
	 * -Ernesto J Rodriguez (GaiaEHR) <erodriguez@certun.com>
	 *
	 * @param stdClass $params
	 * @return array
	 */
	public function getCodeFiles(stdClass $params)
	{
		$this->codeType = $params->codeType;
		$mainDir        = $this->getCodeDir();
		$revisions      = array();

		if(is_dir($mainDir)) {
			$files_array = scandir($mainDir);
			array_shift($files_array); // get rid of "."
			array_shift($files_array); // get rid of ".."
			/**
			 * this foreach loop only encounters 1 file for SNOMED, RXNORM and ICD9 but will cycle through all the
			 * the release files for ICD10
			 */
			foreach($files_array as $file) {
				$file = $mainDir . '/' . $file;
				if(is_file($file)) {
					if($this->codeType == 'RXNORM') {
						if(preg_match("/RxNorm_full_([0-9]{8}).zip/", $file, $matches)) {
							/**
							 * Hard code the version RxNorm feed to be Standard
							 * (if add different RxNorm types/versions/lanuages, then can use this)
							 */
							$version      = 'Standard';
							$date_release = substr($matches[1], 4) . '-' . substr($matches[1], 0, 2) . '-' . substr($matches[1], 2, -4);
							$temp_date    = array(
								'date'=> $date_release,
								'version'=> $version,
								'path'=> $this->file->tempDir . $matches[0],
								'basename' => basename($file));
							array_push($revisions, $temp_date);
						}
					}elseif($this->codeType == 'SNOMED') {
						if(preg_match("/SnomedCT_INT_([0-9]{8}).zip/", $file, $matches)) {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$version      = 'International:English';
							$date_release = substr($matches[1], 0, 4) . '-' . substr($matches[1], 4, -2) . '-' . substr($matches[1], 6);
							$temp_date    = array(
								'date'=> $date_release,
								'version'=> $version,
								'path'=> $this->file->tempDir . $matches[0],
								'basename' => basename($file));
							array_push($revisions, $temp_date);
						}elseif(preg_match("/SnomedCT_Release_INT_([0-9]{8}).zip/", $file, $matches)) {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$version      = 'International:English';
							$date_release = substr($matches[1], 0, 4) . "-" . substr($matches[1], 4, -2) . "-" . substr($matches[1], 6);
							$temp_date    = array(
								'date'=> $date_release,
								'version'=> $version,
								'path'=> $this->file->tempDir . $matches[0],
								'basename' => basename($file));
							array_push($revisions, $temp_date);
						}
					}elseif($this->codeType == 'ICD9'){
						if(preg_match("/cmsv([0-9]+)_master_descriptions.zip/", $file, $matches)) {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$temp_date    = array(
								'date'=> '0000-00-00',
								'version'=> $matches[1],
								'path'=> $this->file->tempDir . $matches[0],
								'basename' => basename($file));
							array_push($revisions, $temp_date);
						}
					}elseif($this->codeType == 'ICD10') {
						if(
							preg_match("/([0-9]{4})_PCS_long_and_abbreviated_titles.zip/", $file, $matches) ||
							preg_match("/DiagnosisGEMs_([0-9]{4}).zip/", $file, $matches) ||
							preg_match("/ICD10OrderFiles_([0-9]{4}).zip/", $file, $matches) ||
							preg_match("/ProcedureGEMs_([0-9]{4}).zip/", $file, $matches) ||
							preg_match("/ReimbursementMapping_([0-9]{4}).zip/", $file, $matches)
						){
							$temp_date = array(
								'date'     => $matches[1].'-01-01',
								'version'  => $matches[1],
								'path'     => $this->file->tempDir . $matches[0],
								'basename' => basename($file));
							array_push($revisions, $temp_date);
						}
					}
				}
			}

			return $revisions;

		} else {
			return array('success' => false, 'error' => $mainDir . 'directory not found');
		}

	}

	function snomed_import()
	{
		// set up array
		$table_array_for_snomed = array(
			"sct_concepts_drop"          => "DROP TABLE IF EXISTS `sct_concepts`",
			"sct_concepts_structure"     => "CREATE TABLE IF NOT EXISTS `sct_concepts` (
	            `ConceptId` bigint(20) NOT NULL,
	            `ConceptStatus` int(11) NOT NULL,
	            `FullySpecifiedName` varchar(255) NOT NULL,
	            `CTV3ID` varchar(5) NOT NULL,
	            `SNOMEDID` varchar(8) NOT NULL,
	            `IsPrimitive` tinyint(1) NOT NULL,
	            PRIMARY KEY (`ConceptId`)
	            ) ENGINE=MyISAM",
			"sct_descriptions_drop"      => "DROP TABLE IF EXISTS `sct_descriptions`",
			"sct_descriptions_structure" => "CREATE TABLE IF NOT EXISTS `sct_descriptions` (
	            `DescriptionId` bigint(20) NOT NULL,
	            `DescriptionStatus` int(11) NOT NULL,
	            `ConceptId` bigint(20) NOT NULL,
	            `Term` varchar(255) NOT NULL,
	            `InitialCapitalStatus` tinyint(1) NOT NULL,
	            `DescriptionType` int(11) NOT NULL,
	            `LanguageCode` varchar(8) NOT NULL,
	            PRIMARY KEY (`DescriptionId`)
	            ) ENGINE=MyISAM",
			"sct_relationships_drop"     => "DROP TABLE IF EXISTS `sct_relationships`",
			"sct_relationships_structure"=> "CREATE TABLE IF NOT EXISTS `sct_relationships` (
	            `RelationshipId` bigint(20) NOT NULL,
	            `ConceptId1` bigint(20) NOT NULL,
	            `RelationshipType` bigint(20) NOT NULL,
	            `ConceptId2` bigint(20) NOT NULL,
	            `CharacteristicType` int(11) NOT NULL,
	            `Refinability` int(11) NOT NULL,
	            `RelationshipGroup` int(11) NOT NULL,
	            PRIMARY KEY (`RelationshipId`)
	            ) ENGINE=MyISAM"
		);
		// set up paths
		$dir_snomed = $this->file->tempDir;
		$sub_path = 'Terminology/Content/';
		$dir      = $dir_snomed;
		$dir      = str_replace('\\', '/', $dir);
		// executing the create statement for tables, these are defined in snomed_capture.inc file
		foreach($table_array_for_snomed as $val) {
			if(trim($val) != '') {
				print '<br>';
				print $val;
				//sqlStatement($val);
			}
		}
		// reading the SNOMED directory and identifying the files to import and replacing the variables by originals values.
		if(is_dir($dir) && $handle = opendir($dir)) {
			while(false !== ($filename = readdir($handle))) {
				if($filename != "." && $filename != ".." && !strpos($filename, "zip")) {
					$path = $dir . "" . $filename . "/" . $sub_path;
					if(!(is_dir($path))) {
						$path = $dir . "" . $filename . "/RF1Release/" . $sub_path;
					}
					if(is_dir($path) && $handle1 = opendir($path)) {
						while(false !== ($filename1 = readdir($handle1))) {
							$load_script   = "Load data local infile '#FILENAME#' into table #TABLE# fields terminated by '\\t' ESCAPED BY '' lines terminated by '\\n' ignore 1 lines   ";
							$array_replace = array("#FILENAME#", "#TABLE#");
							if($filename1 != "." && $filename1 != "..") {
								$file_replace = $path . $filename1;
								if(strpos($filename1, "Concepts") !== false) {
									$new_str = str_replace($array_replace, array($file_replace, "sct_concepts"), $load_script);
								}
								if(strpos($filename1, "Descriptions") !== false) {
									$new_str = str_replace($array_replace, array($file_replace, "sct_descriptions"), $load_script);
								}
								if(strpos($filename1, "Relationships") !== false) {
									$new_str = str_replace($array_replace, array($file_replace, "sct_relationships"), $load_script);
								}
								if($new_str != '') {
									//sqlStatement($new_str);
								}
							}
						}
					}
					closedir($handle1);
				}
			}
			closedir($handle);
		}
		return true;
	}











	private function getCodeDir()
	{
		return $_SESSION['site']['root'] . '/contrib/' . strtolower($this->codeType);
	}

	private function getCurrentCodeVersion()
	{
		$this->codeType;
		return;
	}

	private function getNewCodeVersion()
	{
		$this->codeType;
		return;
	}

}

//$f = new Codes();
//print '<pre>';
//$params = new stdClass();
//$params->codeType = 'ICD10';
//print_r($f->getCodeFiles($params));




