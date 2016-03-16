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

include_once(ROOT . '/classes/Time.php');
include_once(ROOT . '/classes/FileManager.php');

class ExternalDataUpdate
{
	private $db;
	private $codeType;
	private $installedRevision;
	private $error = false;

	function __construct()
    {
        // Set the time limit to infinite, this will take a lot of time.
        // Maybe hours on a magnetic hard disk, maybe 1 hour to minutes in a SSD (Solid State Disk)
        set_time_limit(0);

        // Now, let's the party begin
        $this->db = new MatchaHelper();
		$this->file = new FileManager();
		return;
	}

	/**
	 * This method was originally created by:
	 * -(Mac) Kevin McAloon (OpenEMR) <mcaloon@patienthealthcareanalytics.com>
	 * -Rohit Kumar (OpenEMR) <pandit.rohit@netsity.com>
	 * -Brady Miller (OpenEMR) <brady@sparmy.com>
	 * from OpenEMR Project <http://www.open-emr.org/> And modified by:
	 * -Ernesto J Rodriguez (GaiaEHR) <erodriguez@certun.com>
	 *
	 * @param stdClass $params
	 * @return array
	 */
	public function getCodeFiles(stdClass $params)
    {
		$this->codeType = $params->codeType;
		$mainDir = $this->getCodeDir();
		$revisions = [];
		if(is_dir($mainDir))
        {
			$files_array = $this->file->scanDir($mainDir);
			/**
			 * this foreach loop only encounters 1 file for SNOMED, RXNORM and ICD9 but will
			 * cycle through all the
			 * the release files for ICD10
			 */
			foreach($files_array as $file)
            {
				$file = $mainDir . '/' . $file;
				if(is_file($file))
                {
					if($this->codeType == 'RXNORM')
                    {
						if(preg_match("/RxNorm_full_([0-9]{8}).zip/", $file, $matches))
                        {
							/**
							 * Hard code the version RxNorm feed to be Standard
							 * (if add different RxNorm types/versions/lanuages, then can use this)
							 */
							$version = 'Standard';
							$date_release = substr($matches[1], 4) . '-' . substr($matches[1], 0, 2) . '-' . substr($matches[1], 2, -4);
							$temp_date = [
								'date' => $date_release,
								'version' => $version,
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
					}
                    elseif($this->codeType == 'SNOMED')
                    {
						if(preg_match("/SnomedCT_RF1Release_INT_([0-9]{8}).zip/", $file, $matches))
                        {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$version = 'International:English';
							$date_release = substr($matches[1], 0, 4) . '-' . substr($matches[1], 4, -2) . '-' . substr($matches[1], 6);
							$temp_date = [
								'date' => $date_release,
								'version' => $version,
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
                        elseif(preg_match("/SnomedCT_RF1Release_US([0-9]{7})_([0-9]{8}).zip/", $file, $matches))
                        {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$version = 'US:English';
							$date_release = substr($matches[2], 0, 4) . '-' . substr($matches[2], 4, -2) . '-' . substr($matches[2], 6);
							$temp_date = [
								'date' => $date_release,
								'version' => $version,
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
					}
                    elseif($this->codeType == 'ICD9')
                    {
						if(preg_match("/cmsv([0-9]+)_master_descriptions.zip/", $file, $matches))
                        {
							/**
							 * Hard code the version SNOMED feed to be International:English
							 * (if add different SNOMED types/versions/languages, then can use this)
							 */
							$temp_date = [
								'date' => '0000-00-00',
								'version' => $matches[1],
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
					}
                    elseif($this->codeType == 'ICD10')
                    {
						if(preg_match("/([0-9]{4})_PCS_long_and_abbreviated_titles.zip/", $file, $matches) || preg_match("/DiagnosisGEMs_([0-9]{4}).zip/", $file, $matches) || preg_match("/ICD10OrderFiles_([0-9]{4}).zip/", $file, $matches) || preg_match("/ProcedureGEMs_([0-9]{4}).zip/", $file, $matches) || preg_match("/ReimbursementMapping_([0-9]{4}).zip/", $file, $matches))
                        {
							$temp_date = [
								'date' => $matches[1] . '-01-01',
								'version' => $matches[1],
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
					}
                    elseif($this->codeType == 'HCPCS')
                    {
						if(preg_match("/([0-9]{2})anweb.zip/", $file, $matches)){
							$temp_date = [
								'date' => '20' . $matches[1] . '-01-01',
								'version' => '20' . $matches[1],
								'path' => $file,
								'basename' => basename($file),
								'codeType' => $this->codeType
							];
							array_push($revisions, $temp_date);
						}
					}
				}
			}
			return $revisions;

		}
        else
        {
			return [
				'success' => false,
				'error' => $mainDir . 'directory not found'
			];
		}

	}

	public function updateCodes(stdClass $params)
    {
		$this->codeType = $params->codeType;
		$dir = false;
		if((($params->codeType == 'ICD9' || $params->codeType == 'ICD10') && $this->isCodeVersionNewer($params->version)) || ($params->codeType != 'ICD9' && $params->codeType != 'ICD10')){
			// handle ICD10 request to loop for avery file
			if($params->codeType == 'ICD10'){
				$idc10Dir = $this->getCodeDir();
				if(is_dir($idc10Dir))
                {
					if($this->file->setWorkingDir())
                    {
						$files = $this->file->scanDir($idc10Dir);
						$filesFound = 0;
						foreach($files as $file)
                        {
							if($file == $params->version . '_PCS_long_and_abbreviated_titles.zip' || $file == 'DiagnosisGEMs_' . $params->version . '.zip' || $file == 'ICD10OrderFiles_' . $params->version . '.zip' || $file == 'ProcedureGEMs_' . $params->version . '.zip' || $file == 'ReimbursementMapping_' . $params->version . '.zip')
                            {
								$filesFound++;
								$dir = $this->file->extractFileToDir($idc10Dir . '/' . $file, $this->file->workingDir);
								if($dir === false) $this->error = $this->file->error;
							}
						}
						if($filesFound == 0) $this->error = 'Could not find version ' . $params->version . ' files';
					}
                    else
                    {
						$this->error = $this->file->error;
					}
				}
                else
                {
					$this->error = 'Could not find ICD10 directory';
				}

				// handle the ICD9, RXNORM, and SNOMED requests
			}
            else
            {
				$dir = $this->file->extractFileToTempDir($params->path);
				if($dir === false) $this->error = $this->file->error;
			}

			if($this->error === false)
            {
				if($dir != false)
                {
					$this->file->chmodReclusive($dir, 0777);
					$success = false;
					$name = $params->codeType;
					if($params->codeType == 'ICD9' || $params->codeType == 'ICD10')
                    {
						$name = 'CMS';
						$success = $this->icd_import($dir, $params->codeType);
					}
                    elseif($params->codeType == 'RXNORM')
                    {
						$success = $this->rxnorm_import($dir);
					}
                    elseif($params->codeType == 'SNOMED')
                    {
						$success = $this->snomed_import($dir);
					}
                    elseif($params->codeType == 'SNOMEDCoreProblem')
                    {
						$success = $this->snomed_problems($dir);
					}
                    elseif($params->codeType == 'HCPCS')
                    {
						$success = $this->hcpcs_import($dir);
					}
					$this->file->cleanUp();
					if($success !== false)
                    {
						$params->date = isset($params->date) ? $params->date : date('Y-m-d');
						$this->updateTrackerTable($name, $params->codeType, $this->installedRevision, $params->version, $params->date);
						return [
							'success' => $success,
							'dir' => $dir,
							'params' => $params
						];
					}
                    else
                    {
						return [
							'success' => $success,
							'error' => $this->error
						];
					}
				} else {
					return [
						'success' => false,
						'error' => $this->file->error
					];
				}
			}
            else
            {
				return [
					'success' => false,
					'error' => $this->error
				];
			}
		} else {
			return [
				'success' => false,
				'error' => $this->error
			];
		}
	}

	public function hcpcs_import($dir)
    {
		$dir = str_replace('\\', '/', $dir . '/');
		$fields = [
			[
				'name' => 'HCPCS_CD',
				'pos' => 1,
				'len' => 5
			],
			[
				'name' => 'HCPCS_LONG_DESC_TXT',
				'pos' => 12,
				'len' => 80,
			],
			[
				'name' => 'HCPCS_SHRT_DESC_TXT',
				'pos' => 92,
				'len' => 28,
			],
			[
				'name' => 'HCPCS_PRCNG_IND_CD',
				'pos' => 120,
				'len' => 2,
			],
			[
				'name' => 'HCPCS_MLTPL_PRCNG_IND_CD',
				'pos' => 128,
				'len' => 1,
			],
			[
				'name' => 'HCPCS_CIM_RFRNC_SECT_NUM',
				'pos' => 129,
				'len' => 6,
			],
			[
				'name' => 'HCPCS_MCM_RFRNC_SECT_NUM',
				'pos' => 147,
				'len' => 8,
			],
			[
				'name' => 'HCPCS_STATUTE_NUM',
				'pos' => 171,
				'len' => 10,
			],
			[
				'name' => 'HCPCS_LAB_CRTFCTN_CD',
				'pos' => 181,
				'len' => 3,
			],
			[
				'name' => 'HCPCS_XREF_CD',
				'pos' => 205,
				'len' => 5,
			],
			[
				'name' => 'HCPCS_CVRG_CD',
				'pos' => 230,
				'len' => 1,
			],
			[
				'name' => 'HCPCS_ASC_PMT_GRP_CD',
				'pos' => 231,
				'len' => 2,
			],
			[
				'name' => 'HCPCS_ASC_PMT_GRP_EFCTV_DT',
				'pos' => 233,
				'len' => 8,
			],
			[
				'name' => 'HCPCS_MOG_PMT_GRP_CD',
				'pos' => 241,
				'len' => 3,
			],
			[
				'name' => 'HCPCS_MOG_PMT_PLCY_IND_CD',
				'pos' => 244,
				'len' => 1,
			],
			[
				'name' => 'HCPCS_MOG_PMT_GRP_EFCTV_DT',
				'pos' => 245,
				'len' => 8,
			],
			[
				'name' => 'HCPCS_PRCSG_NOTE_NUM',
				'pos' => 253,
				'len' => 4,
			],
			[
				'name' => 'HCPCS_BETOS_CD',
				'pos' => 257,
				'len' => 3,
			],
			[
				'name' => 'HCPCS_TYPE_SRVC_CD',
				'pos' => 261,
				'len' => 1,
			],
			[
				'name' => 'HCPCS_ANSTHSA_BASE_UNIT_QTY',
				'pos' => 266,
				'len' => 3
			],
			[
				'name' => 'HCPCS_CD_ADD_DT',
				'pos' => 269,
				'len' => 8
			],
			[
				'name' => 'HCPCS_ACTN_EFCTV_DT',
				'pos' => 277,
				'len' => 8
			],
			[
				'name' => 'HCPCS_TRMNTN_DT',
				'pos' => 285,
				'len' => 8
			],
			[
				'name' => 'HCPCS_ACTN_CD',
				'pos' => 293,
				'len' => 1
			]
		];
		if(is_dir($dir) && $handle = opendir($dir))
        {
			while(false !== ($filename = readdir($handle)))
            {
				if(preg_match("/HCPC([0-9]{4})_A-N.txt/", $filename))
                {
					$file_handle = fopen($dir . $filename, "r");
					$buff = [];
					$count = 0;
					$this->db->setSQL('TRUNCATE TABLE `hcpcs_codes`');
					$this->db->execOnly();
					while(!feof($file_handle))
                    {
						$line = fgets($file_handle);
						$foo = [];
						foreach($fields As $field)
                        {
							$foo[$field['name']] = trim(substr($line, $field['pos'] - 1, $field['len']));
						}
						if($count == 0)
                        {
							$buff = $foo;
						}
                        elseif($buff['HCPCS_CD'] != $foo['HCPCS_CD'])
                        {
							$this->db->setSQL($this->db->sqlBind($buff, 'hcpcs_codes', 'I'));
							$this->db->execOnly();
							$buff = $foo;
						}
                        else
                        {
							$buff['HCPCS_LONG_DESC_TXT'] = $buff['HCPCS_LONG_DESC_TXT'] . ' ' . $foo['HCPCS_LONG_DESC_TXT'];
						}
						$count++;
					}
					fclose($file_handle);
				}
			}
			return true;
		}
        else
        {
			$this->error = 'Unable to open ' . $dir;
			return false;
		}

	}

	public function icd_import($dir, $type)
    {
		$dir = str_replace('\\', '/', $dir . '/');
		// the incoming array is a metadata array containing keys that substr match to
		// the incoming filename
		// followed by the field name, position and length of each fixed length text
		// record in the incoming
		// flat files. There are separate definitions for ICD 9 and 10 based on the type
		// passed in
		$incoming = [];
		if($type == 'ICD9')
        {
			$incoming['SHORT_DX'] = [
				'#TABLENAME#' => 'icd9_dx_code',
				'#FLD1#' => 'dx_code',
				'#POS1#' => 1,
				'#LEN1#' => 5,
				'#FLD2#' => 'short_desc',
				'#POS2#' => 7,
				'#LEN2#' => 60
			];
			$incoming['SHORT_SG'] = [
				'#TABLENAME#' => 'icd9_sg_code',
				'#FLD1#' => 'sg_code',
				'#POS1#' => 1,
				'#LEN1#' => 4,
				'#FLD2#' => 'short_desc',
				'#POS2#' => 6,
				'#LEN2#' => 60
			];
			$incoming['LONG_SG'] = [
				'#TABLENAME#' => 'icd9_sg_long_code',
				'#FLD1#' => 'sg_code',
				'#POS1#' => 1,
				'#LEN1#' => 4,
				'#FLD2#' => 'long_desc',
				'#POS2#' => 6,
				'#LEN2#' => 300
			];
			$incoming['LONG_DX'] = [
				'#TABLENAME#' => 'icd9_dx_long_code',
				'#FLD1#' => 'dx_code',
				'#POS1#' => 1,
				'#LEN1#' => 5,
				'#FLD2#' => 'long_desc',
				'#POS2#' => 7,
				'#LEN2#' => 300
			];
		}
        else
        {
			$incoming['icd10pcs_order_'] = [
				'#TABLENAME#' => 'icd10_pcs_order_code',
				'#FLD1#' => 'pcs_code',
				'#POS1#' => 7,
				'#LEN1#' => 7,
				'#FLD2#' => 'valid_for_coding',
				'#POS2#' => 15,
				'#LEN2#' => 1,
				'#FLD3#' => 'short_desc',
				'#POS3#' => 17,
				'#LEN3#' => 60,
				'#FLD4#' => 'long_desc',
				'#POS4#' => 78,
				'#LEN4#' => 300
			];
			$incoming['icd10cm_order_'] = [
				'#TABLENAME#' => 'icd10_dx_order_code',
				'#FLD1#' => 'dx_code',
				'#POS1#' => 7,
				'#LEN1#' => 7,
				'#FLD2#' => 'valid_for_coding',
				'#POS2#' => 15,
				'#LEN2#' => 1,
				'#FLD3#' => 'short_desc',
				'#POS3#' => 17,
				'#LEN3#' => 60,
				'#FLD4#' => 'long_desc',
				'#POS4#' => 78,
				'#LEN4#' => 300
			];
			$incoming['reimb_map_pr_'] = [
				'#TABLENAME#' => 'icd10_reimbr_pcs_9_10',
				'#FLD1#' => 'code',
				'#POS1#' => 1,
				'#LEN1#' => 7,
				'#FLD2#' => 'code_cnt',
				'#POS2#' => 9,
				'#LEN2#' => 1,
				'#FLD3#' => 'ICD9_01',
				'#POS3#' => 11,
				'#LEN3#' => 5,
				'#FLD4#' => 'ICD9_02',
				'#POS4#' => 17,
				'#LEN4#' => 5,
				'#FLD5#' => 'ICD9_03',
				'#POS5#' => 23,
				'#LEN5#' => 5,
				'#FLD6#' => 'ICD9_04',
				'#POS6#' => 29,
				'#LEN6#' => 5,
				'#FLD7#' => 'ICD9_05',
				'#POS7#' => 35,
				'#LEN7#' => 5,
				'#FLD8#' => 'ICD9_06',
				'#POS8#' => 41,
				'#LEN8#' => 5
			];
			$incoming['reimb_map_dx_'] = [
				'#TABLENAME#' => 'icd10_reimbr_dx_9_10',
				'#FLD1#' => 'code',
				'#POS1#' => 1,
				'#LEN1#' => 7,
				'#FLD2#' => 'code_cnt',
				'#POS2#' => 9,
				'#LEN2#' => 1,
				'#FLD3#' => 'ICD9_01',
				'#POS3#' => 11,
				'#LEN3#' => 5,
				'#FLD4#' => 'ICD9_02',
				'#POS4#' => 17,
				'#LEN4#' => 5,
				'#FLD5#' => 'ICD9_03',
				'#POS5#' => 23,
				'#LEN5#' => 5,
				'#FLD6#' => 'ICD9_04',
				'#POS6#' => 29,
				'#LEN6#' => 5,
				'#FLD7#' => 'ICD9_05',
				'#POS7#' => 35,
				'#LEN7#' => 5,
				'#FLD8#' => 'ICD9_06',
				'#POS8#' => 41,
				'#LEN8#' => 5
			];
			$incoming['2012_I10gem'] = [
				'#TABLENAME#' => 'icd10_gem_dx_10_9',
				'#FLD1#' => 'dx_icd10_source',
				'#POS1#' => 1,
				'#LEN1#' => 7,
				'#FLD2#' => 'dx_icd9_target',
				'#POS2#' => 9,
				'#LEN2#' => 5,
				'#FLD3#' => 'flags',
				'#POS3#' => 15,
				'#LEN3#' => 5
			];
			$incoming['2012_I9gem'] = [
				'#TABLENAME#' => 'icd10_gem_dx_9_10',
				'#FLD1#' => 'dx_icd9_source',
				'#POS1#' => 1,
				'#LEN1#' => 5,
				'#FLD2#' => 'dx_icd10_target',
				'#POS2#' => 7,
				'#LEN2#' => 7,
				'#FLD3#' => 'flags',
				'#POS3#' => 15,
				'#LEN3#' => 5
			];
			$incoming['gem_pcsi9'] = [
				'#TABLENAME#' => 'icd10_gem_pcs_10_9',
				'#FLD1#' => 'pcs_icd10_source',
				'#POS1#' => 1,
				'#LEN1#' => 7,
				'#FLD2#' => 'pcs_icd9_target',
				'#POS2#' => 9,
				'#LEN2#' => 5,
				'#FLD3#' => 'flags',
				'#POS3#' => 15,
				'#LEN3#' => 5
			];
			$incoming['gem_i9pcs'] = [
				'#TABLENAME#' => 'icd10_gem_pcs_9_10',
				'#FLD1#' => 'pcs_icd9_source',
				'#POS1#' => 1,
				'#LEN1#' => 5,
				'#FLD2#' => 'pcs_icd10_target',
				'#POS2#' => 7,
				'#LEN2#' => 7,
				'#FLD3#' => 'flags',
				'#POS3#' => 15,
				'#LEN3#' => 5
			];
		}
		// set up the start of the load script to be appended from the incoming array
		// defined above where incoming
		// file matches
		$db_load = "LOAD DATA LOCAL INFILE '#INFILE#' INTO TABLE #TABLENAME# FIELDS TERMINATED BY '\0' (@var) SET revision = #REVISION#, active = 1, ";
		$col_template = '#FLD# = trim(Substring(@var, #POS#, #LEN#))';
		// load all data and set active revision
		if(is_dir($dir) && $handle = opendir($dir))
        {
			while(false !== ($filename = readdir($handle)))
            {
				// bypass unwanted entries
				if(!stripos($filename, '.txt') || stripos($filename, 'diff')) continue;
				// reset the sql load command and susbtitute the filename
				$run_sql = $db_load;
				$run_sql = str_replace('#INFILE#', $dir . $filename, $run_sql);
				$keys = array_keys($incoming);
				while($this_key = array_pop($keys))
                {
					if(stripos($filename, $this_key) !== false)
                    {
						// now substitute the tablename
						$run_sql = str_replace('#TABLENAME#', $incoming[$this_key]['#TABLENAME#'], $run_sql);
						// the range defines the maximum number of fields contained
						// in any of the incoming files
						foreach(range(1, 8) as $field)
                        {
							$fld = '#FLD' . $field . '#';
							$pos = '#POS' . $field . '#';
							$len = '#LEN' . $field . '#';
							$nxtfld = '#FLD' . ($field + 1) . '#';
							// concat this fields template in the sql string
							$run_sql .= $col_template;
							$run_sql = str_replace('#FLD#', $incoming[$this_key][$fld], $run_sql);
							$run_sql = str_replace('#POS#', $incoming[$this_key][$pos], $run_sql);
							$run_sql = str_replace('#LEN#', $incoming[$this_key][$len], $run_sql);
							// at the end of this table's field list
							if(!array_key_exists($nxtfld, $incoming[$this_key])) break;
							$run_sql .= ',';
						}
						// get the next revision
						$this->db->setSQL('SELECT IFNULL(max(revision), 0) AS rev FROM ' . $incoming[$this_key]['#TABLENAME#']);
						$row = $this->db->fetchRecord(PDO::FETCH_ASSOC);
						$next_rev = $row['rev'] + 1;
						// if the next revision is grater than one, lets disable the existing codes
						if($next_rev > 1)
                        {
							$this->db->setSQL($this->db->sqlBind(['active' => 0], $incoming[$this_key]['#TABLENAME#'], 'U'));
							$this->db->execOnly();
						}
						// now lets replace the '#REVISION#' placeholder for the next revision
						// and execute the $run_sql with the new codes
						$run_sql = str_replace('#REVISION#', $next_rev, $run_sql);
						$this->db->exec($run_sql);
						$this->installedRevision = $next_rev;
					}
				}
			}
		}
        else
        {
			$this->error = 'No ICD import directory';
			return false;
		}
		// now update the tables where necessary
		if($type == 'ICD9')
        {
			$this->db->setSQL("UPDATE `icd9_dx_code` SET formatted_dx_code = dx_code");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd9_dx_code` SET formatted_dx_code = concat(concat(LEFT(dx_code, 3), '.'), substr(dx_code, 4)) WHERE dx_code RLIKE '^[V0-9]{1}.*' AND LENGTH(dx_code) > 3");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd9_dx_code` SET formatted_dx_code = concat(concat(LEFT(dx_code, 4), '.'), substr(dx_code, 5)) WHERE dx_code RLIKE '^[E]{1}.*' AND LENGTH(dx_code) > 4");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd9_sg_code` SET formatted_sg_code = concat(concat(LEFT(sg_code, 2), '.'), substr(sg_code, 3))");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd9_dx_code` A, `icd9_dx_long_code` B SET A.long_desc = B.long_desc WHERE A.dx_code = B.dx_code AND A.active = 1 AND A.long_desc IS NULL");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd9_sg_code` A, `icd9_sg_long_code` B SET A.long_desc = B.long_desc WHERE A.sg_code = B.sg_code AND A.active = 1 AND A.long_desc IS NULL");
			$this->db->execOnly();
		}
        else
        {
			// ICD 10
			$this->db->setSQL("UPDATE `icd10_dx_order_code` SET formatted_dx_code = dx_code");
			$this->db->execOnly();
			$this->db->setSQL("UPDATE `icd10_dx_order_code` SET formatted_dx_code = concat(concat(LEFT(dx_code, 3), '.'), substr(dx_code, 4)) WHERE LENGTH(dx_code) > 3");
			$this->db->execOnly();
		}
		return true;
	}

	private function rxnorm_import($dir)
    {
		$dirScripts = $dir . '/scripts/mysql';
		$dir = $dir . '/rrf';
		$dir = str_replace('\\', '/', $dir);
		$rx_info = [];
		$rx_info['rxnconso'] = [
			'title' => 'Concept Names and Sources',
			'dir' => $dir,
			'origin' => 'RXNCONSO.RRF',
			'filename' => 'RXNCONSO.RRF',
			'table' => 'rxnconso',
			'required' => 1
		];
		$rx_info['rxnrel'] = [
			'title' => 'Relationships',
			'dir' => $dir,
			'origin' => 'RXNREL.RRF',
			'filename' => 'RXNREL.RRF',
			'table' => 'rxnrel',
			'required' => 1
		];

		$rx_info['rxnsat'] = [
			'title' => 'Simple Concept and Atom Attributes',
			'dir' => $dir,
			'origin' => 'RXNSAT.RRF',
			'filename' => 'RXNSAT.RRF',
			'table' => 'rxnsat',
			'required' => 0
		];
		// load scripts
		$file_load = file_get_contents($dirScripts . '/Table_scripts_mysql_rxn.sql', true);
		if($_SESSION['server']['IS_WINDOWS'])
        {
			$data_load = file_get_contents($dirScripts . '/Load_scripts_mysql_rxn_win.sql', true);
		}
        else
        {
			$data_load = file_get_contents($dirScripts . '/Load_scripts_mysql_rxn_unix.sql', true);
		}
		$indexes_load = file_get_contents($dirScripts . '/Indexes_mysql_rxn.sql', true);
		// Creating the structure for table and applying indexes
		$file_array = explode(';', $file_load);
		foreach($file_array as $val)
        {
			if(trim($val) != '')
            {
				$stmt = $this->db->conn()->prepare($val);
				$stmt->execute();
			}
		}

		$indexes_array = explode(';', $indexes_load);
		foreach($indexes_array as $val1)
        {
			if(trim($val1) != '')
            {
				$stmt = $this->db->conn()->prepare($val1);
				$stmt->execute();
			}
		}
		$data = explode(';', $data_load);
		foreach($data as $val)
        {
			foreach($rx_info as $key => $value)
            {
				$file_name = $value['origin'];
				$replacement = $dir . '/' . $file_name;
				if(strpos($val, $file_name) !== false)
                {
					$val1 = str_replace($file_name, $replacement, $val);
					if(trim($val1) != '')
                    {
						$this->db->conn()->exec($val1);
					}
				}
			}
		}

		$stmt = $this->db->conn()->prepare('CREATE INDEX X_RXNSAT_CODE ON `rxnsat` (`CODE`) USING BTREE');
		$stmt->execute();
		$stmt = $this->db->conn()->prepare('CREATE INDEX X_RXNSAT_SAB ON `rxnsat` (`SAB`) USING BTREE');
		$stmt->execute();
		$stmt = $this->db->conn()->prepare('CREATE INDEX X_RXNCONSO_SAB ON `rxnconso` (`SAB`) USING BTREE');
		$stmt->execute();
		$stmt = $this->db->conn()->prepare('CREATE INDEX X_RXNCONSO_SEARCH ON `rxnconso` (`STR`,`RXCUI`,`SAB`,`TTY`) USING BTREE');
		$stmt->execute();
		$stmt = $this->db->conn()->prepare('CREATE INDEX X_RXNSAT_SEARCH ON `rxnsat` (`RXCUI`,`SAB`,`ATN`,`ATV`) USING BTREE');
		$stmt->execute();

		return true;
	}

	private function snomed_import($dir)
    {
		// set up array
		$table_array_for_snomed = [
			'sct_concepts_drop' => 'DROP TABLE IF EXISTS sct_concepts',
			'sct_concepts_structure' => 'CREATE TABLE IF NOT EXISTS sct_concepts (
	            ConceptId BIGINT(20) NOT NULL,
	            ConceptStatus INT(11) NOT NULL,
	            FullySpecifiedName VARCHAR(255) NOT NULL,
	            CTV3ID VARCHAR(5) NOT NULL,
	            SNOMEDID VARCHAR(8) NOT NULL,
	            IsPrimitive TINYINT(1) NOT NULL,
	            PRIMARY KEY (ConceptId),
	            INDEX X_SNOMEDID (SNOMEDID),
	            INDEX X_FullySpecifiedName (FullySpecifiedName)
	            )',
			'sct_descriptions_drop' => 'DROP TABLE IF EXISTS sct_descriptions',
			'sct_descriptions_structure' => 'CREATE TABLE IF NOT EXISTS sct_descriptions (
	            DescriptionId BIGINT(20) NOT NULL,
	            DescriptionStatus INT(11) NOT NULL,
	            ConceptId BIGINT(20) NOT NULL,
	            Term VARCHAR(255) NOT NULL,
	            InitialCapitalStatus TINYINT(1) NOT NULL,
	            DescriptionType INT(11) NOT NULL,
	            LanguageCode VARCHAR(8) NOT NULL,
	            PRIMARY KEY (DescriptionId),
	            INDEX X_ConceptId (ConceptId),
	            INDEX X_Term (Term)
	            )',
			'sct_relationships_drop' => 'DROP TABLE IF EXISTS sct_relationships',
			'sct_relationships_structure' => 'CREATE TABLE IF NOT EXISTS sct_relationships (
	            RelationshipId BIGINT(20) NOT NULL,
	            ConceptId1 BIGINT(20) NOT NULL,
	            RelationshipType BIGINT(20) NOT NULL,
	            ConceptId2 BIGINT(20) NOT NULL,
	            CharacteristicType INT(11) NOT NULL,
	            Refinability INT(11) NOT NULL,
	            RelationshipGroup INT(11) NOT NULL,
	            PRIMARY KEY (RelationshipId),
	            INDEX X_ConceptId1(ConceptId1),
	            INDEX X_ConceptId2(ConceptId2),
	            INDEX X_RelationshipType(RelationshipType)
	            )',
			'sct_relationships_loinc_drop' => 'DROP TABLE IF EXISTS sct_relationships_loinc',
			'sct_relationships_loinc_structure' => 'CREATE TABLE IF NOT EXISTS sct_relationships_loinc (
	            LoincNum VARCHAR(7) NOT NULL,
	            Component VARCHAR(150) NOT NULL,
	            Property VARCHAR(25) NOT NULL,
	            TimAspect VARCHAR(25) NOT NULL,
	            System VARCHAR(50) NOT NULL,
	            ScaleType VARCHAR(25) NOT NULL,
	            MethodType VARCHAR(60) NOT NULL,
	            RelationNms VARCHAR(256) NOT NULL,
	            AnswerList VARCHAR(256) NOT NULL,
	            RelationshipType VARCHAR(25) NOT NULL,
	            ConceptId VARCHAR(25) NOT NULL,
	            INDEX X_LoincNum(LoincNum),
	            INDEX X_ConceptId(ConceptId)
            	)',
			'sct_relationships_icd9_drop' => 'DROP TABLE IF EXISTS sct_relationships_icd9',
			'sct_relationships_icd9_structure' => 'CREATE TABLE IF NOT EXISTS sct_relationships_icd9 (
	            TARGETID VARCHAR(15) NOT NULL,
	            TARGETSCHEMEID VARCHAR(50) NOT NULL,
	            TARGETCODES VARCHAR(50) NOT NULL,
	            TARGETRULE VARCHAR(50) NOT NULL,
	            TARGETADVICE VARCHAR(50) NOT NULL,
	            INDEX X_TARGETID(TARGETID),
	            INDEX X_TARGETCODES(TARGETCODES)
            	)',
			'sct_relationships_icd10_drop' => 'DROP TABLE IF EXISTS sct_relationships_icd10',
			'sct_relationships_icd10_structure' => 'CREATE TABLE IF NOT EXISTS sct_relationships_icd10 (
	            TARGETID VARCHAR(15) NOT NULL,
	            TARGETSCHEMEID VARCHAR(50) NOT NULL,
	            TARGETCODES VARCHAR(50) NOT NULL,
	            TARGETRULE VARCHAR(50) NOT NULL,
	            TARGETADVICE VARCHAR(50) NOT NULL,
	            INDEX X_TARGETID(TARGETID),
	            INDEX X_TARGETCODES(TARGETCODES)
            	)'
		];

		// set up paths
		$dir_snomed = $dir . '/';
		$dir = $dir_snomed;
		$dir = str_replace('\\', '/', $dir);
		// executing the create statement for tables, these are defined in
		// snomed_capture.inc file
		foreach($table_array_for_snomed as $val)
        {
			if(trim($val) != '')
            {
				$stmt = $this->db->conn()->prepare($val);
				$stmt->execute();
			}
		}

		// reading the SNOMED directory and identifying the files to import and replacing
		// the variables by originals values.
		$sub_path = 'Terminology/Content/';
		$this->loadSnomedData($dir, $sub_path);

		//$sub_path = 'OtherResources/LOINCIntegration/';
		//$this->loadSnomedData($dir, $sub_path);

		$sub_path = 'Maps/ICD9/';
		$this->loadSnomedData($dir, $sub_path);

		$sub_path = 'Maps/ICDO/';
		$this->loadSnomedData($dir, $sub_path);
		return true;
	}

	private function snomed_problems($dir)
    {
		// set up array
		$this->db->conn()->exec('
			DROP TABLE IF EXISTS sct_problem_list;
			CREATE TABLE sct_problem_list (
	            SNOMED_CID VARCHAR(15) NOT NULL,
	            SNOMED_FSN VARCHAR(255) NOT NULL,
	            SNOMED_CONCEPT_STATUS VARCHAR(20) NOT NULL,
	            UMLS_CUI VARCHAR(20) NOT NULL,
	            OCCURRENCE VARCHAR(20) NOT NULL,
	            CONCEPT_USAGE VARCHAR(20) NOT NULL,
	            FIRST_IN_SUBSET VARCHAR(20) NOT NULL,
	            IS_RETIRED_FROM_SUBSET VARCHAR(20) NOT NULL,
	            LAST_IN_SUBSET VARCHAR(20) NOT NULL,
	            REPLACED_BY_SNOMED_CID VARCHAR(20) NOT NULL,
	            PRIMARY KEY (SNOMED_CID),
	            INDEX X_SNOMED_FSN (SNOMED_FSN)
            );
		');

		$dir = str_replace('\\', '/', $dir . '/');

		if(is_dir($dir) && $handle = opendir($dir))
        {
			while(false !== ($filename = readdir($handle)))
            {
				if(($filename != '.' || $filename != '..') && strstr($filename, 'SNOMEDCT_CORE_SUBSET') !== false)
                {
					$path = $dir . $filename;
					$load_script = "LOAD DATA LOCAL INFILE '#FILENAME#' into table #TABLE# fields terminated by '|' ESCAPED BY '' lines terminated by '\\n' ignore 1 lines ";
					$array_replace = [
						'#FILENAME#',
						'#TABLE#'
					];
					$new_str = str_replace($array_replace, [
						$path,
						'sct_problem_list'
					], $load_script);
					if(isset($new_str)) $this->db->conn()->exec($new_str);
				}
			}
			closedir($handle);
		}
		return true;
	}

	/**
	 * Method: loadSnomedData
	 * A method to load an parse the SNOMED Downloaded .zip file
	 * when it is parsed, inject the TXT file into the database.
	 * @param $dir
	 * @param $sub_path
	 */
	private function loadSnomedData($dir, $sub_path)
    {
		// This max executin time, is temporary
		// Match should control this time, in INIT time of loading the library
		// And it should be configurable too.
		@ini_set('max_execution_time', 0);

		if(is_dir($dir . $sub_path) && $handle = opendir($dir . $sub_path))
        {
			while(false !== ($filename = readdir($handle)))
            {
				if($filename != '.' && $filename != '..' && !strpos($filename, 'zip'))
                {

					$path = $dir . $sub_path . $filename;

					$load_script = "LOAD DATA LOCAL INFILE '#FILENAME#' into table #TABLE# fields terminated by '\\t' ESCAPED BY '' lines terminated by '\\n' ignore 1 lines ";

					$array_replace = [
						'#FILENAME#',
						'#TABLE#'
					];
					if(strpos($filename, 'Concepts') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_concepts'
						], $load_script);
					}
                    elseif(strpos($filename, 'Descriptions') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_descriptions'
						], $load_script);
					}
                    elseif(strpos($filename, 'Relationships') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_relationships'
						], $load_script);
					}
                    elseif(strpos($filename, 'Integration_LOINC') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_relationships_loinc'
						], $load_script);
					}
                    elseif(strpos($filename, 'CrossMapTargets_ICD9') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_relationships_icd9'
						], $load_script);
					}
                    elseif(strpos($filename, 'CrossMapTargets_ICDO') !== false)
                    {
						$new_str = str_replace($array_replace, [
							$path,
							'sct_relationships_icd10'
						], $load_script);
					}
					if(isset($new_str)) $this->db->conn()->exec($new_str);
				}
			}
			closedir($handle);
		}
	}

	private function updateTrackerTable($name, $codeType, $revision, $version, $date, $file_checksum = '') {
		$data = [];
		$data['code_type'] = $codeType;
		$data['imported_date'] = Time::getLocalTime();
		$data['revision_name'] = $name;
		$data['revision_date'] = $date;
		$data['revision_number'] = $revision;
		$data['revision_version'] = $version;
		$data['file_checksum'] = $file_checksum;

		//        print_r($data);
		$this->db->setSQL($this->db->sqlBind($data, 'standardized_tables_track', 'I'));
		$this->db->execLog();
		return true;
	}

	private function getCodeDir() {
		return ROOT . '/contrib/' . strtolower($this->codeType);
	}

	public function getCurrentCodesInfo() {
		$codes = [];
		$codes[] = ['data' => $this->getCurrentCodeInfoByCodeType('ICD9')];
		$codes[] = ['data' => $this->getCurrentCodeInfoByCodeType('ICD10')];
		$codes[] = ['data' => $this->getCurrentCodeInfoByCodeType('RXNORM')];
		$codes[] = ['data' => $this->getCurrentCodeInfoByCodeType('SNOMED')];
		$codes[] = ['data' => $this->getCurrentCodeInfoByCodeType('HCPCS')];
		return $codes;
	}

	public function getCurrentCodeInfoByCodeType($codeType) {
		$this->db->setSQL("SELECT code_type AS codeType,
								  imported_date,
								  revision_name,
								  revision_number,
							      revision_version,
							      revision_date
							 FROM standardized_tables_track
							WHERE code_type = '$codeType'
						 ORDER BY imported_date DESC");
		return $this->db->fetchRecord();
	}

	private function getCurrentCodeVersion() {
		$code = $this->getCurrentCodeInfoByCodeType($this->codeType);
		if(!empty($code)){
			return $code['revision_version'];
		} else {
			return 0;
		}
	}

	private function isCodeVersionNewer($versionToInstall) {
		$versionInstalled = $this->getCurrentCodeVersion();
		if($versionInstalled > $versionToInstall){
			$this->error = "You currently have a newer database version installed (version $versionInstalled)";
			return false;
		} elseif($versionInstalled == $versionToInstall) {
			$this->error = "Database version $versionInstalled is currently installed";
			return false;
		} else {
			return true;
		}
	}

}

//
//$f = new ExternalDataUpdate();
//print '<pre>';
//$params = new stdClass();
//$params->codeType = 'HCPCS';
//$params->version = 2013;
//$params->basename = '13anweb.zip';
//$params->path = 'C:\inetpub\wwwroot\gaiaehr\contrib\hcpcs\13anweb.zip';
//$f->updateCodes($params);
