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

	protected $children = array('OBR');

	function __construct($hl7){
		parent::__construct($hl7);

		$this->rawSeg = array();
		$this->rawSeg[0] = 'ORC';                   // ROC Segment
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