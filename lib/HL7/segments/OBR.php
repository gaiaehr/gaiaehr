<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');


class OBR extends Segments{

	protected $children = array('OBX','SPM');

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'OBR');
		$this->setField(1, 'SI', 1);
		$this->setField(2, 'EI', 1);
		$this->setField(3, 'EI', 1);
		$this->setField(4, 'CE', 1);
		$this->setField(5, 'ID', 1);
		$this->setField(6, 'TS', 1);
		$this->setField(7, 'TS', 1);
		$this->setField(8, 'TS', 1);
		$this->setField(9, 'CQ', 1);
		$this->setField(10, 'XCN', 1);
		/**
		 * OBR-11 Specimen Action Code
		 * A    Add ordered tests to the existing specimen
		 * G    Generated order; reflex order
		 * L    Lab to obtain specimen from patient
		 * O    Specimen obtained by service other than Lab
		 * P    Pending specimen; Order sent prior to delivery
		 * R    Revised order
		 * S    Schedule the tests specified below
		 */
		$this->setField(11, 'ID', 1);
		$this->setField(12, 'CE', 1);
		$this->setField(13, 'ST', 1);
		$this->setField(14, 'TS', 1);
		/**
		 * OBR-15 Specimen Source
		 * B Blind Sample
		 * C Calibrator
		 * P Patient (default if blank component value)
		 * Q Control specimen
		 * R Replicate (of patient sample as a control)
		 */
		$this->setField(15, 'SPS', 1);
		$this->setField(16, 'XCN', 1);
		$this->setField(17, 'XTN', 1);
		$this->setField(18, 'ST', 1);
		$this->setField(19, 'ST', 1);
		$this->setField(20, 'ST', 1);
		$this->setField(21, 'ST', 1);
		$this->setField(22, 'TS', 1);
		$this->setField(23, 'MOC', 1);
		/**
		 * OBR-24 Diagnostic Serv Sect ID
		 * AU   Audiology
		 * BG   Blood Gases
		 * BLB  Blood Bank
		 * CUS  Cardiac Ultrasound
		 * CTH  Cardiac Catheterization
		 * CT   CAT Scan
		 * CH   Chemistry
		 * CP   Cytopathology
		 * EC   Electrocardiac (e.g., EKG, EEC, Holter)
		 * EN   Electroneuro (EEG, EMG,EP,PSG)
		 * HM   Hematology
		 * ICU  Bedside ICU Monitoring
		 * IMM  Immunology
		 * LAB  Laboratory
		 * MB   Microbiology
		 * MCB  Mycobacteriology
		 * MYC  Mycology
		 * NMS  Nuclear Medicine Scan
		 * NMR  Nuclear Magnetic Resonance
		 * NRS  Nursing Service Measures
		 * OUS  OB Ultrasound
		 * OT   Occupational Therapy
		 * OTH  Other
		 * OSL  Outside Lab
		 * PHR  Pharmacy
		 * PT   Physical Therapy
		 * PHY  Physician (Hx. Dx, admission note, etc.)
		 * PF   Pulmonary Function
		 * RAD  Radiology
		 * RX   Radiograph
		 * RUS  Radiology Ultrasound
		 * RC   Respiratory Care (therapy)
		 * RT   Radiation Therapy
		 * SR   Serology
		 * SP   Surgical Pathology
		 * TX   Toxicology
		 * VUS  Vascular Ultrasound
		 * VR   Virology
		 * XRC  Cineradiograph
		 */
		$this->setField(24, 'ID', 1);
		/**
		 * OBR-25 Result Status
		 * O    Order received; specimen not yet received
		 * I    No results available; specimen received, procedure incomplete
		 * S    No results available; procedure scheduled, but not done
		 * A    Some, but not all, results available
		 * P    Preliminary: A verified early result is available, final results not yet obtained
		 * C    Correction to results
		 * R    Results stored; not yet verified
		 * F    Final results; results stored and verified. Can only be changed with a corrected result.
		 * X    No results available; Order canceled.
		 * Y    No order on record for this test. (Used only on queries)
		 * Z    No record of this patient. (Used only on queries)
		 */
		$this->setField(25, 'ID', 1);
		$this->setField(26, 'PRL', 1);
		$this->setField(27, 'TQ', 1);
		$this->setField(28, 'XCN', 1);
		$this->setField(29, 'EIP', 1);
		/**
		 * OBR-30 Transportation Mode
		 * CART     Cart - patient travels on cart or gurney
		 * PORT     The examining device goes to patient's location
		 * WALK     Patient walks to diagnostic service
		 * WHLC     Wheelchair
		 */
		$this->setField(30, 'ID', 1);
		$this->setField(31, 'CE', 1);
		$this->setField(32, 'NDL', 1);
		$this->setField(33, 'NDL', 1);
		$this->setField(34, 'NDL', 1);
		$this->setField(35, 'NDL', 1);
		$this->setField(36, 'TS', 1);
		$this->setField(37, 'NM', 1);
		$this->setField(38, 'CE', 1);
		$this->setField(39, 'CE', 1);
		$this->setField(40, 'CE', 1);
		/**
		 * OBR-41 Transport Arranged
		 * A    Arranged
		 * N    Not Arranged
		 * U    Unknown
		 */
		$this->setField(41, 'ID', 1);
		/**
		 * OBR-42 Escort Required
		 * R    Required
		 * N    Not Required
		 * U    Unknown
		 */
		$this->setField(42, 'ID', 1);
		$this->setField(43, 'CE', 1);
		$this->setField(44, 'CE', 1);
		$this->setField(45, 'CE', 1);
		$this->setField(46, 'CE', 1);
		/**
		 * OBR-47 Filler Supplemental Service Information
		 * The SNOMED DICOM Micro-glossary (SDM) or private (local) entries.
		 */
		$this->setField(47, 'CE', 1);
		$this->setField(48, 'CWE', 1);
		$this->setField(49, 'IS', 1);
		/**
		 * OBR-49 Result Handling
		 * F    Film-with-patient
		 * N    Notify provider when ready
		 */
		$this->setField(50, 'CWE', 1);


	}
}