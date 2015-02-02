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
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'OBR';
		$this->rawSeg[1] = $this->getType('SI');
		$this->rawSeg[2] = $this->getType('EI');
		$this->rawSeg[3] = $this->getType('EI');
		$this->rawSeg[4] = $this->getType('CE');
		$this->rawSeg[5] = $this->getType('ID');
		$this->rawSeg[6] = $this->getType('TS');
		$this->rawSeg[7] = $this->getType('TS');
		$this->rawSeg[8] = $this->getType('TS');
		$this->rawSeg[9] = $this->getType('CQ');
		$this->rawSeg[10] = $this->getType('XCN');
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
		$this->rawSeg[11] = $this->getType('ID');
		$this->rawSeg[12] = $this->getType('CE');
		$this->rawSeg[13] = $this->getType('ST');
		$this->rawSeg[14] = $this->getType('TS');
		/**
		 * OBR-15 Specimen Source
		 * B Blind Sample
		 * C Calibrator
		 * P Patient (default if blank component value)
		 * Q Control specimen
		 * R Replicate (of patient sample as a control)
		 */
		$this->rawSeg[15] = $this->getType('SPS');
		$this->rawSeg[16] = $this->getType('XCN');
		$this->rawSeg[17] = $this->getType('XTN');
		$this->rawSeg[18] = $this->getType('ST');
		$this->rawSeg[19] = $this->getType('ST');
		$this->rawSeg[20] = $this->getType('ST');
		$this->rawSeg[21] = $this->getType('ST');
		$this->rawSeg[22] = $this->getType('TS');
		$this->rawSeg[23] = $this->getType('MOC');
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
		$this->rawSeg[24] = $this->getType('ID');
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
		$this->rawSeg[25] = $this->getType('ID');
		$this->rawSeg[26] = $this->getType('PRL');
		$this->rawSeg[27] = $this->getType('TQ');
		$this->rawSeg[28] = $this->getType('XCN');
		$this->rawSeg[29] = $this->getType('EIP');
		/**
		 * OBR-30 Transportation Mode
		 * CART     Cart - patient travels on cart or gurney
		 * PORT     The examining device goes to patient's location
		 * WALK     Patient walks to diagnostic service
		 * WHLC     Wheelchair
		 */
		$this->rawSeg[30] = $this->getType('ID');
		$this->rawSeg[31] = $this->getType('CE');
		$this->rawSeg[32] = $this->getType('NDL');
		$this->rawSeg[33] = $this->getType('NDL');
		$this->rawSeg[34] = $this->getType('NDL');
		$this->rawSeg[35] = $this->getType('NDL');
		$this->rawSeg[36] = $this->getType('TS');
		$this->rawSeg[37] = $this->getType('NM');
		$this->rawSeg[38] = $this->getType('CE');
		$this->rawSeg[39] = $this->getType('CE');
		$this->rawSeg[40] = $this->getType('CE');
		/**
		 * OBR-41 Transport Arranged
		 * A    Arranged
		 * N    Not Arranged
		 * U    Unknown
		 */
		$this->rawSeg[41] = $this->getType('ID');
		/**
		 * OBR-42 Escort Required
		 * R    Required
		 * N    Not Required
		 * U    Unknown
		 */
		$this->rawSeg[42] = $this->getType('ID');
		$this->rawSeg[43] = $this->getType('CE');
		$this->rawSeg[44] = $this->getType('CE');
		$this->rawSeg[45] = $this->getType('CE');
		$this->rawSeg[46] = $this->getType('CE');
		/**
		 * OBR-47 Filler Supplemental Service Information
		 * The SNOMED DICOM Micro-glossary (SDM) or private (local) entries.
		 */
		$this->rawSeg[47] = $this->getType('CE');
		$this->rawSeg[48] = $this->getType('CWE');
		$this->rawSeg[49] = $this->getType('IS');
		/**
		 * OBR-49 Result Handling
		 * F    Film-with-patient
		 * N    Notify provider when ready
		 */
		$this->rawSeg[50] = $this->getType('CWE');


	}
}