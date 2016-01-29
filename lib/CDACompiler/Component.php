<?php

class Component
{

    /**
     * @param $id
     * @return array
     */
    public static function templateId($id)
    {
        return [
            '@attributes' => [
                'root' => $id
            ]
        ];
    }

    /**
     * @param $realmCode
     * @return array
     */
    public static function realmCode($realmCode)
    {
        return [
            '@attributes' => [
                'code' => $realmCode
            ]
        ];
    }

    /**
     * @param $low
     * @param null $high
     * @return mixed
     */
    public static function time($low, $high = null)
    {
        $ReturnValue['low'] = [
            '@attributes' => [
                'value' => $low
            ]
        ];

        if($high == 'UNK')
        {
            $ReturnValue['high'] = [
                '@attributes' => [
                    'nullflavor' => 'UNK'
                ]
            ];
            return $ReturnValue;
        }

        if($high == NULL) return $ReturnValue;

        $ReturnValue['high'] = [
            '@attributes' => [
                'value' => $high
            ]
        ];

        return $ReturnValue;
    }

    /**
     * @param $use
     * @param $value
     * @return array
     */
    public static function telecom($use, $value)
    {
        // Add the tel: prefix is does not has it
        if(strpos($value, 'tel:') === true) $value = 'tel: ' . $value;
        if(!isset($use) || !isset($value))
        {
            return [
                '@attributes' => [
                    'nullFlavor' => 'NI'
                ]
            ];
        }
        else
        {
            return [
                '@attributes' => [
                    'use' => $use,
                    'value' => $value
                ]
            ];
        }
    }

    /**
     * code
     * @param null $code
     * @param null $displayName
     * @param null $codeSystemName
     * @return array
     */
    public static function code($code = null, $displayName = null, $codeSystemName = null)
    {
        if ($code == null)
        {
            return [
                '@attributes' => [
                    'nullFlavor' => 'NP'
                ]
            ];
        }
        else
        {
            return [
                '@attributes' => [
                    'code' => $code,
                    'displayName' => $displayName,
                    'codeSystem' => Utilities::CodingSystemId($codeSystemName),
                    'codeSystemName' => $codeSystemName
                ]
            ];
        }
    }

    /**
     * @param $title
     * @return mixed
     */
    public static function title($title)
    {
        return $title;
    }

    /**
     * @param $root
     * @param $extension
     * @return array
     */
    public static function typeId($root, $extension)
    {
        return [
            '@attributes' => [
                'root' => $root,
                'extension' => $extension
            ]
        ];
    }

    /**
     * @param $effectiveDate
     * @return array
     */
    public static function effectiveTime($effectiveDate)
    {
        // the syntax is "YYYYMMDDHHMMSS.UUUU[+|-ZZzz]" where digits can be omitted
        // the right side to express less precision.
        return [
            '@attributes' => [
                'value' => $effectiveDate
            ]
        ];
    }

    /**
     * @param $confidentialityCode
     * @return array
     */
    public static function confidentialityCode($confidentialityCode)
    {
        return [
            '@attributes' => [
                'code' => $confidentialityCode,
                'codeSystem' => Utilities::CodingSystemId('Confidentiality'),
                'codeSystemName' => 'Confidentiality'
            ]
        ];
    }

    /**
     * @param $languageCode
     * @return array
     */
    public static function languageCode($languageCode)
    {
        return [
            '@attributes' => [
                'code' => $languageCode
            ]
        ];
    }

    /**
     * @param $root
     * @param null $extension
     * @return array
     */
    public static function id($root, $extension = null)
    {
        $compose = [
            '@attributes' => [
                'root' => $root
            ]
        ];

        if(isset($extension)) $compose['@attributes']['extension'] = $extension;

        return $compose;
    }

    /**
     * @param $extension
     * @param $root
     * @return array
     */
    public static function setId($extension, $root)
    {
        return [
            '@attributes' => [
                'extension' => $extension,
                'root' => $root
            ]
        ];
    }

    /**
     * @param $versionNumber
     * @return array
     */
    public static function versionNumber($versionNumber)
    {
        return [
            '@attributes' => [
                'value' => $versionNumber
            ]
        ];
    }

