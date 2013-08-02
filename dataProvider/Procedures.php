<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Procedures
{
	/**
	 * @var bool|MatchaCUP
	 */
	private $p;

    function __construct()
    {
		$this->p = MatchaModel::setSenchaModel('App.model.patient.encounter.Procedures');
        return;
    }

	public function loadProcedures($params){
        /**
         * Audit Log
         * Added by: Gino Rivera
         * GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
         */
        $this->db->AuditLog('Encounter procedures viewed');
		return $this->p->load($params)->all();
	}

	public function saveProcedure($params){
        /**
         * Audit Log
         * Added by: Gino Rivera
         * GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
         */
        $this->db->AuditLog('Encounter procedures updated');
		return $this->p->save($params);
	}

	public function destroyProcedure($params){
        /**
         * Audit Log
         * Added by: Gino Rivera
         * GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
         */
        $this->db->AuditLog('Encounter procedures deleted');
		return $this->p->destroy($params);
	}

}
//print '<pre>';
//$p = new Prescriptions();
//$params = new stdClass();
//$params->query = 't';
//print_r($p->getSigCodesByQuery($params));
