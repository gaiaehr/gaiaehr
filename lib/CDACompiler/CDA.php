<?php

require_once("Autoloader.php");

class CDA
{

    /**
     * @param array $Arguments
     * @return string|void
     * @throws Exception
     */
    public function Compile($Arguments = array())
    {
        // Check for the version argument, if not throw an exception
        if (!isset($Arguments['ClinicalDocument']['version']))
            throw new Exception('The CDA Version is not specified.', '001');

        // Check for the realm argument, if not throw an exception
        if (!isset($Arguments['ClinicalDocument']['realm']))
            throw new Exception('The CDA Realm is not specified.', '002');

        switch ($Arguments['ClinicalDocument']['version'])
        {
            case '1':
                return self::__CDA_v1($Arguments);
                break;
            case '2':
                return self::__CDA_v2($Arguments);
                break;
        }

    }

    /**
     * @param null $data
     * @param string $docType
     * @return string
     */
    private static function __CDA_v2($data = null, $docType = 'CCD')
    {

        // Set up the attributes of the XML
        $CDABuild = [];
        $CDABuild['@attributes'] = [
            'xmlns' => 'urn:hl7-org:v3',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
        ];

        switch($docType)
        {
            // Referral Note
            case 'RN':
                break;

            // Transfer Summary
            case 'TS':
                // Merge the Document Level
                $CDABuild = array_merge($CDABuild, LevelDocument\RealmHeader_US::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\recordTarget::Insert($data));
                break;

            // Continuite Of Care Document (CCD) v2
            default:
            case 'CCD':
                // Merge the Document Level
                $CDABuild = array_merge($CDABuild, LevelDocument\RealmHeader_US::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\recordTarget::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\author::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\dataEnterer::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\informant::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\legalAuthenticator::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\custodian::Insert($data));
                $CDABuild = array_merge($CDABuild, LevelDocument\documentationOf::Insert($data));
                foreach($CDABuild['documentationOf']['performer'] as $Performer)
                {
                    $CDABuild['documentationOf']['performer'][] = LevelDocument\performer::Insert($Performer);
                }

                // Merge the Section Level
                $CDABuild['component']['structuredBody'][] = LevelSection\advanceDirectives::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\allergies::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\encounters::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\familyHistory::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\functionalStatus::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\immunizations::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\medicalEquipment::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\medications::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\payers::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\planOfTreatment::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\problems::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\procedures::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\results::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\socialHistory::Insert($data);
                $CDABuild['component']['structuredBody'][] = LevelSection\vitalSigns::Insert($data);
                break;
        }

//         echo '<pre>';
//         print_r($CDABuild);
//         echo '</pre>';

        // Buildup the XML
        $xml = Array2XML::createXML('ClinicalDocument', $CDABuild);
        return $xml->saveXML();
    }

    /**
     * @param $data
     */
    private static function __CDA_v1($data)
    {

    }

}
