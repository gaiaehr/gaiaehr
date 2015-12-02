<?php

/**
 * 3.65	Physician of Record Participant (V2)
 *
 * This encounterParticipant is the attending physician and is usually different from the Physician
 * Reading Study Performer defined in documentationOf/serviceEvent.
 *
 * Contains:
 * US Realm Person Name (PN.US.FIELDED)
 *
 */

namespace LevelEntry;

use LevelOther;
use Component;
use Utilities;
use Exception;

/**
 * Class physicianOfRecordParticipant
 * @package LevelEntry
 */
class physicianOfRecordParticipant
{
    /**
     * @param $PortionData
     * @throws Exception
     */
    private static function Validate($PortionData)
    {
        if(!isset($PortionData['npi']))
            throw new Exception('SHALL contain exactly one [1..1] National Provider Identifier');
    }

    /**
     * Build the Narrative part of this section
     * @param $PortionData
     */
    public static function Narrative($PortionData)
    {
    }

    /**
     * @return array
     */
    public static function Structure()
    {
        return [
            'npi' => 'SHALL contain exactly one [1..1] National Provider Identifier',
            'dicomCode' => 'SHALL contain a valid DICOM Organizational Role from DICOM CID 7452  (Value Set 1.2.840.10008.6.1.516)(@codeSystem is 1.2.840.10008.2.16.4) or an appropriate national health care provider coding system (e.g., NUCC in the U.S., where @codeSystem is 2.16.840.1.113883.6.101)Footnote: DICOM Part 16 (NEMA PS3.16), page 631 in the 2011 edition. See ftp://medical.nema.org/medical/dicom/2011/11_16pu.pdf',
            'dicomCodeSystemName' => 'SHALL contain a valid DICOM Organizational Role from DICOM CID 7452  (Value Set 1.2.840.10008.6.1.516)(@codeSystem is 1.2.840.10008.2.16.4) or an appropriate national health care provider coding system (e.g., NUCC in the U.S., where @codeSystem is 2.16.840.1.113883.6.101)Footnote: DICOM Part 16 (NEMA PS3.16), page 631 in the 2011 edition. See ftp://medical.nema.org/medical/dicom/2011/11_16pu.pdf',
            'dicomDisplayName' => 'SHALL contain a valid DICOM Organizational Role from DICOM CID 7452  (Value Set 1.2.840.10008.6.1.516)(@codeSystem is 1.2.840.10008.2.16.4) or an appropriate national health care provider coding system (e.g., NUCC in the U.S., where @codeSystem is 2.16.840.1.113883.6.101)Footnote: DICOM Part 16 (NEMA PS3.16), page 631 in the 2011 edition. See ftp://medical.nema.org/medical/dicom/2011/11_16pu.pdf',
            'assignedPerson' => LevelOther\usRealmPersonNamePNUSFIELDED::Structure()
        ];
    }

    /**
     * @param $PortionData
     * @param $CompleteData
     * @return array|Exception
     */
    public static function Insert($PortionData, $CompleteData)
    {
        try {
            // Validate first
            self::Validate($PortionData);

            $Entry = [
                'encounterParticipant' => [
                    '@attributes' => [
                        'typeCode' => 'ATND'
                    ],
                    'templateId' => Component::templateId('2.16.840.1.113883.10.20.6.2.2.2'),
                    'assignedEntity' => [
                        'id' => [
                            'root' => '2.16.840.1.113883.4.6',
                            'extension' => $PortionData['npi']
                        ],
                        'code' => [
                            'code' => $PortionData['dicomCode'],
                            'codeSystem' => Utilities::CodingSystemId($PortionData['dicomCodeSystemName']),
                            'codeSystemName' => $PortionData['dicomCodeSystemName'],
                            'displayName' => $PortionData['dicomDisplayName']
                        ]
                    ]
                ]
            ];

            // This assignedEntity SHOULD contain zero or one [0..1] assignedPerson
            // SHALL contain exactly one [1..1] US Realm Person Name (PN.US.FIELDED)
            if(isset($PortionData['assignedPerson']))
            {
                $Entry['encounterParticipant']['assignedEntity'] = LevelOther\usRealmPersonNamePNUSFIELDED::insert(
                    $PortionData['assignedPerson'],
                    $CompleteData
                );
            }

            return $Entry;
        }
        catch (Exception $Error)
        {
            return $Error;
        }
    }

}
