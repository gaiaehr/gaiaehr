<?php

class Utilities
{

    /**
     * CodingSystemId
     * Returns the ID of the Coding System selected.
     * @param $system
     * @return string
     */
    public static function CodingSystemId($system)
    {
        $system = strtoupper($system);
        switch($system)
        {
            case 'CPT-4':
            case 'CPT 4':
                return '2.16.840.1.113883.6.12';
                break;
            case 'ICD-10':
            case 'ICD 10':
                return '2.16.840.1.113883.6.6';
                break;
            case 'SNOMED-CT':
            case 'SNOMED CT':
            case 'SNOMED -CT':
                return '2.16.840.1.113883.6.96';
                break;
            case 'ACTCODE':
                return '2.16.840.1.113883.5.4';
                break;
            case 'CONFIDENTIALITY':
                return '2.16.840.1.113883.5.25';
                break;
            case 'LANGUAGE':
                return '2.16.840.1.113883.1.11.11526';
                break;
            case 'LOINC':
                return '2.16.840.1.113883.6.1';
                break;
            case 'SOCIALSECURITYNUMBER':
                return '2.16.840.1.113883.4.1';
                break;
            case 'ADMINISTRATIVEGENDER':
                return '2.16.840.1.113883.5.1';
                break;
            case 'MARITALSTATUSCODE':
                return '2.16.840.1.113883.5.2';
                break;
            case 'HL7 RELIGIOUS AFFILIATION':
            case 'HL7RELIGIOUSAFFILIATION':
                return '2.16.840.1.113883.5.1076';
                break;
            case 'RACE & ETHNICITY - CDC':
            case 'RACE AND ETHNICITY - CDC':
                return '2.16.840.1.113883.6.238';
                break;
            case 'RESPONSIBLEPARTY':
                return '2.16.840.1.113883.1.11.19830';
                break;
            case 'LANGUAGEABILITYMODE':
                return '2.16.840.1.113883.5.60';
                break;
            case 'LANGUAGEABILITYPROFICIENCY':
                return '2.16.840.1.113883.5.61';
                break;
            case 'RELIGIOUSAFILIATION':
                return '2.16.840.1.113883.1.11.19185';
                break;
            case 'NUCC':
                return '2.16.840.1.113883.6.101';
                break;
            case 'PERSONALANDLEGALRELATIONSHIPROLE':
                return '2.16.840.1.113883.5.111';
                break;
            case 'PARTICIPATIONFUNCTION':
                return '2.16.840.1.113883.5.88';
                break;
            case 'TAXONOMY':
                return '2.16.840.1.113883.4.6';
                break;
            case 'DCMUID':
                return '1.2.840.10008.2.6.1';
                break;
            case 'DCM':
                return '1.2.840.10008.2.16.4';
                break;
        }
    }

    /**
     * Generate v3 UUID
     *
     * Version 3 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     *
     * @param $namespace
     * @param $name
     * @return bool|string
     */
    public static function UUIDv3($namespace, $name)
    {
        if(!self::is_valid($namespace)) return false;
        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-','{','}'), '', $namespace);
        // Binary Value
        $nstr = '';
        // Convert Namespace UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2)
        {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }
        // Calculate hash value
        $hash = md5($nstr . $name);
        return sprintf('%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 3
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    /**
     *
     * Generate v4 UUID
     *
     * Version 4 UUIDs are pseudo-random.
     */
    public static function UUIDv4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Generate v5 UUID
     *
     * Version 5 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     *
     * @param $namespace
     * @param $name
     * @return bool|string
     */
    public static function UUIDv5($namespace, $name)
    {
        if(!self::is_valid($namespace)) return false;
        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-','{','}'), '', $namespace);
        // Binary Value
        $nstr = '';
        // Convert Namespace UUID to bits
        for($i = 0; $i < strlen($nhex); $i+=2)
        {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
        }
        // Calculate hash value
        $hash = sha1($nstr . $name);
        return sprintf('%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr($hash, 0, 8),
            // 16 bits for "time_mid"
            substr($hash, 8, 4),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }

    public static function is_valid($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }

}
