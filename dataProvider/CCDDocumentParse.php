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
include_once(ROOT . '/classes/Array2XML.php');
include_once(ROOT . '/classes/XML2Array.php');
include_once(ROOT . '/dataProvider/SnomedCodes.php');

class CCDDocumentParse {

	private $document;

	private $index;

	public $styledXml;

	/**
	 * @var SnomedCodes
	 */
	private $SnomedCodes;

	function __construct($xml = null) {
		if(isset($xml))
			$this->setDocument($xml);
	}

	function setDocument($xml) {
		$this->document = $this->XmlToArray($xml);
		unset($this->document['ClinicalDocument']['@attributes']);

		Array2XML::init('1.0', 'UTF-8', true, ['xml-stylesheet' => 'type="text/xsl" href="' . URL . '/lib/CCRCDA/schema/cda2.xsl"']);

		$data = [
			'@attributes' => [
				'xmlns' => 'urn:hl7-org:v3',
				'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
				'xsi:schemaLocation' => 'urn:hl7-org:v3 CDA.xsd'
			]
		];
		foreach($this->document['ClinicalDocument'] as $i => $com){
			$data[$i] = $com;
		}

		$this->styledXml = Array2XML::createXML('ClinicalDocument', $data)->saveXML();
		unset($data);

		$this->index = [];
		foreach($this->document['ClinicalDocument']['component']['structuredBody']['component'] as $index => $component){
			$code = isset($component['section']['code']['@attributes']['code']) ? $component['section']['code']['@attributes']['code'] : '';

			//Advance Directives ???
			if($code == '48765-2'){
				$this->index['allergies'] = $index;
			} elseif($code == '10160-0') {
				$this->index['medications'] = $index;
			} elseif($code == '11450-4') {
				$this->index['problems'] = $index;
			} elseif($code == '47519-4') {
				$this->index['procedures'] = $index;
			} elseif($code == '30954-2') {
				$this->index['results'] = $index;
			} elseif($code == '46240-8') {
				$this->index['encounters'] = $index;
			} elseif($code == '51847-2') {
				$this->index['assessments'] = $index;
			} elseif($code == '46239-0') {
				$this->index['chiefcomplaint'] = $index;
			} else {

				$tplId = isset($component['section']['templateId']['@attributes']['root']) ? $component['section']['templateId']['@attributes']['root'] : '';

				if($tplId == '2.16.840.1.113883.10.20.22.2.21.1'){
					$this->index['advancedirectives'] = $index;
				}
			}
		}
	}

	function getDocument() {
		$document = new stdClass();
		$document->title = $this->getTitle();
		$document->patient = $this->getPatient();
		$document->encounter = $this->getEncounter();
		$document->author = $this->getAuthor();
		$document->allergies = $this->getAllergies();
		$document->medications = $this->getMedications();
		$document->problems = $this->getProblems();
		$document->procedures = $this->getProcedures();
		$document->results = $this->getResults();
		$document->encounters = $this->getEncounters();
		$document->advancedirectives = $this->getAdvanceDirectives();
		return $document;
	}

