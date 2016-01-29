<?php

/**
 * 1.14 US Realm Header (V2)
 *
 * This template defines constraints that represent common administrative and demographic concepts for US Realm
 * CDA documents. Further specification, such as documentCode, are provided in document templates that
 * conform to this template.
 *
 */

namespace LevelDocument;

use Utilities;
use Component;
use Exception;

/**
 * Class RealmHeader_US
 * @package LevelDocument
 */
class RealmHeader_US
{
    /**
     * @param $Data
     * @throws Exception
     */
    private static function Validate($Data)
    {
        if(!isset($Data['ClinicalDocument']))
            throw new Exception('1.14 US Realm Header (V2) - Is not declared.');
        if(!isset($Data['ClinicalDocument']['code']))
            throw new Exception('ClinicalDocument SHALL contain code');
        if(!isset($Data['ClinicalDocument']['documentName']))
            throw new Exception('ClinicalDocument SHALL contain documentName');
        if(!isset($Data['ClinicalDocument']['codeSystemName']))
            throw new Exception('ClinicalDocument SHALL contain codeSystemName');
        if(!isset($Data['ClinicalDocument']['title']))
            throw new Exception('ClinicalDocument SHALL contain title');
        if(!isset($Data['ClinicalDocument']['effectiveDate']))
            throw new Exception('ClinicalDocument SHALL contain effectiveDate');
        if(!isset($Data['ClinicalDocument']['confidentiality']))
            throw new Exception('ClinicalDocument SHALL contain confidentialitys');
        if(!isset($Data['ClinicalDocument']['confidentiality']))
            throw new Exception('ClinicalDocument SHALL contain languageCode');
    }

    /**
     * @param $Data
     * @return array|Exception
     */
    public static function Insert($Data)
    {
        try {
            // Validate first
            self::Validate($Data);

            // Compose the document
            $Section = [
                'realmCode' => Component::realmCode($Data['ClinicalDocument']['realm']),
                'typeId' => Component::typeId('2.16.840.1.113883.1.3','POCD_HD000040'),
                'templateId' => Component::templateId('2.16.840.1.113883.10.20.22.1.1'),
                'id' => Component::id(Utilities::UUIDv4()),
                'code' => Component::code(
                    $Data['ClinicalDocument']['code'],
                    $Data['ClinicalDocument']['documentName'],
                    $Data['ClinicalDocument']['codeSystemName']
                ),
                'title' => Component::title($Data['ClinicalDocument']['title']),
                'effectiveTime' => Component::time($Data['ClinicalDocument']['effectiveDate']),
                'confidentialityCode' => Component::confidentialityCode($Data['ClinicalDocument']['confidentiality']),
                'languageCode' => Component::languageCode(
                    $Data['ClinicalDocument']['languageCode']
                ),
                'setId' => Component::setId('111199021', '2.16.840.1.113883.19'),
                'versionNumber' => Component::versionNumber('1')
            ];
            return $Section;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }
}
