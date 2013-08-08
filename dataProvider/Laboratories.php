<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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

class Laboratories
{
	/**
	 * @var MatchaCUP
	 */
	private $LO = null;
	/**
	 * @var MatchaCUP
	 */
	private $LP = null;

    function __construct()
    {
	    $this->db = new MatchaHelper();
	    if($this->LP == null) $this->LO = MatchaModel::setSenchaModel('App.model.administration.LabPanels');
	    if($this->LO == null) $this->LO = MatchaModel::setSenchaModel('App.model.administration.LabObservations');
        return;
    }

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
    public function getLoincPanels(stdClass $params)
    {

	    $this->db->setSQL("SELECT p.ID as id,
                                  p.SEQUENCE as sequence,
                                  l.LONG_COMMON_NAME as code_text_short,
                                  p.LOINC_NUM as code,
                                  'LOINC' as code_type,
                                  p.active
                             FROM labs_loinc_panels AS p
                        LEFT JOIN labs_loinc AS l ON p.LOINC_NUM = l.LOINC_NUM
                            WHERE p.PARENT_id = p.ID

                              AND p.parent_name LIKE '%$params->query%'");
//        $sort = isset($params->sort) ? $params->sort[0]->property . ' ' . $params->sort[0]->direction : 'sequence ASC';
//        $sqlStatement['SELECT'] = "id, parent_id, parent_loinc, sequence, default_unit, shortname AS code_text_short, parent_name AS code_text, number AS code, active";
//        $sqlStatement['LEFTJOIN'] = "labs_loinc on loinc_num = parent_loinc";
//        $sqlStatement['WHERE'] = "parent_name LIKE '%$params->query%' AND id = parent_id";
//        $sqlStatement['ORDER'] = $sort;
        return $this->db->fetchRecords();
    }

    /**
     * @param stdClass $params
     * @return array
     */
    public function getLabObservations(stdClass $params)
    {
        return $this->getLabObservationFieldsByParentId($params->selectedId);
    }

    /**
     * @param stdClass $params
     * @return stdClass
     */
    public function updateLabObservation(stdClass $params)
    {
        $data = get_object_vars($params);
        unset($data['id']);
        $this->LO->save($data);
        return $params;
    }

    public function getActiveLaboratoryTypes()
    {
        $records = array();
        $sqlStatement['SELECT'] = "id, code_text_short, parent_name, loinc_name";
        $sqlStatement['WHERE'] = "id = parent_id AND active = '1'";
        $sqlStatement['ORDER'] = "parent_name ASC";
        foreach ($this->LO->buildSQL($sqlStatement)->all() as $row)
        {
            $row->label = ($row->code_text_short == '' || $row->code_text_short == null) ? $row->parent_name : $row->code_text_short;
            $row->fields = $this->getLabObservationFieldsByParentId($row->id);
            $records[] = $row;
        }
        return $records;
    }

    public function getLabObservationFieldsByParentId($id)
    {
        $records = array();

	    $this->db->setSQL("SELECT p.ID as id,
                                  p.PARENT_id as parent_id,
                                  p.SEQUENCE as sequence,
                                  l.LONG_COMMON_NAME as loinc_name,
                                  p.LOINC_NUM as loinc_number,
                                  p.OBSERVATION_REQUIRED_IN_PANEL as required_in_panel,
                                  'LOINC' as code_type,
                                  p.ACTIVE as active,
                                  l.EXAMPLE_UNITS as default_unit
                             FROM labs_loinc_panels AS p
                        LEFT JOIN labs_loinc AS l ON p.LOINC_NUM = l.LOINC_NUM
                            WHERE p.PARENT_id != ID
                              AND p.PARENT_id = '$id'
                              AND l.STATUS = 'ACTIVE'
                         ORDER BY SEQUENCE");


        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
//	        print_r($row);

//            $row['default_unit'] = ($row['default_unit'] == null || $row['default_unit'] == '') ? $row['SUBMITTED_UNITS'] : $row['default_unit'];
            $records[] = $row;
        }
        return $records;
    }

}
