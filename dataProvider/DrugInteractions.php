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

// Set an Autoloader for PHPHtmlParser, without this the classes will never get loaded as needed.
spl_autoload_register(function($className)
{
    $baseDir = '../lib';
    $fileName = $baseDir . '/' . str_replace('\\', '/', $className) . '.php';
    if (stream_resolve_include_path($fileName)) {
        include $fileName;
        return true;
    }
    echo 'Fail to load: '. $fileName;
    return false;
});

use PHPHtmlParser\Dom;

class DrugInteractions
{

    /**
     * This drugs are drugs that the website service automatically check for
     * interactions, we don't want them, we just want the interactions with other
     * ingredients.
     * @var array
     */
    var $typicalIngredients = [
        'Food',
        'Enteral Feedings',
        'Alcohol',
        'Tobacco',
        'Grapefruit juice',
        'Ethanol',
        'Caffeine',
        'Marijuana'
    ];

    var $Medications;

    /**
     * This are the web service, that brings back the results of a drug interaction.
     * @var string
     */
    private $interactionUrl = 'http://rochestergen.staywellsolutionsonline.com/DrugInteraction/InteractionReport.pg';

    function __construct()
    {
        if(!isset($this->DrugInteractions))
            $this->Medications = MatchaModel::setSenchaModel('App.model.patient.Medications');

        return;
    }

    /**
     * Method: getDrugInteractions
     * @param stdClass $params
     * @return array
     */
    public function getDrugInteractions(stdClass $params)
    {
        // Load all the active medication of the patient.
        // If the medication is not active, do not check for interactions
        $agregateFilter = new stdClass();
        $agregateFilter->property = 'end_date';
        $agregateFilter->operator = '=';
        $agregateFilter->value = null;
        $params->filter[] = $agregateFilter;
        $Medications = $this->Medications->load($params)->all();
        $getQueryString = '';

        // Build up the query string of active medications
        foreach($Medications as $Medication)
        {
            $getQueryString = $Medication['GS_CODE'] . ':';
        }
        $requestValues = [
            'drugproductids' => $getQueryString
        ];
        $result = file_get_contents(
            $this->interactionUrl.'?'.http_build_query($requestValues),
            false
        );

        // It was an error on the internet connection or on the request.
        if ($result === FALSE) return false;

        // Search for a "No content found" string, if it is found means that
        // no drug interaction is matched
        $foundOn = strpos($result, "No content found");
        if($foundOn) return false;

        // Search for a "No significant drug" string, if it is found means that
        // no drug interaction is matched
        $foundOn = strpos($result, "No significant drug interactions were found.");
        if($foundOn) return false;

        $dom = new Dom;
        $parsed = $dom->loadStr($result, [
            'cleanupInput' => false,
            'removeScripts' => true
        ]);
        $serviceInteractions = $parsed->find('div.item');

        foreach($serviceInteractions as $serviceInteraction)
        {
            $title = strip_tags($serviceInteraction->find('p.InteractionTitle', 0)->outerHtml);
            $saveIt = true;
            foreach($this->typicalIngredients as $typicalIngredient)
            {
                if(stristr($title, $typicalIngredient)) $saveIt = false;
            }
            if($saveIt)
            {

                $drugs = explode(' and ', $title);
                $interaction['drug_1'] = $drugs[0];
                $interaction['drug_2'] = $drugs[1];
                $interaction['severity'] = str_replace(
                    'Severity: ',
                    '',
                    strip_tags($serviceInteraction->find('p.InteractionSeverity', 0)->outerHtml)
                );
                $interaction['interaction_description'] = strip_tags($serviceInteraction->find('p.InteractionText', 0)->outerHtml);
                $interactions[] = $interaction;
            }
        }
        return [
            'totals' => count($interactions),
            'data' => $interactions
        ];
    }

    /**
     * Internal function to get the patient medication by it's ID
     */
    private function __getMedicationByPatientId($patientId)
    {

    }

}