    /**
     * @param $code
     * @param $displayName
     * @param $codeSystemName
     * @param $codeSystem
     * @return array
     */
    public static function translation($code, $displayName, $codeSystemName, $codeSystem)
    {
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystemName' => $codeSystemName,
                'codeSystem' => $codeSystem
            ]
        ];
    }

    /**
     * @param $code
     * @return array
     */
    public static function statusCode($code)
    {
        return [
            '@attributes' => [
                'code' => $code
            ]
        ];
    }

    /**
     * @param $root
     * @param $extension
     * @return array
     */
    public static function sdtc_id($root, $extension)
    {
        return [
            '@attributes' => [
                'root' => $root,
                'extension' => $extension
            ]
        ];
    }

    /**
     * @param $xsitype
     * @param $code
     * @param $displayName
     * @param $codeSystem
     * @param $codeSystemName
     * @return array
     */
    public static function value($xsitype, $code, $displayName, $codeSystem, $codeSystemName)
    {
        return [
            '@attributes' => [
                'xsi:type' => $xsitype,
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => $codeSystem,
                'codeSystemName' => $codeSystemName
            ]
        ];
    }

    /**
     * addr
     * Injects the address of any component
     *
     * @param $use
     * @param $streetAddressLine
     * @param $city
     * @param $state
     * @param $postalCode
     * @param $country
     * @return array
     */
    public static function addr($use = null, $streetAddressLine, $city, $state, $postalCode, $country)
    {
        $addrBuild = [];
        if($use) $addrBuild['@attributes'] = ['use' => $use ];
        $addrBuild['streetAddressLine'] = $streetAddressLine;
        $addrBuild['city'] = $city;
        $addrBuild['state'] = $state;
        $addrBuild['postalCode'] = $postalCode;
        $addrBuild['country'] = $country;
        return $addrBuild;
    }

    /**
     * @param $prefix
     * @param null $prefixQualifier
     * @param $given
     * @param null $givenQualifier
     * @param $family
     * @param null $familyQualifier
     * @param $name
     * @param null $nameQualifier
     * @return array
     */
    public static function name(
        $prefix,
        $prefixQualifier = null,
        $given,
        $givenQualifier = null,
        $family,
        $familyQualifier = null,
        $name,
        $nameQualifier = null)
    {

        $nameBuild = [];

        // If the prefix is no information aka NI, return and give a
        // nullvalue = 'NI'
        if($prefix == 'NI')
        {
            $prefix = [ 'prefix' => [
                '@attributes' => [
                    'nullvalue' => 'NI'
                ]
            ]
            ];
            $nameBuild = array_merge($nameBuild, $prefix);
            return $nameBuild;
        }

        // Prefix
        if($prefix)
        {
            $prefix = [ 'prefix' => $prefix ];
            if($prefixQualifier)
                $prefix['prefix'] = [ '@attributes' => ['qualifier' => $prefixQualifier] ];
            $nameBuild = array_merge($nameBuild, $prefix);
        }

        // Given
        if($given)
        {
            $given = ['given' => $given];
            if ($givenQualifier)
                $given['given'] =[ '@attributes' => ['qualifier' => $givenQualifier] ];
            $nameBuild = array_merge($nameBuild, $given);
        }

        // Family
        if($family)
        {
            $family = ['family' => $family];
            if ($familyQualifier)
                $family['family'] = [ '@attributes' => ['qualifier' => $familyQualifier]];
            $nameBuild = array_merge($nameBuild, $family);
        }

        // Name
        if($name)
        {
            $name = ['name' => $name ];
            if($nameQualifier) $name['name']['@attributes']['qualifier'] = $nameQualifier;
            $nameBuild = array_merge($nameBuild, $name);
        }

        return $nameBuild;
    }

    /**
     * administrativeGenderCode
     * @param $code
     * @return array
     */
    public static function administrativeGenderCode($code)
    {
        $displayName = null;
        switch($code)
        {
            case 'F':
                $displayName = 'Female';
                break;
            case 'M':
                $displayName = 'Male';
                break;
            case 'UN':
                $displayName = 'Undifferentiated';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('AdministrativeGender'),
                'codeSystemName' => 'AdministrativeGender'
            ]
        ];
    }

    /**
     * @param $datetime
     * @return array
     */
    public static function birthTime($datetime)
    {
        return [
            '@attributes' => [
                'value' => $datetime
            ]
        ];
    }

    /**
     * @param $code
     * @return array
     */
    public static function maritalStatusCode($code)
    {
        // Value Set: Marital Status Value Set 2.16.840.1.113883.1.11.12212
        // Marital Status is the domestic partnership status of a person.
        $displayName = null;
        switch($code){
            case 'A':
                $displayName = 'Annulled';
                break;
            case 'D':
                $displayName = 'Divorced';
                break;
            case 'T':
                $displayName = 'Domestic partner';
                break;
            case 'I':
                $displayName = 'Interlocutory';
                break;
            case 'L':
                $displayName = 'Legally Separated';
                break;
            case 'M':
                $displayName = 'Married';
                break;
            case 'S':
                $displayName = 'Never Married';
                break;
            case 'P':
                $displayName = 'Polygamous';
                break;
            case 'W':
                $displayName = 'Widowed';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('MaritalStatusCode'),
                'codeSystemName' => 'MaritalStatusCode'
            ]
        ];
    }

    /**
     * participationFunction
     * TODO: Finish entering all the value.
     * @param $code
     * @return array
     */
    public static function participationFunction($code)
    {
        $displayName = '';
        switch($code)
        {
            case 'PCP':
                $displayName = 'Primary care physician';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('ParticipationFunction'),
                'codeSystemName' => 'Participation Function'
            ]
        ];
    }

    /**
     * religiousAffiliationCode
     * TODO: Finish all the codes. They are incomplete.
     * @param $code
     * @return array
     */
    public static function religiousAffiliationCode($code)
    {
        $displayName = '';
        switch($code)
        {
            case '1001':
                $displayName = 'Adventist';
                break;
            case '1002':
                $displayName = 'African Religions';
                break;
            case '1003':
                $displayName = 'Afro-Caribbean Religions';
                break;
            case '1004':
                $displayName = 'Agnosticism';
                break;
            case '1005':
                $displayName = 'Anglican';
                break;
            case '1006':
                $displayName = 'Animism';
                break;
            case '1061':
                $displayName = 'Assembly of God';
                break;
            case '1007':
                $displayName = 'Atheism';
                break;
            case '1008':
                $displayName =  "Babi & Baha'I faiths";
                break;
            case '1009':
                $displayName = 'Baptist';
                break;
            case '1010':
                $displayName = 'Bon';
                break;
            case '1062':
                $displayName = 'Brethren';
                break;
            case '1011':
                $displayName = 'Cao Dai';
                break;
            case '1012':
                $displayName = 'Celticism';
                break;
            case '1013':
                $displayName = 'Christian (non-Catholic, non-specific)';
                break;
            case '1063':
                $displayName = 'Christian Scientist';
                break;
            case '1064':
                $displayName = 'Church of Christ';
                break;
            case '1065':
                $displayName = 'Church of God';
                break;
            case '1014':
                $displayName = 'Confucianism';
                break;
            case '1066':
                $displayName = 'Congregational';
                break;
            case '1015':
                $displayName = 'Cyberculture Religions';
                break;
            case '1067':
                $displayName = 'Disciples of Christ';
                break;
            case '1016':
                $displayName = 'Divination';
                break;
            case '1068':
                $displayName = 'Eastern Orthodox';
                break;
            case '1069':
                $displayName = 'Episcopalian';
                break;
            case '1070':
                $displayName = 'Evangelical Covenant';
                break;
            case '1017':
                $displayName = 'Fourth Way';
                break;
            case '1018':
                $displayName = 'Free Daism';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('Religious Affiliation'),
                'codeSystemName' => 'Religious Affiliation'
            ]
        ];
    }

    /**
     * raceCode
     * TODO: Finish all the codes.
     * @param $code
     * @return array
     */
    public static function raceCode($code)
    {
        $displayName = '';
        switch($code)
        {
            case '1006-6':
                $displayName = 'Abenaki';
                break;
            case '1579-2':
                $displayName = 'Absentee Shawnee';
                break;
            case '1490-2':
                $displayName = 'Acoma';
                break;
            case '2126-1':
                $displayName = 'Afghanistani';
                break;
            case '2060-2':
                $displayName = 'African';
                break;
            case '2058-6':
                $displayName = 'African American';
                break;
            case '1994-3':
                $displayName = 'Agdaagux';
                break;
            case '2076-8':
                $displayName = 'Hawaiian or Other Pacific Islander';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('Race & Ethnicity - CDC'),
                'codeSystemName' => 'Race & Ethnicity - CDC'
            ]
        ];
    }


    /**
     * TODO: Finish entering all the codes.
     * @param $code
     * @return array
     */
    public static function ethnicGroupCode($code)
    {
        $displayName = '';
        switch($code)
        {
            case '2135-2':
                $displayName = 'Hispanic or Latino';
                break;
            case '2186-5':
                $displayName = 'Not Hispanic or Latino';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('Race & Ethnicity - CDC'),
                'codeSystemName' => 'Race & Ethnicity - CDC'
            ]
        ];
    }

    /**
     * @param $code
     * @param $displayName
     * @param $codeSystemName
     * @return array
     */
    public static function modeCode($code, $displayName, $codeSystemName)
    {
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId($codeSystemName),
                'codeSystemName' => $codeSystemName
            ]
        ];
    }

    /**
     * @param $code
     * @return array
     */
    public static function proficiencyLevelCode($code)
    {
        // Value Set: LanguageAbilityProficiency 2.16.840.1.113883.1.11.12199
        $displayName = null;
        switch($code)
        {
            case 'E':
                $displayName = 'Excellent';
                break;
            case 'F':
                $displayName = 'Fair';
                break;
            case 'G':
                $displayName = 'Good';
                break;
            case 'P':
                $displayName = 'Poor';
                break;
        }

        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('LanguageAbilityProficiency'),
                'codeSystemName' => 'LanguageAbilityProficiency'
            ]
        ];
    }

    /**
     * @param $code
     * @return array
     */
    public static function languageAbilityMode($code)
    {
        // Value Set: LanguageAbilityMode Value Set 2.16.840.1.113883.1.11.12249
        $displayName = null;
        switch($code)
        {
            case 'ESGN':
                $displayName = 'Expressed signed';
                break;
            case 'ESP':
                $displayName = 'Expressed spoken';
                break;
            case 'EWR':
                $displayName = 'Expressed written';
                break;
            case 'RSGN':
                $displayName = 'Received signed';
                break;
            case 'RSP':
                $displayName = 'Received spoken';
                break;
            case 'RWR':
                $displayName = 'Received written';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('LanguageAbilityMode'),
                'codeSystemName' => 'LanguageAbilityMode'
            ]
        ];
    }

    /**
     * RoleCode
     * TODO: Need to finish entering all the codes.
     * @param $code
     * @return array
     */
    public static function RoleCode($code)
    {
        $displayName = null;
        switch($code)
        {
            case 'POWATT':
                $displayName = 'Power of Attorney';
                break;
            case 'HPOWATT':
                $displayName = 'Healthcare Power of Attorney';
                break;
        }
        return [
            '@attributes' => [
                'code' => $code,
                'displayName' => $displayName,
                'codeSystem' => Utilities::CodingSystemId('LanguageAbilityMode'),
                'codeSystemName' => 'LanguageAbilityMode'
            ]
        ];
    }

    /**
     * @param $value
     * @return array
     */
    public static function preferenceInd($value)
    {
        return [
            '@attributes' => [
                'value' => $value
            ]
        ];
    }


    /**
     * NUCCProviderCodes
     * TODO: Finish entering all the codes.
     * @param $code
     * @return array
     */
    public static function NUCCProviderCodes($code)
    {
        if ($code == null)
        {
            return [
                '@attributes' => [
                    'nullFlavor' => 'NP'
                ]
            ];

        }
        else
        {
            $displayName = null;
            switch($code)
            {
                case '171100000X':
                    $displayName = 'Acupuncturist [Other Service Providers]';
                    break;
                case '364SA2100X':
                    $displayName = 'Acute Care [Physician Assistants & Advanced Practice Nursing Providers\Clinical Nurse Specialist]';
                    break;
                case '207QA0505X':
                    $displayName = 'Adult Medicine';
                    break;
                case '163W00000X':
                    $displayName = 'Registered nurse';
                    break;
            }
            return [
                '@attributes' => [
                    'code' => $code,
                    'displayName' => $displayName,
                    'codeSystem' => Utilities::CodingSystemId('NUCC'),
                    'codeSystemName' => 'NUCC'
                ]
            ];
        }
    }

}
