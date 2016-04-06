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
class Rxnorm {
	/**
	 * @var PDO
	 */
	private $db;

	/**
	 * @var MatchaCUP
	 */
	private $i;

	function __construct()
    {
		$this->db = Matcha::getConn();
	}

	function setInstructionModel()
    {
		if(isset($this->i)) return $this->i;
		return $this->i = MatchaModel::setSenchaModel('App.model.administration.MedicationInstruction');
	}

	public function getStrengthByCODE($CODE)
    {
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DST'
		                      AND SAB    = 'RXNORM'");
		$sth->execute([':c' => $CODE]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDrugRouteByCODE($CODE) {
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DRT'
		                      AND SAB    = 'RXNORM'");
		$sth->execute([':c' => $CODE]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDoseformByCODE($CODE)
    {
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DDF'
		                      AND SAB    = 'RXNORM'");
		$sth->execute([':c' => $CODE]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

    public function getIngredient($RxNormCode){

        // Fetch the RxNorm entire record, this because we need the entire RxNorm Concepts record
        $Statement = $this->db->prepare("SELECT * FROM rxnconso WHERE CODE=:code AND (TTY='SCD' OR TTY='SBD')");
        $Statement->execute([':code' => $RxNormCode]);
        $RxNormRecord = $Statement->fetchAll(PDO::FETCH_ASSOC);

        // If the record has the Branded Record execute SQL to extract
        // the ingredient of that branded drug
        if($RxNormRecord['TTY']=='SBD') {
            // Fetch the relationship for ingredient
            $Statement = $this->db->prepare("SELECT * FROM rxnrel WHERE RXCUI2=:rxcui AND RELA='has_ingredient'");
            $Statement->execute([':rxcui' => $RxNormRecord['RXCUI']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            // Fetch the relationship for trademark
            $Statement = $this->db->prepare("SELECT * FROM rxnrel WHERE RXCUI1=:rxcui AND RELA='has_tradename'");
            $Statement->execute([':rxcui' => $Record['RXCUI1']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            // Extract the ingredient
            $Statement = $this->db->prepare("SELECT * FROM gaiadb.rxnconso WHERE RXCUI=:rxcui AND SAB='RXNORM'");
            $Statement->execute([':rxcui' => $Record['RXCUI2']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            return $Record;
        }
        // If the record has the Clinical record execute SQL to
        // narrow the ingredient only
        elseif($RxNormRecord['TTY']=='SCD') {
            // Fetch the relationship for consists of...
            $Statement = $this->db->prepare("SELECT * FROM rxnrel WHERE RXCUI2=:rxcui AND RELA='consists_of'");
            $Statement->execute([':rxcui' => $RxNormRecord['RXCUI']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            // Fetch the relationship for ingredient of...
            $Statement = $this->db->prepare("SELECT * FROM rxnrel WHERE RXCUI1=:rxcui AND RELA='ingredient_of'");
            $Statement->execute([':rxcui' => $Record['RXCUI1']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            // Extract the ingredient
            $Statement = $this->db->prepare("SELECT * FROM rxnconso WHERE RXCUI=:rxcui AND SAB='RXNORM'");
            $Statement->execute([':rxcui' => $Record['RXCUI2']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            return $Record;
        }
        elseif($RxNormRecord['TTY']=='BN'){
            $Statement = $this->db->prepare("SELECT * FROM rxnrel WHERE RXCUI1=:rxcui AND RELA='has_tradename'");
            $Statement->execute([':rxcui' => $RxNormRecord['RXCUI']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            // Extract the ingredient
            $Statement = $this->db->prepare("SELECT * FROM rxnconso WHERE RXCUI=:rxcui AND SAB='RXNORM'");
            $Statement->execute([':rxcui' => $Record['RXCUI2']]);
            $Record = $Statement->fetchAll(PDO::FETCH_ASSOC);

            return $Record;
        }
    }

	public function getDoseformAbbreviateByCODE($CODE) {
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DDFA'
		                      AND SAB    = 'RXNORM'");
		$sth->execute([':c' => $CODE]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDatabaseShortNameByCODE($CODE) {
		$sth = $this->db->prepare("SELECT SAB
		                     FROM rxnsat
		                    WHERE `CODE` = :c
                              AND SAB    = 'RXNORM'");
		$sth->execute([':c' => $CODE]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['SAB'];
	}

	public function getMedicationNameByRXCUI($RXCUI) {
		$sth = $this->db->prepare("SELECT STR
		                     FROM rxnconso
		                    WHERE RXCUI = :c
		                 GROUP BY RXCUI");
		$sth->execute([':c' => $RXCUI]);
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['STR'];
	}

	public function getRXNORMLiveSearch(stdClass $params)
    {
		$sth = $this->db->prepare("SELECT `rxnconso`.*, `rxnsat`.`ATV` AS NDC
									 FROM `rxnconso`
							   INNER JOIN `rxnsat`
								       ON `rxnconso`.`RXCUI` = `rxnsat`.`RXCUI`
								      AND `rxnconso`.`SAB` = 'RXNORM'
								      AND `rxnsat`.`ATN` = 'NDC'
								      AND (`rxnconso`.`TTY` = 'SCD' OR `rxnconso`.`TTY` = 'SBD')
								    WHERE (`rxnconso`.`STR` LIKE :q1 OR `rxnconso`.`RXCUI` = :q2)
							     GROUP BY `rxnconso`.`STR`
     							    LIMIT 100");
		$sth->execute([ ':q1' => '%'.$params->query.'%', ':q2' => $params->query ]);
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

        // Look for the GS Code
        foreach($records as $key => $record)
        {
            $sth = $this->db->prepare("SELECT * FROM rxnconso WHERE SAB='GS' AND RXCUI='".$record['CODE']."'");
            $sth->execute();
            $records[$key]['GS_CODE']  = $sth->fetch(PDO::FETCH_ASSOC)['CODE'];
        }

		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return [
			'totals' => $total,
			'rows' => $records
		];
	}

	public function getRXNORMList(stdClass $params) {
		if(isset($params->query)){
			$sth = $this->db->prepare("SELECT * FROM rxnconso
                                        WHERE (SAB = 'RXNORM' AND TTY = 'BD')
                                        AND STR LIKE :q
                                        GROUP BY RXCUI LIMIT 500");
			$sth->execute([':q' => $params->query . '%']);
		} else {
			$sth = $this->db->prepare("SELECT * FROM rxnconso
                                        WHERE (SAB = 'RXNORM'
                                        AND TTY = 'BD')
                                        GROUP BY RXCUI LIMIT 500");
			$sth->execute();
		}
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return [
			'totals' => $total,
			'data' => $records
		];
	}

	/**
	 * getRXNORMAllergyLiveSearch
	 * Method called from the GaiaEHR Web Client, to look for a Medication
	 * that cause allergy to the patient. The search can be either by RxCUI Code or by Medication Name.
	 * This includes Ingridient Name, Brand Name
	 * @param stdClass $params
	 * @return array
	 */
	public function getRXNORMAllergyLiveSearch(stdClass $params) {
		$sth = $this->db->prepare("SELECT *
								 	 FROM rxnconso
									WHERE SAB='RXNORM'
									AND (TTY = 'IN' OR TTY = 'PIN' OR TTY = 'BN')
									AND RXCUI LIKE :q1
									OR STR LIKE :q2
							 	 GROUP BY RXCUI LIMIT 100");
		$sth->execute([
			':q2' => '%'.$params->query.'%',
			':q1' => $params->query.'%'
		]);
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return [
			'totals' => $total,
			'rows' => $records
		];
	}

	public function getMedicationAttributesByRxcui($rxcui) {
		$response = [];

		$sth = $this->db->prepare("SELECT `ATV`, `ATN`
 								   FROM rxnsat
								  WHERE `RXCUI` = :c
								    AND `ATN` = 'RXN_AVAILABLE_STRENGTH'
								    AND `SAB` = 'RXNORM'");
		$sth->execute([':c' => $rxcui]);
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		if($result !== false){
			$response[$result['ATN']] = $result['ATV'];
		}

		$sth = $this->db->prepare("SELECT `rxnconso`.*
								     FROM `rxnrel`
						        LEFT JOIN `rxnconso` ON `rxnconso`.`RXCUI` = `rxnrel`.`RXCUI2`
								    WHERE `rxnrel`.`RXCUI1` = :c
								      AND `rxnrel`.`RELA` = 'dose_form_of'");

		$sth->execute([':c' => $rxcui]);
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function IndexActiveIngredients() {
		$this->db->exec('TRUNCATE TABLE rxnconsoindex');
		$sth = $this->db->prepare("SELECT id, STR FROM rxnconso WHERE TTY = 'IN' AND SAB = 'RXNORM' GROUP BY RXCUI");
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records As $record){
			$this->db->exec("INSERT INTO `rxnconsoindex` (`rxnid`, `STR`) VALUES ('{$record['id']}', '{$record['STR']}')");
		}
	}

	public function getMedicationInstructions($params) {
		$this->setInstructionModel();
		return $this->i->load($params)->all();
	}

	public function getMedicationInstruction($params) {
		$this->setInstructionModel();
		return $this->i->load($params)->one();
	}

	public function addMedicationInstruction($params) {
		$this->setInstructionModel();
		return $this->i->save($params);
	}

	public function updateMedicationInstructions($params) {
		$this->setInstructionModel();
		return $this->i->save($params);
	}

	public function destroyMedicationInstructions($params) {
		$this->setInstructionModel();
		return $this->i->save($params);
	}
}