	function getTitle() {
		return isset($this->document['ClinicalDocument']['title']) ? $this->document['ClinicalDocument']['title'] : '';
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	function getPatient() {
		$dom = $this->document['ClinicalDocument']['recordTarget']['patientRole'];
		$patient = new stdClass();

		// IDs
		if($this->isAssoc($dom['id'])){
			$patient->pubpid = $dom['id']['@attributes']['extension'];
		} else {
			$foo = [];
			foreach($dom['id'] as $id){
				$foo[] = $id['@attributes']['extension'];
			}
			$patient->pubpid = implode('~', $foo);
			unset($foo);
		}

		// address
		$a = isset($dom['addr']) ? $dom['addr'] : [];
		$patient->address = isset($a['streetAddressLine']) ? $a['streetAddressLine'] : '';
		$patient->city = isset($a['city']) ? $a['city'] : '';
		$patient->state = isset($a['state']) ? $a['state'] : '';
		$patient->zipcode = isset($a['postalCode']) ? $a['postalCode'] : '';
		$patient->country = isset($a['country']) ? $a['country'] : '';
		unset($a);

		// phones
		if(isset($dom['telecom'])){
			$telecoms = $this->telecomHandler($dom['telecom']);
			foreach($telecoms as $type => $telecom){
				if($type == 'WP'){
					$patient->work_phone = $telecom;
				} else {
					$patient->home_phone = $telecom;
				}
			}
		}

		if(!isset($dom['patient'])){
			throw new Exception('Error: ClinicalDocument->recordTarget->patientRole->Patient is required');
		}
		//names
		if(!isset($dom['patient']['name']['given'])){
			throw new Exception('Error: Patient given name is required');
		}
		if(!isset($dom['patient']['name']['family'])){
			throw new Exception('Error: Patient family name is required');
		}
		$names = $this->nameHandler($dom['patient']['name']);
		$patient->fname = $names['fname'];
		$patient->mname = $names['mname'];
		$patient->lname = $names['lname'];
		//gender
		if(!isset($dom['patient']['administrativeGenderCode'])){
			throw new Exception('Error: Patient gender is required');
		}
		$patient->sex = $dom['patient']['administrativeGenderCode']['@attributes']['code'];
		//DOB
		$patient->DOB = $this->dateParser($dom['patient']['birthTime']['@attributes']['value']);
		// fix for date with only the day...  add the time at the end
		if(strlen($patient->DOB) <= 10)
			$patient->DOB .= ' 00:00:00';

		//marital StatusCode
		$patient->marital_status = isset($dom['patient']['maritalStatusCode']['@attributes']['code']) ? $dom['patient']['maritalStatusCode']['@attributes']['code'] : '';
		//race
		$patient->race = isset($dom['patient']['raceCode']['@attributes']['code']) ? $dom['patient']['raceCode']['@attributes']['code'] : '';
		//ethnicGroupCode
		$patient->ethnicity = isset($dom['patient']['ethnicGroupCode']['@attributes']['code']) ? $dom['patient']['ethnicGroupCode']['@attributes']['code'] : '';
		//birthplace
		if(isset($dom['patient']['birthplace']['place']['addr'])){
			$addr = $dom['patient']['birthplace']['place']['addr'];
			$foo = '';

			if(isset($addr['city'])){
				$foo .= is_string($addr['city']) ? $addr['city'] : '';
			}
			if(isset($addr['state'])){
				$foo .= is_string($addr['state']) ? ' ' . $addr['state'] : '';
			}
			if(isset($addr['country'])){
				$foo .= is_string($addr['country']) ? ' ' . $addr['country'] : '';
			}

			$patient->birth_place = trim($foo);
		} else {
			$patient->birth_place = '';
		}

		//languageCommunication
		$patient->language = isset($dom['patient']['languageCommunication']['languageCode']['@attributes']['code']) ? $dom['patient']['languageCommunication']['languageCode']['@attributes']['code'] : '';

		//religious  not implemented
		//$patient->religion = '';

		//guardian
		if(isset($dom['patient']['guardian'])){
			// do a bit more...
			// lets just save the name for now
			if($dom['patient']['guardian']['guardianPerson']){
				$name = isset($dom['patient']['guardian']['guardianPerson']['name']['given']) ? $dom['patient']['guardian']['guardianPerson']['name']['given'] : '';
				$name .= isset($dom['patient']['guardian']['guardianPerson']['name']['family']) ? ' ' . $dom['patient']['guardian']['guardianPerson']['name']['family'] : '';
				$patient->guardians_name = trim($name);
			}
		}
		unset($dom);
		return $patient;

	}

	/**
	 * @return stdClass
	 */
	function getAuthor() {
		$dom = $this->document['ClinicalDocument']['author'];
		$author = new stdClass();

		if(isset($dom['assignedAuthor'])){
			$author->id = $dom['assignedAuthor']['id']['@attributes']['extension'];

			if(isset($dom['assignedAuthor']['assignedPerson']['name'])){
				$names = $this->nameHandler($dom['assignedAuthor']['assignedPerson']['name']);
				$author->fname = $names['fname'];
				$author->mname = $names['mname'];
				$author->lname = $names['lname'];
			}

			if(isset($dom['assignedAuthor']['addr'])){
				$author->address = isset($dom['assignedAuthor']['addr']['streetAddressLine']) ? $dom['assignedAuthor']['addr']['streetAddressLine'] : '';
				$author->city = isset($dom['assignedAuthor']['addr']['city']) ? $dom['assignedAuthor']['addr']['city'] : '';
				$author->state = isset($dom['assignedAuthor']['addr']['state']) ? $dom['assignedAuthor']['addr']['state'] : '';
				$author->zipcode = isset($dom['assignedAuthor']['addr']['postalCode']) ? $dom['assignedAuthor']['addr']['postalCode'] : '';
				$author->country = isset($dom['assignedAuthor']['addr']['country']) ? $dom['assignedAuthor']['addr']['country'] : '';
			}
			if(isset($dom['assignedAuthor']['telecom']) && $dom['assignedAuthor']['telecom'] !== ''){
				$telecoms = $this->telecomHandler($dom['assignedAuthor']['telecom']);
				foreach($telecoms as $type => $telecom){
					if($type == 'WP'){
						$author->work_phone = $telecom;
					} else {
						$author->home_phone = $telecom;
					}
				}
			}
		}

		return $author;
	}

	function getEncounter() {
		$encounter = new stdClass();

		if(!isset($this->document['ClinicalDocument']['componentOf'])){
			return $encounter;
		}

		$dom = $this->document['ClinicalDocument']['componentOf'];
		if(isset($dom['encompassingEncounter'])){
			$encounter->rid = $dom['encompassingEncounter']['id']['@attributes']['extension'];
			$times = $this->datesHandler($dom['encompassingEncounter']['effectiveTime']);
			$encounter->service_date = $times['low'];
			unset($times);
		}

		if(isset($this->index['chiefcomplaint'])){
			$cc = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['chiefcomplaint']]['section'];
			$encounter->brief_description = $cc['text']['paragraph']['@value'];
		}

		if(isset($this->index['assessments'])){
			$assessments = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['assessments']]['section'];
			if($this->isAssoc($assessments['entry']))
				$section['entry'] = [$assessments['entry']];

			$encounter->assessments = [];

			foreach($assessments['entry'] as $i => $entry){
				if(isset($entry['act'])){
					$assessment = new stdClass();
					$assessment->text = $assessments['text']['paragraph'][$i]['@value'];
					$code = $this->codeHandler($entry['act']['code']);
					$assessment->code = $code['code'];
					$assessment->code_text = $code['code_text'];
					$assessment->code_type = $code['code_type'];
					$encounter->assessments[] = $assessment;
				}
			}
		}

