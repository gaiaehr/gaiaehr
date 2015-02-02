<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');

class RXA extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'RXA';
		/**
		 * RXA-1 Give Sub-ID Counter (NM)
		 */
		$this->rawSeg[1] = '0';
		/**
		 * RXA-2 Administration Sub-ID Counter (NM)
		 */
		$this->rawSeg[2] = '1';
		/**
		 * RXA-3 Date/Time Start of Administration (TS)
		 */
		$this->rawSeg[3] = $this->getType('TS');
		/**
		 * RXA-4 Date/Time End of Administration (If Applies) (TS)
		 */
		$this->rawSeg[4] = $this->getType('TS');
		/**
		 * RXA-5 Administered Code (CE)
		 */
		$this->rawSeg[5] = $this->getType('CE');
		/**
		 * RXA-6 Administered Amount (NM)
		 */
		$this->rawSeg[6] = '';
		/**
		 * RXA-7 Administered units (CE)
		 */
		$this->rawSeg[7] = $this->getType('CE');
		/**
		 * RXA-8 Administered Dosage Form (CE)
		 */
		$this->rawSeg[8] = $this->getType('CE');
		/**
		 * RXA-9 Administration Notes (CE)
		 */
		$this->rawSeg[9] = $this->getType('CE');
		/**
		 * RXA-10 Administering Provider (XCN)
		 */
		$this->rawSeg[10] = $this->getType('XCN');
		/**
		 * RXA-11 Administered-at Location (LA2)
		 */
		$this->rawSeg[11] = $this->getType('LA2');
		/**
		 * RXA-12 Administered Per (Time Unit) (ST)
		 * This field contains the rate at which this medication/treatment was administered as calculated
		 * by using RXA-6-administered amount and RXA-7-administered units. This field is conditional because it is
		 * required when a treatment is administered continuously at a prescribed rate, e.g., certain IV solutions
		 */
		$this->rawSeg[12] = '';
		/**
		 * RXA-13 Administered Strength (NM)
		 * Use when RXA-5-Administered Code does not specify the strength.
		 */
		$this->rawSeg[13] = '';
		/**
		 * RXA-14 Administered Strength Units (CE)
		 */
		$this->rawSeg[14] = $this->getType('CE');
		/**
		 * RXA-15 Substance Lot Number (ST)
		 * Note: The lot number is the number printed on the label attached to the container holding the substance
		 * and on the packaging which houses the container. If the substance is a vaccine, for example, and a diluent
		 * is required, a lot number may appear on the vial containing the diluent; however, any such identifier
		 * associated with a diluent is not the identifier of interest. The substance lot number should be reported, not
		 * that of the diluent.
		 */
		$this->rawSeg[15] = '';
		/**
		 * RXA-16 Substance Expiration Date (TS)
		 */
		$this->rawSeg[16] = $this->getType('TS');
		/**
		 * RXA-17 Substance Manufacturer Name (CE)
		 */
		$this->rawSeg[17] = $this->getType('CE');
		/**
		 * RXA-18 Substance/Treatment Refusal Reason (CE)
		 */
		$this->rawSeg[18] = $this->getType('CE');
		/**
		 * RXA-19 Indication (CE)
		 */
		$this->rawSeg[19] = $this->getType('CE');
		/**
		 * RXA-20 Completion Status (ID)
		 * CP Complete
		 * RE Refused
		 * NA Not Administered
		 * PA Partially Administered
		 */
		$this->rawSeg[20] = '';
		/**
		 * RXA-21 Action Code – RXA (ID)
		 * A Add
		 * D Delete
		 * U Update
		 */
		$this->rawSeg[21] = 'A';
		/**
		 * RXA-22 System Entry Date/Time (TS)
		 */
		$this->rawSeg[22] = $this->getType('TS');
		/**
		 * RXA-23 Administered Drug Strength Volume (NM)
		 * Description: This numeric field defines the volume measurement in which the drug strength concentration
		 * is contained. For example, Acetaminophen 120 MG/5ML Elixir means that 120 MG of the drug is in a
		 * solution with a volume of 5 ML , which would be encoded in RXA-13, RXA-14, RXA-23 and RXA-24
		 */
		$this->rawSeg[23] = '';
		/**
		 * RXA-24 Administered Drug Strength Volume Units (CWE)
		 */
		$this->rawSeg[24] = $this->getType('CWE');
		/**
		 * RXA-25 Administered Barcode Identifier
		 */
		$this->rawSeg[25] = $this->getType('CWE');
		/**
		 * Pharmacy Order Type (ID) Table 0480
		 */
		$this->rawSeg[26] = '';

	}
}