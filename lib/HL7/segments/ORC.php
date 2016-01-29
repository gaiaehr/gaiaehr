<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');

class ORC extends Segments{

	protected $children = array('OBR');

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'ORC');
		/**
		 * AF Order/service refill request approval
		 * CA Cancel order/service request
		 * CH Child order/service
		 * CN Combined result
		 * CR Canceled as requested
		 * DC Discontinue order/service request
		 * DE Data errors
		 * DF Order/service refill request denied
		 * DR Discontinued as requested
		 * FU Order/service refilled, unsolicited
		 * HD Hold order request
		 * HR On hold as requested
		 * LI Link order/service to patient care problem or goal
		 * NA Number assigned
		 * NW New order/service
		 * OC Order/service canceled
		 * OD Order/service discontinued
		 * OE Order/service released
		 * OF Order/service refilled as requested
		 * OH Order/service held
		 * OK Order/service accepted & OK
		 * OP Notification of order for outside dispense
		 * OR Released as requested
		 * PA Parent order/service
		 * PR Previous Results with new order/service
		 * PY Notification of replacement order for outside dispense
		 * RE Observations/Performed Service to follow
		 * RF Refill order/service request
		 * RL Release previous hold
		 * RO Replacement order
		 * RP Order/service replace request
		 * RQ Replaced as requested
		 * RR Request received
		 * RU Replaced unsolicited
		 * SC Status changed
		 * SN Send order/service number
		 * SR Response to send order/service status request
		 * SS Send order/service status request
		 * UA Unable to accept order/service
		 * UC Unable to cancel
		 * UD Unable to discontinue
		 * UF Unable to refill
		 * UH Unable to put on hold
		 * UM Unable to replace
		 * UN Unlink order/service from patient care problem or goal
		 * UR Unable to release
		 * UX Unable to change
		 * XO Change order/service request
		 * XR Changed as requested
		 * XX Order/service changed, unsol.
		 */
		$this->setField(1, 'ID', 2, true);
		$this->setField(2, 'EI', 22);
		$this->setField(3, 'EI', 22);
		$this->setField(4, 'EI', 22);
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
		$this->setField(5, 'ID', 2);
		/**
		 * ORC-6 Response Flag
		 * E    Report exceptions only
		 * R    Same as E, also Replacement and Parent-Child
		 * D    Same as R, also other associated segments
		 * F    Same as D, plus confirmations explicitly
		 * N    Only the MSA segment is returned
		 */
		$this->setField(6, 'ID', 1);
		$this->setField(7, 'TQ', 200, false, true);
		$this->setField(8, 'EIP', 200);
		$this->setField(9, 'TS', 26);
		$this->setField(10, 'XCN', 250, false, true);
		$this->setField(11, 'XCN', 250, false, true);
		$this->setField(12, 'XCN', 250, false, true);
		$this->setField(13, 'PL', 80);
		$this->setField(14, 'XTN', 250, false, true);
		$this->setField(15, 'TS', 26);
		$this->setField(16, 'CE', 250);
		$this->setField(17, 'CE', 250);
		$this->setField(18, 'CE', 250);
		$this->setField(19, 'XCN', 250, false, true);
		/**
		 * ORC-20 Advanced Beneficiary Notice Code
		 * 1    Service is subject to medical necessity procedures
		 * 2    Patient has been informed of responsibility, and agrees to pay for service
		 * 3    Patient has been informed of responsibility, and asks that the payer be billed
		 * 4    Advanced Beneficiary Notice has not been signed
		 */
		$this->setField(20, 'CE', 250);
		$this->setField(21, 'XON', 250, false, true);
		$this->setField(22, 'XAD', 250, false, true);
		$this->setField(23, 'XTN', 250, false, true);
		$this->setField(24, 'XAD', 250, false, true);
		$this->setField(25, 'CWE', 250);
		$this->setField(26, 'CWE', 60);
		$this->setField(27, 'TS', 26);
		$this->setField(28, 'CWE', 1);
		/**
		 * ORC – 29 Order Type
		 * I    Inpatient Order
		 * O    Outpatient Order
		 */
		$this->setField(29, 'CWE', 250);
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
		$this->setField(30, 'CNE', 250);
		$this->setField(31, 'CWE', 250);
	}
}