		return $encounter;
	}

	/**
	 * @return array
	 */
	function getAllergies() {
		$allergies = [];

		if(!isset($this->index['allergies'])){
			return $allergies;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['allergies']]['section'];

		if(!isset($section['entry'])){
			return $allergies;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];
		foreach($section['entry'] as $entry){

			$allergy = new stdClass();

			// allergy type
			$code = $this->codeHandler($entry['act']['entryRelationship']['observation']['value']['@attributes']);
			$allergy->allergy_type = $code['code_text'];
			$allergy->allergy_type_code = $code['code'];
			$allergy->allergy_type_code_type = $code['code_type'];
			unset($code);

			// allergy
			$code = $this->codeHandler($entry['act']['entryRelationship']['observation']['participant']['participantRole']['playingEntity']['code']['@attributes']);
			$allergy->allergy = $code['code_text'];
			$allergy->allergy_code = $code['code'];
			$allergy->allergy_code_type = $code['code_type'];
			unset($code);

			//dates
			if(isset($entry['act']['effectiveTime'])){
				$dates = $this->datesHandler($entry['act']['effectiveTime'], true);
				$allergy->begin_date = $dates['low'];
				$allergy->end_date = $dates['high'];
			}

			// reaction, severity, status
			foreach($entry['act']['entryRelationship']['observation']['entryRelationship'] as $obs){
				$key = null;
				switch($obs['observation']['templateId']['@attributes']['root']) {
					case '2.16.840.1.113883.10.20.22.4.28':
						$key = 'status';
						break;
					case '2.16.840.1.113883.10.20.22.4.9':
						$key = 'reaction';
						break;
					case '2.16.840.1.113883.10.20.22.4.8':
						$key = 'severity';
						break;
				}

				if(isset($key)){
					$code = $this->codeHandler($obs['observation']['value']['@attributes']);
					$allergy->{$key} = $code['code_text'];
					$allergy->{$key . '_code'} = $code['code'];
					$allergy->{$key . '_code_type'} = $code['code_type'];
					unset($code);
				};
			}

			$allergies[] = $allergy;
		}

		return $allergies;

	}

	/**
	 * @return array
	 */
	function getMedications() {
		$medications = [];

		if(!isset($this->index['medications'])){
			return $medications;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['medications']]['section'];

		if(!isset($section['entry'])){
			return $medications;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];
		foreach($section['entry'] as $entry){

			$medication = new stdClass();

			if(!$this->isAssoc($entry['substanceAdministration']['effectiveTime'])){
				foreach($entry['substanceAdministration']['effectiveTime'] as $date){
					if(!isset($date['low']))
						continue;
					$dates = $this->datesHandler($date, true);
				}
			} else {
				$dates = $this->datesHandler($entry['substanceAdministration']['effectiveTime'], true);
			}

			if(isset($dates)){
				$medication->begin_date = $dates['low'];
				$medication->end_date = $dates['high'];
			}

			if($entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturedMaterial']){
				$code = $this->codeHandler($entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturedMaterial']['code']['@attributes']);
				$medication->RXCUI = $code['code'];
				$medication->STR = $code['code_text'];
				unset($code);
			}

			//			if(isset($entry['substanceAdministration']['doseQuantity']['@attributes']['value'])){
			//				$medication->dose = $entry['substanceAdministration']['doseQuantity']['@attributes']['value'];
			//			}
			//
			//			if(isset($entry['substanceAdministration']['rateQuantity']['@attributes']['value'])){
			//				$medication->dispense = '';
			//			}
			//			$medication->form = '';
			//			$medication->route = '';
			//			$medication->dispense = '';
			//			$medication->refill = '';
			//			$medication->directions = '';
			$medications[] = $medication;
		}

		return $medications;
	}

	/**
	 * @return array
	 */
	function getProblems() {
		$problems = [];

		if(!isset($this->index['problems'])){
			return $problems;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['problems']]['section'];

		if(!isset($section['entry'])){
			return $problems;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];
		foreach($section['entry'] as $entry){
			$problem = new stdClass();

			$code = $this->codeHandler($entry['act']['entryRelationship']['observation']['value']);
			$problem->code = $code['code'];
			$problem->code_text = $code['code_text'];
			$problem->code_type = $code['code_type'];
			unset($code);

			$dates = $this->datesHandler($entry['act']['effectiveTime'], true);
			$problem->begin_date = $dates['low'];
			$problem->end_date = $dates['high'];
			unset($dates);

			foreach($entry['act']['entryRelationship']['observation']['entryRelationship'] as $obs){
				// status template

				if(!isset($obs['observation']))
					continue;
				if(!isset($obs['observation']['templateId']))
					continue;

				if($obs['observation']['templateId']['@attributes']['root'] == '2.16.840.1.113883.10.20.22.4.6'){
					$code = $this->codeHandler($obs['observation']['value']['@attributes']);
					$problem->status = $code['code_text'];
					$problem->status_code = $code['code'];
					$problem->status_code_type = $code['code_type'];
				}

			}
			$problems[] = $problem;
		}

		return $problems;
	}

	/**
	 * @return array
	 */
	function getProcedures() {
		$procedures = [];

		if(!isset($this->index['procedures'])){
			return $procedures;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['procedures']]['section'];

		if(!isset($section['entry'])){
			return $procedures;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];

		foreach($section['entry'] as $entry){
			$procedure = new stdClass();

			if(isset($entry['procedure']['code'])){
				// procedure
				$code = $this->codeHandler($entry['procedure']['code']);
				if($code['code'] == '')
					continue;

				$procedure->code = $code['code'];
				$procedure->code_text = $code['code_text'];
				$procedure->code_type = $code['code_type'];

				//dates
				$dates = $this->datesHandler($entry['procedure']['effectiveTime']);
				$procedure->procedure_date = $dates['low'];
				$procedures[] = $procedure;
			}

		}
		return $procedures;
	}

	/**
	 * @return array
	 */
	function getResults() {
		$results = [];

		if(!isset($this->index['results'])){
			return $results;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['results']]['section'];

		if(!isset($section['entry'])){
			return $results;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];

		foreach($section['entry'] as $entry){
			$result = new stdClass();

			$code = $this->codeHandler($entry['organizer']['code']);
			$result->code = $code['code'];
			$result->code_text = $code['code_text'];
			$result->code_type = $code['code_type'];
			unset($code);

			$result_date = '0000-00-00';

			$result->observations = [];

			if($this->isAssoc($entry['organizer']['component']))
				$entry['organizer']['component'] = [$entry['organizer']['component']];

			foreach($entry['organizer']['component'] as $obs){

				if(isset($obs['observation'])){
					$obs = $obs['observation'];

					$observation = new stdClass();
					$code = $this->codeHandler($obs['code']);
					$observation->code = $code['code'];
					$observation->code_text = $code['code_text'];
					$observation->code_type = $code['code_type'];
					unset($code);

					$observation->value = isset($obs['value']['@attributes']['value']) ? $obs['value']['@attributes']['value'] : '';
					$observation->units = isset($obs['value']['@attributes']['unit']) ? $obs['value']['@attributes']['unit'] : '';

					if(isset($obs['referenceRange'])){
						if(isset($obs['referenceRange']['observationRange']['text'])){
							$observation->reference_rage = $obs['referenceRange']['observationRange']['text'];
						} else {
							if(isset($obs['referenceRange']['observationRange']['value']['low'])){
								$observation->reference_rage = $obs['referenceRange']['observationRange']['value']['low']['@attributes']['value'];
							}
							if(isset($obs['referenceRange']['observationRange']['value']['high'])){
								$observation->reference_rage .= ' - ' . $obs['referenceRange']['observationRange']['value']['high']['@attributes']['value'];
							}
							$observation->reference_rage .= ' ' . $observation->units;
						}
					}

					$dates = $this->datesHandler($obs['effectiveTime']);
					$observation->date_analysis = $dates['low'];

					if(isset($obs['interpretationCode'])){
						$observation->abnormal_flag = $obs['interpretationCode']['@attributes']['code'];
					}

					$observation->observation_result_status = $obs['statusCode']['@attributes']['code'];
					$dates = $this->datesHandler($obs['effectiveTime']);
					$observation->date_observation = $result_date = $dates['low'];

					$result->observations[] = $observation;


				}elseif(isset($obs['procedure'])){

					//TODO


				}


			}

			$result->result_date = $result_date;

			$results[] = $result;
		}

		return $results;
	}

	/**
	 * @return array
	 */
	function getEncounters() {
		$encounters = [];

		if(!isset($this->index['encounters'])){
			return $encounters;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['encounters']]['section'];

		if(!isset($section['entry'])){
			return $encounters;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];

		foreach($section['entry'] as $entry){

			if(!isset($entry['encounter']['entryRelationship']))
				continue;

			$encounter = new stdClass();

			$dates = $this->datesHandler($entry['encounter']['effectiveTime']);
			$encounter->service_date = $dates['low'];
			unset($dates);

			$code = $this->codeHandler($entry['encounter']['code']);
			$encounter->service_code = $code['code'];
			$encounter->service_code_text = $code['code_text'];
			$encounter->service_code_type = $code['code_type'];
			unset($code);

			$encounter->observations = [];

			if($this->isAssoc($entry['encounter']['entryRelationship'])){
				$entry['encounter']['entryRelationship'] = [$entry['encounter']['entryRelationship']];
			};
			// for each observations
			foreach($entry['encounter']['entryRelationship'] as $obs){

				if(isset($obs['observation'])){
					$obs = $obs['observation'];
				} elseif(isset($obs['act'])) {
					$obs = $obs['act']['entryRelationship']['observation'];
				}

				$observation = new stdClass();

				$code = $this->codeHandler($obs['code']);
				$observation->code = $code['code'];
				$observation->code_text = $code['code_text'];
				$observation->code_type = $code['code_type'];
				unset($code);

				$code = $this->codeHandler($obs['value']);
				$observation->value_code = $code['code'];
				$observation->value_code_text = $code['code_text'];
				$observation->value_code_type = $code['code_type'];
				unset($code);

				$encounter->observations[] = $observation;

			}

			$encounters[] = $encounter;
		}

		return $encounters;
	}

	/**
	 * @return array
	 */
	function getAdvanceDirectives() {
		$directives = [];

		if(!isset($this->index['advancedirectives'])){
			return $directives;
		}

		$section = $this->document['ClinicalDocument']['component']['structuredBody']['component'][$this->index['advancedirectives']]['section'];

		if(!isset($section['entry'])){
			return $directives;
		}

		if($this->isAssoc($section['entry']))
			$section['entry'] = [$section['entry']];

		foreach($section['entry'] as $entry){
			$directive = new stdClass();

			$code = $this->codeHandler($entry['observation']['code']);
			$directive->code = $code['code'];
			$directive->code_text = $code['code_text'];
			$directive->code_type = $code['code_type'];
			unset($code);

			$code = $this->codeHandler($entry['observation']['value']);
			$directive->value_code = $code['code'];
			$directive->value_code_text = $code['code_text'];
			$directive->value_code_type = $code['code_type'];
			unset($code);

			$dates = $this->datesHandler($entry['observation']['effectiveTime'], true);
			$directive->begin_date = $dates['low'];
			$directive->end_date = $dates['high'];

			if(isset($entry['observation']['participant'])){
				if($this->isAssoc($entry['observation']['participant'])){
					$entry['observation']['participant'] = [$entry['observation']['participant']];
				}

				$directive->contact = '';

				foreach($entry['observation']['participant'] as $participant){
					$participant = $participant['participantRole'];

					if(isset($participant['playingEntity']) && (isset($participant['addr']) || isset($participant['telecom']))){

						if(isset($participant['addr'])){
							$address = isset($participant['addr']['streetAddressLine']) ? $participant['addr']['streetAddressLine'] : '';
							$address .= isset($participant['addr']['city']) ? ' ' . $participant['addr']['city'] : '';
							$address .= isset($participant['addr']['state']) ? ', ' . $participant['addr']['state'] : '';
							$address .= isset($participant['addr']['postalCode']) ? ' ' . $participant['addr']['postalCode'] : '';
							$address .= isset($participant['addr']['country']) ? ' ' . $participant['addr']['country'] : '';
						}

						$tel = isset($participant['telecom']) ? $participant['telecom']['@attributes']['value'] : '';

						$name = $this->nameHandler($participant['playingEntity']['name']);
						$directive->contact = $name['prefix'] . ' ' . $name['lname'] . ' ' . $name['fname'] . $name['mname'] . ' ~ ' . $tel . $address;

					}

				}
			}

			$directives[] = $directive;
		}

		return $directives;
	}

	/**
	 * @param $array
	 * @return string
	 */
	function ArrayToJson($array) {
		return json_encode($array);
	}

	/**
	 * @param $xml
	 * @return string
	 */
	function XmlToJson($xml) {
		return $this->ArrayToJson($this->XmlToArray($xml));
	}

	/**
	 * @param $xml
	 * @return DOMDocument
	 */
	function XmlToArray($xml) {
		return XML2Array::createArray($xml);
	}

	/**
	 * @param $telecoms
	 * @return array
	 */
	function telecomHandler($telecoms) {
		$telecoms = !$this->isAssoc($telecoms) ? $telecoms : [$telecoms];
		$results = [];
		foreach($telecoms as $telecom){
			$use = isset($telecom['@attributes']['use']) && $telecom['@attributes']['use'] != '' ? $telecom['@attributes']['use'] : 'HP';
			$results[$use] = isset($telecom['@attributes']['value']) ? $this->parsePhone($telecom['@attributes']['value']) : '';
		}
		return $results;
	}

	/**
	 * @param $name
	 * @return array
	 */
	function nameHandler($name) {
		$results = [];

		$results['prefix'] = isset($name['prefix']) && is_string($name['prefix']) ? $name['prefix'] : '';

		if(is_array($name['given'])){
			$results['fname'] = isset($name['given'][0]) ? $name['given'][0] : '';
			if(!isset($name['given'][1])){
				$results['mname'] = '';
			} elseif(is_string($name['given'][1])) {
				$results['mname'] = isset($name['given'][1]) ? $name['given'][1] : '';
			} elseif(is_array($name['given'][1])) {
				$results['mname'] = isset($name['given'][1]['@value']) ? $name['given'][1]['@value'] : '';
			}
		} else {
			$results['fname'] = isset($name['given']) ? $name['given'] : '';
			$results['mname'] = '';
		}

		$results['lname'] = isset($name['family']) ? $name['family'] : '';
		return $results;
	}

	/**
	 * @param $dates
	 * @param $justDate
	 * @return array
	 */
	function datesHandler($dates, $justDate = false) {
		$result = [
			'low' => '0000-00-00',
			'high' => '0000-00-00'
		];

		if(is_string($dates)){
			$result['low'] = $this->dateParser($dates);
		} else {
			if(isset($dates['@value'])){
				$result['low'] = $this->dateHandler($dates);
			} else {
				if(isset($dates['low'])){
					$result['low'] = $this->dateHandler($dates['low']);
				}
				if(isset($dates['high'])){
					$result['high'] = $this->dateHandler($dates['high']);
				}
			}
		}

		if($justDate){
			$result['low'] = substr($result['low'], 0, 10);
			$result['high'] = substr($result['high'], 0, 10);
		}

		return $result;
	}

	/**
	 * @param $date
	 * @return mixed|string
	 */
	function dateHandler($date) {
		$result = '0000-00-00';
		if(is_string($date)){
			$result = $this->dateParser($date);
		} elseif(isset($date['@attributes']['value'])) {
			$result = $this->dateParser($date['@attributes']['value']);
		}
		return $result;
	}

	/**
	 * @param $code
	 * @return array
	 */
	function codeHandler($code) {
		if(isset($code['@attributes'])){
			return $this->codeHandler($code['@attributes']);
		}
		$result = [];
		$result['code'] = isset($code['code']) ? $code['code'] : '';
		$result['code_type'] = isset($code['codeSystem']) ? $this->getCodeSystemName($code['codeSystem']) : '';
		$result['code_text'] = isset($code['displayName']) ? $code['displayName'] : '';

		if($result['code_text'] == ''){

			if($result['code_type'] == 'SNOMEDCT'){

				if(!isset($this->SnomedCodes)){
					$this->SnomedCodes = new SnomedCodes();
				}
				$text = $this->SnomedCodes->getSnomedTextByConceptId($result['code']);
				$result['code_text'] = $text;

			} elseif($result['code_type'] == 'LOINC') {

				//TODO

			}

		}

		return $result;
	}

	/**
	 * @param $date
	 * @return mixed|string
	 */
	function dateParser($date) {
		$result = '0000-00-00';
		switch(strlen($date)) {
			case 4:
				$result = $date . '-00-00';
				break;
			case 6:
				$result = preg_replace('/^(\d{4})(\d{2})/', '$1-$2-00', $date);
				break;
			case 8:
				$result = preg_replace('/^(\d{4})(\d{2})(\d{2})$/', '$1-$2-$3', $date);
				break;
			case 10:
				$result = preg_replace('/^(\d{4})(\d{2})(\d{2})(\d{2})$/', '$1-$2-$3 $4:00:00', $date);
				break;
			case 12:
				$result = preg_replace('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1-$2-$3 $4:$5:00', $date);
				break;
			case 14:
				$result = preg_replace('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1-$2-$3 $4:$5:$6', $date);
				break;
		}

		return $result;
	}

	/**
	 * @param $phone
	 * @return mixed
	 */
	function parsePhone($phone) {
		return preg_replace('/tel:/', '', $phone);
	}

	/**
	 * @param $arr
	 * @return bool
	 */
	function isAssoc($arr) {
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * @param $code
	 * @return string
	 */
	function getCodeSystemName($code) {
		$code = str_replace('.', '', $code);
		$codes = [
			'2168401113883612' => 'CPT4',
			'2168401113883642' => 'ICD9',
			'21684011138836103' => 'ICD9CM',
			'216840111388363' => 'ICD10',
			'216840111388361' => 'LOINC',
			'216840111388366' => 'NDC',
			'2168401113883688' => 'RXNORM',
			'2168401113883696' => 'SNOMEDCT',
			'216840111388346' => 'NPI',
			'216840111388349' => 'UNII',
			'216840111388332611' => 'NCI'
		];

		return isset($codes[$code]) ? $codes[$code] : 'UNK';

	}

	function getTestCCD($file) {

		$ccd = file_get_contents(ROOT . '/dataProvider/CCDs/' . $file);
		$this->setDocument($ccd);
		return ['ccd' => $this->getDocument()];
	}
}

//print '<pre>';
//$xml = file_get_contents('ccd3.xml');
//$x = new CCDDocumentParse($xml);
////$x->getAdvanceDirectives();
//print_r($x->getDocument());


