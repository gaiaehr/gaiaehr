<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 10:19 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('../lib/HL7/HL7.php');

class HL7Messages {

	public $hl7;

	function __construct(){
		$this->hl7 = new HL7();
	}

	function sendVXU(){
		$msh = $this->hl7->addSegment('MSH');
		$msh->setValue('3.1','GaiaEHR');
		$msh->setValue('4.1','GaiaEHR Facility');
		$msh->setValue('5.1','Test Application');
		$msh->setValue('6.1','Test Facility');
		$msh->setValue('9.1','VXU');
		$msh->setValue('9.2','V04');
		$msh->setValue('9.3','VXU_V04');
		$msh->setValue('11.1','P');
		$msh->setValue('12.1','2.5.1');

		$pid = $this->hl7->addSegment('PID');
		$pid->setValue('3.1', '15485');         //IDNumber
		$pid->setValue('3.4.1', 'MPI');         //Namespace ID
		$pid->setValue('3.4.2', '2.16.840.1.113883.19.3.2.1'); //Universal ID
		$pid->setValue('3.4.3', 'ISO');         //Universal ID Type (HL70301)
		$pid->setValue('3.5', 'MR');            //IDNumber Type (HL70203)
		$pid->setValue('5.1.1', 'Rodriguez');   //Surname
		$pid->setValue('5.2', 'Ernesto');       //GivenName
		$pid->setValue('7.1', '19780123');      //Date of Birth
		$pid->setValue('8','M');                //Administrative Sex
		$pid->setValue('10.1','2106-3');        //Race Identifier
		$pid->setValue('10.2','White');         //Race Text
		$pid->setValue('10.3','HL70005');       //Race Name of Coding System
		$pid->setValue('11.1.1','');            //Street or Mailing Address
		$pid->setValue('11.3','');              //City
		$pid->setValue('11.4','');              //State
		$pid->setValue('11.5','');              //Zip Code
		$pid->setValue('11.7','');              //Address Type
		$pid->setValue('13.2','PRN');           //PhoneNumber‐Home
		$pid->setValue('13.6','787');           //Area/City Code
		$pid->setValue('13.7','7525561');       //LocalNumber
		$pid->setValue('22.1','H');                  //EthnicGroup Identifier
		$pid->setValue('22.2','Hispanic or Latino'); //EthnicGroup Text
		$pid->setValue('22.3','HL70189');            //Name of Coding System

		$roc = $this->hl7->addSegment('ORC');
		$roc->setValue('1', 'RE');                  //HL70119

		$rxa = $this->hl7->addSegment('RXA');
		$rxa->setValue('3.1', '201005121330');      //Date/Time Start of Administration
		$rxa->setValue('4.1', '201005121330');      //Date/Time End of Administration
		//Administered Code
		$rxa->setValue('5.1', '19');                //Identifier
		$rxa->setValue('5.2', 'Bacillus Calmette-Guerin'); //Text
		$rxa->setValue('5.3', 'CVX');               //Name of Coding System
		$rxa->setValue('6', '1');                   //Administered Amount
		//AdministeredUnits(ml, etc)
		$rxa->setValue('7.1', 'ml');                //Identifier
		$rxa->setValue('7.2', 'milliliter');        //Text
		$rxa->setValue('7.3', 'ISO+');              //Name of Coding System HL70396
		$rxa->setValue('15', 'L888355');            //Substance LotNumbers

		//Substance ManufacturerName
		$rxa->setValue('17.1', 'OTC');              //Identifier
		$rxa->setValue('17.2', 'ORGANON');          //Text
		$rxa->setValue('17.3', 'MVX');              //Name of Coding System
	    $rxa->setValue('21', 'A');                      //Action Code


		return  $this->SendTo();
	}


    public function SendTo(){
        $data_string = $this->hl7->getMessage();

        print 'Sending...'.PHP_EOL;
        print $data_string;

        $ch = curl_init('http://192.168.1.109:8007');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: text/plain',
                'Content-Length: ' . strlen($data_string))
        );

        return curl_exec($ch);
    }
}

print '<pre>';
$hl7 = new HL7Messages();
print_r($hl7->sendVXU());