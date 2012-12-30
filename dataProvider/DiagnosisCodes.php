<?php
/*
 GaiaEHR (Electronic Health Records)
 DiagnosisCodes.php
 Diagnosis Codes dataProvider
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
if (!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
$_SESSION['site']['flops'] = 0;
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/Arrays.php');
ini_set('memory_limit', '256M');
class DiagnosisCodes
{
    private $db;

    function __construct()
    {
        $this->db = new dbHelper();
        return;
    }

    public function ICDCodeSearch($query)
    {
        if (!is_string($query)) {
            $query = $query->query;
        }
        /**
         * get last code revision
         */
        $revision = $this->getLastRevisionByCodeType('ICD10');
        $records = array();
        /**
         * ICD9
         */
        $this->db->setSQL("SELECT dx_id 			AS id,
								  formatted_dx_code,
								  formatted_dx_code AS code,
								  dx_code,
								  dx_code 			AS xcode,
								  long_desc,
								  long_desc 		AS code_text,
								  short_desc,
								  'ICD9-DX'			AS code_type
						     FROM icd9_dx_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$query%'
                        	   OR long_desc 		LIKE '$query%'
                        	   OR dx_code			LIKE '$query%'
                        	   OR formatted_dx_code	LIKE '$query%')
                         ORDER BY formatted_dx_code ASC");
        $records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_ASSOC));
        /**
         * ICD9 Surgery
         */
        $this->db->setSQL("SELECT sg_id 			AS id,
								  formatted_sg_code,
								  formatted_sg_code	AS code,
								  sg_code,
								  sg_code			AS xcode,
								  long_desc,
								  long_desc 		AS code_text,
								  short_desc,
                                  'ICD9-SG'			AS code_type
							 FROM icd9_sg_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$query%'
                        	   OR long_desc 		LIKE '$query%'
                        	   OR sg_code			LIKE '$query%'
                        	   OR formatted_sg_code	LIKE '$query%')
                         ORDER BY formatted_sg_code ASC");
        $records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_ASSOC));
        /**
         * ICD10 DX
         */
        $this->db->setSQL("SELECT dx_id 			AS id,
								  formatted_dx_code,
								  formatted_dx_code AS code,
								  dx_code,
								  dx_code 			AS xcode,
								  long_desc,
								  long_desc 		AS code_text,
								  short_desc,
								  'ICD10-DX'		AS code_type
							 FROM icd10_dx_order_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$query%'
                        	   OR long_desc 		LIKE '$query%'
                        	   OR dx_code 			LIKE '$query%'
                        	   OR formatted_dx_code	LIKE '$query%')
                         ORDER BY formatted_dx_code ASC");
        $records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_ASSOC));
        /**
         * ICD10 PCS
         */
        $this->db->setSQL("SELECT pcs_id 			AS id,
								  pcs_code,
								  pcs_code			AS code,
								  pcs_code			AS xcode,
								  long_desc,
								  long_desc 		AS code_text,
								  short_desc,
								  'ICD10-PCS'		AS code_type
							 FROM icd10_pcs_order_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$query%'
                        	   OR long_desc 		LIKE '$query%'
                        	   OR pcs_code 			LIKE '$query%')
                         ORDER BY pcs_code ASC");
        $records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_ASSOC));

        if (is_object($query)) {
            $total = count($records);
            $records = array_slice($records, $query->start, $query->limit, true);
            return array('totals' => $total, 'rows' => $records);
        } else {
            return $records;
        }

    }

    public function getICDDataByCode($code)
    {
        $data = array();
        $revision = $this->getLastRevisionByCodeType('ICD9');
        $this->db->setSQL("SELECT *, formatted_dx_code AS code, 'ICD9-DX' AS code_type
						  	 FROM icd9_dx_code
						 	WHERE (dx_code  = '$code' OR formatted_dx_code  = '$code')
						      AND revision = '$revision'");
        $data[] = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $this->db->setSQL("SELECT *, formatted_sg_code AS code, 'ICD9-SG' AS code_type
						  	 FROM icd9_sg_code
						 	WHERE (sg_code  = '$code' OR formatted_sg_code  = '$code')
						      AND revision = '$revision'");
        $data[] = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $revision = $this->getLastRevisionByCodeType('ICD10');
        $this->db->setSQL("SELECT *, formatted_dx_code AS code, 'ICD10-DX' AS code_type
						  	 FROM icd10_dx_order_code
						 	WHERE (dx_code  = '$code' OR formatted_dx_code  = '$code')
						      AND revision = '$revision'");
        $data[] = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $this->db->setSQL("SELECT *, pcs_code AS code, 'ICD10-PCS' AS code_type
						  	 FROM icd10_pcs_order_code
						 	WHERE pcs_code = '$code'
						      AND revision = '$revision'");
        $data[] = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        foreach ($data as $foo) {
            if (is_array($foo)) {
                return $foo;
            }
        }
        return array();
    }

    public function getICD9CodesByICD10Code($ICD10)
    {
        $ICD9s = array();
        $revision = $this->getLastRevisionByCodeType('ICD10');
        $this->db->setSQL("SELECT b.formatted_dx_code AS code,
								  'ICD9-DX' AS code_type, b.*
						  	 FROM icd10_gem_dx_10_9 AS a
						LEFT JOIN icd9_dx_code AS b ON b.dx_code = a.dx_icd9_target
						 	WHERE a.dx_icd10_source = '$ICD10'
						 	  AND a.revision = '$revision'");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if (!empty($records))
            $ICD9s = array_merge($ICD9s, $records);
        $this->db->setSQL("SELECT b.formatted_sg_code AS code,
								  'ICD9-SG' AS code_type, b.*
						  	 FROM icd10_gem_pcs_10_9 AS a
						LEFT JOIN icd9_sg_code AS b ON b.sg_code = a.pcs_icd9_target
						 	WHERE a.pcs_icd10_source = '$ICD10'
						 	  AND a.revision = '$revision'");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if (!empty($records))
            $ICD9s = array_merge($ICD9s, $records);
        return $ICD9s;
    }

    public function getICD10CodesByICD9Code($ICD9)
    {
        $ICD10s = array();
        $revision = $this->getLastRevisionByCodeType('ICD9');
        $this->db->setSQL("SELECT b.formatted_dx_code AS code,
								  'ICD10-DX' AS code_type, b.*
						  	 FROM icd10_gem_dx_9_10 AS a
					    LEFT JOIN icd10_dx_order_code AS b ON b.dx_code = a.dx_icd10_target
						 	WHERE a.dx_icd9_source = '$ICD9'
						 	  AND a.revision = '$revision'");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if (!empty($records))
            $ICD10s = array_merge($ICD10s, $records);
        $this->db->setSQL("SELECT b.pcs_code AS code,
								  'ICD10-PCS' AS code_type, b.*
						  	 FROM icd10_gem_pcs_9_10 AS a
					    LEFT JOIN icd10_pcs_order_code AS b ON b.pcs_code = a.pcs_icd10_target
						 	WHERE a.pcs_icd9_source = '$ICD9'
						 	  AND a.revision = '$revision'");
        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
        if (!empty($records))
            $ICD10s = array_merge($ICD10s, $records);
        return $ICD10s;
    }

    public function getLastRevisionByCodeType($codeType)
    {
        $this->db->setSQL("SELECT MAX(revision_number) AS last_revision
                        	 FROM standardized_tables_track
                        	WHERE code_type = '$codeType'");
        $record = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        return $record['last_revision'];
    }

    public function getICDByEid($eid)
    {
        $records = array();
        $this->db->setSQL("SELECT code
							 FROM encounter_codes_icdx
							WHERE eid = '$eid'
                            ORDER BY id ASC");
        foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $foo) {
            $records[] = $this->getICDDataByCode($foo['code']);
        }
        return $records;
    }

    public function getICDByPid($pid)
    {
        $records = array();
        $this->db->setSQL("SELECT code
							 FROM encounter_codes_icdx
							WHERE pid = '$pid'
                            ORDER BY id ASC");
        foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $foo) {
            $records[] = $this->getICDDataByCode($foo['code']);
        }
        return $records;
    }

    public function liveCodeSearch(stdClass $params)
    {
        $records = array();
        $haystack = array();
        $queries = explode(' ', $params->query);
        foreach ($queries as $query) {

            foreach ($this->ICDCodeSearch(trim($query)) as $row) {
                if (array_key_exists($row['code'], $haystack)) {
                    $foo = $records[$row['code']];
                    unset($records[$row['code']]);
                    $foo['weight']++;
                    $records[$row['code']] = $foo;
                } else {
                    $row['weight'] = 1;
                    $haystack[$row['code']] = 1;
                    $records[$row['code']] = $row;
                }
            }
        }
        $records = array_slice($records, 0, 300, false);
        Arrays::sksort($records, 'weight', false);
        $total = count($records);
        $records = array_slice($records, $params->start, $params->limit, false);
        return array(
            'totals' => $total,
            'rows' => array_values($records)
        );
    }

}

//$f = new DiagnosisCodes();
//print '<pre>';
//$params = new stdClass();
//$params->codeType = 'ICD9';
//$params->query = '0';
//$params->start = 0;
//$params->limit = 25;
//print_r($f->ICDCodeSearch($params));
