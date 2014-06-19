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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class WebSearchCodes {

	private $codeSystem = array(
		'ICD10CM' => '2.16.840.1.113883.6.90',
		'ICD10-CM' => '2.16.840.1.113883.6.90',
		'ICD-10-CM' => '2.16.840.1.113883.6.90',
		'ICD9CM' => '2.16.840.1.113883.6.103',
		'ICD9-CM' => '2.16.840.1.113883.6.103',
		'ICD-9-CM' => '2.16.840.1.113883.6.103',
		'SNOMED' => '2.16.840.1.113883.6.96',
		'RXCUI' => '2.16.840.1.113883.6.88',
		'NDC' => '2.16.840.1.113883.6.69',
		'LOINC' => '2.16.840.1.113883.6.1'
	);

	private $medline = 'http://apps.nlm.nih.gov/medlineplus/services/mpconnect_service.cfm';
	private $responseType = 'application/json';
	private $language = 'en';

	public function Search($params) {
		$args = '?';
		if(isset($params->code)) $args .= 'mainSearchCriteria.v.c='.$params->code . '&';
		if(isset($params->codeType)) $args .= 'mainSearchCriteria.v.cs='.$this->codeSystem[$params->codeType] . '&';
//		if(isset($params->codeText)) $args .= 'mainSearchCriteria.v.dn='.$params->codeText . '&';
		$args .= 'knowledgeResponseType='.$this->responseType . '&';
		$args .= 'informationRecipient.languageCode.c='.$this->language;
		$response = @file_get_contents($this->medline . $args);

		return json_decode($response, true);
	}

}
//print '<pre>';
//$p = new Prescriptions();
//$params = new stdClass();
//$params->query = 't';
//print_r($p->getSigCodesByQuery($params));
