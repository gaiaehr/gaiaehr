<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class ORC extends Segments{

	function __construct(){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'ORC';                   // ROC Segment
		$this->rawSeg[1] = $this->getType('ID');
		$this->rawSeg[2] = $this->getType('EI');
		$this->rawSeg[3] = $this->getType('EI');
		$this->rawSeg[4] = $this->getType('EI');
		/**
		 * ORC-5 Order Status
		 * A    Some, but not all, results available
		 * CA   Order was canceled
		 * CM   Order is completed
		 * DC   Order was discontinued
		 * ER   Error, order not found
		 * HD   Order is on hold
		 * IP   In process, unspecified
		 * RP   Order has been replaced
		 * SC   In process, scheduled
		 */
		$this->rawSeg[5] = $this->getType('ID');
		/**
		 * ORC-6 Response Flag
		 * E    Report exceptions only
		 * R    Same as E, also Replacement and Parent-Child
		 * D    Same as R, also other associated segments
		 * F    Same as D, plus confirmations explicitly
		 * N    Only the MSA segment is returned
		 */
		$this->rawSeg[6] = $this->getType('ID');
		$this->rawSeg[7] = $this->getType('TQ');
		$this->rawSeg[8] = $this->getType('EIP');
		$this->rawSeg[9] = $this->getType('TS');
		$this->rawSeg[10] = $this->getType('XCN');
		$this->rawSeg[11] = $this->getType('XCN');
		$this->rawSeg[12] = $this->getType('XCN');
		$this->rawSeg[13] = $this->getType('PL');
		$this->rawSeg[14] = $this->getType('XTN');
		$this->rawSeg[15] = $this->getType('TS');
		$this->rawSeg[16] = $this->getType('CE');
		$this->rawSeg[17] = $this->getType('CE');
		$this->rawSeg[18] = $this->getType('CE');
		$this->rawSeg[19] = $this->getType('XCN');
		/**
		 * ORC-20 Advanced Beneficiary Notice Code
		 * 1    Service is subject to medical necessity procedures
		 * 2    Patient has been informed of responsibility, and agrees to pay for service
		 * 3    Patient has been informed of responsibility, and asks that the payer be billed
		 * 4    Advanced Beneficiary Notice has not been signed
		 */
		$this->rawSeg[20] = $this->getType('CE');
		$this->rawSeg[21] = $this->getType('XON');
		$this->rawSeg[22] = $this->getType('XAD');
		$this->rawSeg[23] = $this->getType('XTN');
		$this->rawSeg[24] = $this->getType('XAD');
		$this->rawSeg[25] = $this->getType('CWE');
		$this->rawSeg[26] = $this->getType('CWE');
		$this->rawSeg[27] = $this->getType('TS');
		$this->rawSeg[28] = $this->getType('CWE');
		/**
		 * ORC – 29 Order Type
		 * I    Inpatient Order
		 * O    Outpatient Order
		 */
		$this->rawSeg[29] = $this->getType('CWE');
		/**
		 * ORC – 30 Enterer Authorization Mode
		 * EL   Electronic
		 * EM   E-mail
		 * FX   Fax
		 * IP   In Person
		 * MA   Mail
		 * PA   Paper
		 * PH   Phone
		 * RE   Reflexive (Automated system)
		 * VC   Video-conference
		 * VO   Voice
		 */
		$this->rawSeg[30] = $this->getType('CNE');
		$this->rawSeg[31] = $this->getType('CWE');
	}
}