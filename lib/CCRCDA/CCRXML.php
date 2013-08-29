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
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!isset($_SESSION))
{
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
$_SESSION['url'] = 'http://localhost/gaiaehr';
$_SESSION['root'] = '/var/www/gaiaehr';

include_once ($_SESSION['root'] . '/classes/UUID.php');

/**
 * Class ccrXML
 * This class should create a valid XML data file for HL7 CCR
 * stylesheet CCR.XSL
 */
class ccrXML {

    public $cdaDocument;
    private $xmlBody;

    private $patientUI;
    private $authorUI;
    private $clinicUI;
    private $insuranceUI;
    private $softwareUI;
    private $referenceUI;
    private $guardianUI;
    private $paymentProviderUI;

    function __construct()
    {
        $this->cdaDocument = new XMLWriter();
        $this->cdaDocument->openMemory();
        $this->cdaDocument->setIndent(true);
        $this->cdaDocument->setIndentString('    ');
        $this->cdaDocument->startDocument('1.0','UTF-8');
        $this->cdaDocument->writePi('xml-stylesheet', 'type="text/xsl" href="'.$_SESSION['url']. '/lib/CCRCDA/schema/ccr.xsl' .'"');

        // Start the CCR data document
        $this->cdaDocument->startElement("ContinuityOfCareRecord");
        $this->cdaDocument->writeAttribute("xmlns", "urn:astm-org:CCR");
        $this->cdaDocument->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");

        // set the GUID for actors linkage
        $this->patientUI = UUID::v4();
        $this->authorUI = UUID::v4();
        $this->clinicUI = UUID::v4();
        $this->insuranceUI = UUID::v4();
        $this->referenceUI = UUID::v4();
        $this->softwareUI = UUID::v4();
        $this->guardianUI = UUID::v4(); // TODO: This should be an array of GUID depending on how many guardian the patien has.

        $this->paymentProviderUI = UUID::v4();
    }

    function XMLdocument()
    {
        $this->cdaDocument->endElement();
        return $this->cdaDocument->outputMemory(true);
    }

    /**
     * Header of the CCR document
     * you need to pass an array to build the header
     * array['language'] = 'English'
     * @param $dataArray
     * @return bool|\Exception
     */
    function Header($dataArray)
    {
        try
        {
            // CCR Unique Identifier - (REQUIRED)
            //	<CCRDocumentObjectID>db734647-fc99-424c-a864-7e3cda82e703</CCRDocumentObjectID>
            $this->cdaDocument->startElement("CCRDocumentObjectID");
            $this->cdaDocument->text(UUID::v4());
            $this->cdaDocument->endElement();

            // Language - (REQUIRED)
            //  <Language>
            //		<Text>English</Text>
            //		<Code>
            //			<Value>en-US</Value>
            //			<CodingSystem>IETF1766</CodingSystem>
            //		</Code>
            //	</Language>
            if($dataArray['language']['text'] == '' || $dataArray['language']['code'] == '' || $dataArray['language']['codingsystem'] == '')
                throw new Exception('Language is not set, its required');
            $this->cdaDocument->startElement("Language");
                $this->cdaDocument->startElement("Text");
                $this->cdaDocument->text($dataArray['language']['text']);
                $this->cdaDocument->endElement();
                $this->cdaDocument->startElement("Code");
                    $this->cdaDocument->startElement("Value");
                    $this->cdaDocument->text($dataArray['language']['code']);
                    $this->cdaDocument->endElement();
                    $this->cdaDocument->startElement("CodingSystem");
                    $this->cdaDocument->text($dataArray['language']['codingsystem']);
                    $this->cdaDocument->endElement();
                $this->cdaDocument->endElement();
            $this->cdaDocument->endElement();

            // Version - (REQUIRED)
            $this->cdaDocument->startElement("Version");
            $this->cdaDocument->text('1.0');
            $this->cdaDocument->endElement();

            // DateTime - (REQUIRED)
            //  <DateTime>
            //      <ExactDateTime>2000-04-07T13:00:00+0500</ExactDateTime>
            //	</DateTime>
            $this->cdaDocument->startElement("DateTime");
                $this->cdaDocument->startElement("ExactDateTime");
                $this->cdaDocument->text(date('Y-m-d\TH:i:s\Z'));
                $this->cdaDocument->endElement();
            $this->cdaDocument->endElement();

            // Patient - (REQUIRED)
            // The patient is referenced by Actors section
            //  <Patient>
            //      <ActorID>2-16-840-1-113883-19-5-996756495</ActorID>
            //	</Patient>
            $this->cdaDocument->startElement("Patient");
                $this->cdaDocument->startElement("ActorID");
                $this->cdaDocument->text($this->patientUI);
                $this->cdaDocument->endElement();
            $this->cdaDocument->endElement();

            // Header - From:
            // <From>
            //		<ActorLink>
            //			<ActorID>8a54f393-8015-460c-abd2-f29aad15481c</ActorID>
            //			<ActorRole>
            //				<Text>author</Text>
            //			</ActorRole>
            //		</ActorLink>
            //	</From>
            $this->cdaDocument->startElement("From");
                $this->cdaDocument->startElement("ActorLink");
                    $this->cdaDocument->startElement("ActorID");
                    $this->cdaDocument->text($this->softwareUI);
                    $this->cdaDocument->endElement();
                    $this->cdaDocument->startElement("ActorRole");
                    $this->cdaDocument->text($dataArray['author']);
                    $this->cdaDocument->endElement();
                $this->cdaDocument->endElement();
            $this->cdaDocument->endElement();

            // Purpose - (OPTIONAL)
            //  <Purpose>
            //		<Description>
            //			<Text>Transfer of care</Text>
            //			<Code>
            //				<Value>308292007</Value>
            //				<CodingSystem>SNOMED CT</CodingSystem>
            //			</Code>
            //		</Description>
            //	</Purpose>
            if($dataArray['purpose']['description'] != '' || $dataArray['purpose']['code'] != '' || $dataArray['purpose']['codingsystem'] != '')
            {
                $this->cdaDocument->startElement("Purpose");
                    $this->cdaDocument->startElement("Description");
                        $this->cdaDocument->startElement("Text");
                        $this->cdaDocument->text($dataArray['purpose']['description']);
                        $this->cdaDocument->endElement();
                        $this->cdaDocument->startElement("Code");
                            $this->cdaDocument->startElement("Value");
                            $this->cdaDocument->text($dataArray['purpose']['code']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("CodingSystem");
                            $this->cdaDocument->text($dataArray['purpose']['codingsystem']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                $this->cdaDocument->endElement();
            }

            return true;
        }
        catch(Exception $e)
        {
            return $e;
        }
    }

    /**
     * Body function
     * This function should create the Body of the CCR data document
     * you need to pass an array to build the header
     * @param $dataArray
     * @return bool|\Exception
     */
    function Body($dataArray)
    {
        try
        {
            // Body - (REQUIRED)
            $this->cdaDocument->startElement("Body");


            // Payers - (REQUIRED) - One or more
            // Payers contains data on the patient’s payers, whether a ‘third party’ insurance, self-pay, other payer or
            // guarantor, or some combination of payers, and is used to define which entity is the responsible fiduciary for
            // the financial aspects of a patient’s care.
            if(count($dataArray['payers']) <= 0) throw new Exception('Payers must have one or more payers');
            $this->cdaDocument->startElement("Payers");
            foreach($dataArray['payers'] as $payer)
            {
                $this->cdaDocument->startElement("Payer");
                    // CCRDocumentObjectID
                    $this->cdaDocument->startElement("CCRDocumentObjectID");
                    $this->cdaDocument->text(UUID::v4());
                    $this->cdaDocument->endElement();
                    // Type
                    $this->cdaDocument->startElement("Type");
                        $this->cdaDocument->startElement("Text");
                        $this->cdaDocument->text($payer['type']);
                        $this->cdaDocument->endElement();
                        // Code
                        $this->cdaDocument->startElement("Code");
                            $this->cdaDocument->startElement("Value");
                            $this->cdaDocument->text($payer['value']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("CodingSystem");
                            $this->cdaDocument->text($payer['codingsystem']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                    // Source - Clinic
                    $this->cdaDocument->startElement("Source");
                        $this->cdaDocument->startElement("Actor");
                            $this->cdaDocument->startElement("ActorID");
                            $this->cdaDocument->text($this->softwareUI);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                    // Payment Provider
                    $this->cdaDocument->startElement("PaymentProvider");
                        $this->cdaDocument->startElement("ActorID");
                        $this->cdaDocument->text($this->insuranceUI);
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                    // Subscriber
                    $this->cdaDocument->startElement("Subscriber");
                        $this->cdaDocument->startElement("ActorID");
                        $this->cdaDocument->text($this->patientUI);
                        $this->cdaDocument->endElement();
                        $this->cdaDocument->startElement("ActorRole");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($payer['subscriber']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                    // Authorizations
                    $this->cdaDocument->startElement("Authorizations");
                        $this->cdaDocument->startElement("CCRDocumentObjectID");
                        $this->cdaDocument->text(UUID::v4());
                        $this->cdaDocument->endElement();
                        $this->cdaDocument->startElement("Description");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($payer['authorization']['description']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("Code");
                                $this->cdaDocument->startElement("Value");
                                $this->cdaDocument->text($payer['authorization']['code']);
                                $this->cdaDocument->endElement();
                                $this->cdaDocument->startElement("CodingSystem");
                                $this->cdaDocument->text($payer['authorization']['cosingsystem']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        $this->cdaDocument->startElement("Source");
                            $this->cdaDocument->startElement("Actor");
                                $this->cdaDocument->startElement("ActorID");
                                $this->cdaDocument->text($this->softwareUI);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();

                $this->cdaDocument->endElement();
            }
            $this->cdaDocument->endElement();

            // Advance Directives section - (REQUIRED IF KNOWN)
            // This section contains data defining the patient’s advance directives and any reference to supporting
            // documentation. The most recent and up-to-date directives are required, if known, and should be listed in as
            // much detail as possible. This section contains data such as the existence of living wills, healthcare proxies,
            // and CPR and resuscitation status. If referenced documents are available, they can be included in the CCD
            // exchange package.
            if(isset($dataArray['advanceDirectives']))
            {
                $this->cdaDocument->startElement("AdvanceDirectives");
                foreach($dataArray['advanceDirectives'] as $directiveItem)
                {
                    $this->cdaDocument->startElement("AdvanceDirective");
                        // CCRDocumentObjectID
                        $this->cdaDocument->startElement("CCRDocumentObjectID");
                        $this->cdaDocument->text(UUID::v4());
                        $this->cdaDocument->endElement();
                        // DateTime
                        $this->cdaDocument->startElement("DateTime");
                            $this->cdaDocument->startElement("Type");
                            $this->cdaDocument->text($directiveItem['directiveDescription']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("ExactDateTime");
                            $this->cdaDocument->text($directiveItem['exactDateTime']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Type
                        $this->cdaDocument->startElement("Type");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($directiveItem['type']['description']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("Code");
                                $this->cdaDocument->startElement("Value");
                                $this->cdaDocument->text($directiveItem['type']['code']);
                                $this->cdaDocument->endElement();
                                $this->cdaDocument->startElement("CodingSystem");
                                $this->cdaDocument->text($directiveItem['type']['codingsystem']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Description
                        $this->cdaDocument->startElement("Description");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($directiveItem['description']['description']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("Code");
                                $this->cdaDocument->startElement("Value");
                                $this->cdaDocument->text($directiveItem['description']['code']);
                                $this->cdaDocument->endElement();
                                $this->cdaDocument->startElement("CodingSystem");
                                $this->cdaDocument->text($directiveItem['description']['codingsystem']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Status
                        $this->cdaDocument->startElement("Status");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($directiveItem['status']['description']);
                            $this->cdaDocument->endElement();
                            $this->cdaDocument->startElement("Code");
                                $this->cdaDocument->startElement("Value");
                                $this->cdaDocument->text($directiveItem['status']['code']);
                                $this->cdaDocument->endElement();
                                $this->cdaDocument->startElement("CodingSystem");
                                $this->cdaDocument->text($directiveItem['status']['codingsystem']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Source
                        $this->cdaDocument->startElement("Source");
                            $this->cdaDocument->startElement("Actor");
                                $this->cdaDocument->startElement("ActorID");
                                $this->cdaDocument->text($this->softwareUI);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // ReferenceID
                        // This reference is used to link between this directive and documents
                        // TODO: If a document is attached do this CCR it has to make reference to this number.
                        $this->cdaDocument->startElement("ReferenceID");
                        $this->cdaDocument->text($this->referenceUI);
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                }
                $this->cdaDocument->endElement();
            }

            // Supporters - (OPTIONAL)
            // Represents the patient’s sources of support such as immediate family, relatives, and guardian at the time the
            // summarization is generated. Support information also includes next of kin, caregivers, and support
            // organizations. At a minimum, key support contacts relative to healthcare decisions, including next of kin,
            // should be included.
            if(isset($dataArray['supporters']))
            {
                $this->cdaDocument->startElement("Support");
                foreach($dataArray['supporters'] as $supporter)
                {
                    $this->cdaDocument->startElement("SupportProvider");
                        $this->cdaDocument->startElement("ActorID");
                        $this->cdaDocument->text($this->guardianUI);
                        $this->cdaDocument->endElement();
                        $this->cdaDocument->startElement("ActorRole");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($supporter['role']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                }
                $this->cdaDocument->endElement();
            }

            // Functional Status section - (OPTIONAL)
            // Functional Status describes the patient’s status of normal functioning at the time the Care Record was
            // created. Functional statuses include information regarding the patient relative to:
            //• Ambulatory ability
            //• Mental status or competency
            //• Activities of Daily Living (ADLs), including bathing, dressing, feeding, grooming
            //• Home / living situation having an effect on the health status of the patient
            //• Ability to care for self
            //• Social activity, including issues with social cognition, participation with friends and acquaintances
            //• other than family members
            //• Occupation activity, including activities partly or directly related to working, housework or volunteering, family and home responsibilities or activities related to home and family
            //• Communication ability, including issues with speech, writing or cognition required for communication Perception, including sight, hearing, taste, skin sensation, kinesthetic sense, proprioception, or balance
            // Any deviation from normal function that the patient displays and is recorded in the record should be
            // included. Of particular interest are those limitations that would in any way interfere with self care or the
            // medical therapeutic process. In addition, an improvement, any change in or noting that the patient has
            // normal functioning status is also valid for inclusion.
            if(isset($dataArray['functionalStatus']))
            {
                $this->cdaDocument->startElement("FunctionalStatus");
                    $this->cdaDocument->startElement("Function");
                        // CCRDocumentObjectID
                        $this->cdaDocument->startElement("CCRDocumentObjectID");
                        $this->cdaDocument->text(UUID::v4());
                        $this->cdaDocument->endElement();
                        // Type
                        $this->cdaDocument->startElement("Type");
                            $this->cdaDocument->startElement("Text");
                            $this->cdaDocument->text($dataArray['functionalStatus']['type']);
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Source
                        $this->cdaDocument->startElement("Source");
                            $this->cdaDocument->startElement("Actor");
                                $this->cdaDocument->startElement("ActorID");
                                $this->cdaDocument->text($this->softwareUI);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                        // Problem
                        // TODO: This can have one or more functional problems, we need to create a loop for functional problems
                        $this->cdaDocument->startElement("Problem");
                            // CCRDocumentObjectID
                            $this->cdaDocument->startElement("CCRDocumentObjectID");
                            $this->cdaDocument->text(UUID::v4());
                            $this->cdaDocument->endElement();
                            // DateTime
                            $this->cdaDocument->startElement("DateTime");
                                $this->cdaDocument->startElement("ExactDateTime");
                                $this->cdaDocument->text($dataArray['functionalStatus']['problem']['exactDateTime']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                            // Description
                            $this->cdaDocument->startElement("Description");
                                $this->cdaDocument->startElement("Text");
                                $this->cdaDocument->text($dataArray['functionalStatus']['problem']['description']);
                                $this->cdaDocument->endElement();
                                $this->cdaDocument->startElement("Code");
                                    $this->cdaDocument->startElement("Value");
                                    $this->cdaDocument->text($dataArray['functionalStatus']['problem']['code']);
                                    $this->cdaDocument->endElement();
                                    $this->cdaDocument->startElement("CodingSystem");
                                    $this->cdaDocument->text($dataArray['functionalStatus']['problem']['codingsystem']);
                                    $this->cdaDocument->endElement();
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                            // Status
                            $this->cdaDocument->startElement("Status");
                                $this->cdaDocument->startElement("Text");
                                $this->cdaDocument->text($dataArray['functionalStatus']['problem']['status']);
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                            // Source
                            $this->cdaDocument->startElement("Source");
                                $this->cdaDocument->startElement("Actor");
                                    $this->cdaDocument->startElement("ActorID");
                                    $this->cdaDocument->text($this->softwareUI);
                                    $this->cdaDocument->endElement();
                                $this->cdaDocument->endElement();
                            $this->cdaDocument->endElement();
                        $this->cdaDocument->endElement();
                    $this->cdaDocument->endElement();
                $this->cdaDocument->endElement();
            }

            // Problems section - (OPTIONAL)
            // This section lists and describes all relevant clinical problems at the time the summary is generated. At a
            // minimum, all pertinent current and historical problems should be listed. CDA R2 represents problems as
            // Observations.
            if(isset($dataArray['functionalStatus']))
            {

            }

            // End of Body
            $this->cdaDocument->endElement();
            return true;
        }
        catch(Exception $e)
        {
            return $e;
        }
    }
}


$test = new ccrXML();

$headerData['language']['text'] = 'English';
$headerData['language']['code'] = 'en-US';
$headerData['language']['codingsystem'] = 'IETF1766';
$headerData['author'] = 'GaiaEHR';
$headerData['purpose']['description'] = '';
$headerData['purpose']['code'] = '';
$headerData['purpose']['codingsystem'] = '';

$headerData['payers'][0]['type'] = 'Extended healthcare';
$headerData['payers'][0]['value'] = 'EHCPOL';
$headerData['payers'][0]['codingsystem'] = 'ActCode';
$headerData['payers'][0]['subscriber'] = 'Covered party';
$headerData['payers'][0]['authorization']['description'] = 'Colonoscopy';
$headerData['payers'][0]['authorization']['code'] = '73761001';
$headerData['payers'][0]['authorization']['codingsystem'] = 'SNOMED CT';

$headerData['advanceDirectives'][0]['directiveDescription'] = 'Verified With Patient';
$headerData['advanceDirectives'][0]['exactDateTime'] = date('Y-m-d\TH:i:s\Z');
$headerData['advanceDirectives'][0]['type']['description'] = 'Resuscitation status';
$headerData['advanceDirectives'][0]['type']['code'] = '304251008';
$headerData['advanceDirectives'][0]['type']['codingsystem'] = 'SNOMED CT';
$headerData['advanceDirectives'][0]['description']['description'] = 'Do not resuscitate';
$headerData['advanceDirectives'][0]['description']['code'] = '304253006';
$headerData['advanceDirectives'][0]['description']['codingsystem'] = 'SNOMED CT';
$headerData['advanceDirectives'][0]['status']['description'] = 'Current and verified';
$headerData['advanceDirectives'][0]['status']['code'] = '15240007';
$headerData['advanceDirectives'][0]['status']['codingsystem'] = 'SNOMED CT';

$headerData['supporters'][0]['role'] = 'Guardian';
//$headerData['supporters'][1]['role'] = 'Next of Kin';

$headerData['functionalStatus']['type'] = 'Ambulatory Status';
$headerData['functionalStatus']['problem']['exactDateTime'] = date('Y-m-d\TH:i:s\Z');
$headerData['functionalStatus']['problem']['description'] = 'Dependence on cane';
$headerData['functionalStatus']['problem']['code'] = '105504002';
$headerData['functionalStatus']['problem']['codingsystem'] = 'SNOMED CT';
$headerData['functionalStatus']['problem']['status'] = 'Active';


//echo '<pre>';
//print_r($headerData);
//echo '</pre>';

$test->Header($headerData);
$test->Body($headerData);

header('Content-type: application/xml');
echo $test->XMLdocument();