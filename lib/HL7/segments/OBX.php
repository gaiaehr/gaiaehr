<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');

class OBX extends Segments{

	protected $children = array('NTE');

	protected $dynamicFields = array(
		// field OBX-2 defines OBX-5
		'2' => '5'
	);

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'OBX';
		$this->rawSeg[1] = $this->getType('SI');
		/**
		 * OBX-2 Value Type
		 * AD   Address
		 * CE   Coded Entry
		 * CF   Coded Element With Formatted Values
		 * CK   Composite ID With Check Digit
		 * CN   Composite ID And Name
		 * CP   Composite Price
		 * CX   Extended Composite ID With Check Digit
		 * DT   Date
		 * ED   Encapsulated Data
		 * FT   Formatted Text (Display)
		 * MO   Money
		 * NM   Numeric
		 * PN   Person Name
		 * RP   Reference Pointer
		 * SN   Structured Numeric
		 * ST   String Data.
		 * TM
		 * TN
		 * TS
		 * TX
		 * XAD
		 * XCN
		 * XON
		 * XPN
		 * XTN
		 */
		$this->rawSeg[2] = $this->getType('ID');
		$this->rawSeg[3] = $this->getType('CE');
		$this->rawSeg[4] = $this->getType('ST');
		$this->rawSeg[5] = 2; // OBX-2-value type contains the data type for this field
		$this->rawSeg[6] = $this->getType('CE');
		$this->rawSeg[7] = $this->getType('ST');
		/**
		 * OBX-8 Abnormal Flags
		 * L    Below low normal
		 * H    Above high normal
		 * LL   Below lower panic limits
		 * HH   Above upper panic limits
		 * <    Below absolute low-off instrument scale
		 * >    Above absolute high-off instrument scale
		 * N    Normal (applies to non-numeric results)
		 * A    Abnormal (applies to non-numeric results)
		 * AA   Very abnormal (applies to non-numeric units, analogous to panic limits for numeric units)
		 * null No range defined, or normal ranges don't apply
		 * U    Significant change up
		 * D    Significant change down
		 * B    Better--use when direction not relevant
		 * W    Worse--use when direction not relevant
	 	 * S    Susceptible. Indicates for microbiology susceptibilities only.
		 * R    Resistant. Indicates for microbiology susceptibilities only.
		 * I    Intermediate. Indicates for microbiology susceptibilities only.
		 * MS   Moderately susceptible. Indicates for microbiology susceptibilities only.
		 * VS   Very susceptible. Indicates for microbiology susceptibilities only.
		 */
		$this->rawSeg[8] = $this->getType('IS');
		$this->rawSeg[9] = $this->getType('NM');
		/**
		 * OBX-10 Nature of abnormal test
		 * A    An age-based population
		 * N    None - generic normal range
		 * R    A race-based population
		 * S    A sex-based population
		 * SP   Species
		 * B    Breed
		 * ST   Strain
		 */
		$this->rawSeg[10] = $this->getType('ID');
		/**
		 * C    Record coming over is a correction and thus replaces a final result
		 * D    Deletes the OBX record
		 * F    Final results; Can only be changed with a corrected result.
		 * I    Specimen in lab; results pending
		 * N    Not asked; used to affirmatively document that the observation identified in the OBX was not sought when the universal service ID in OBR-4 implies that it would be sought.
		 * O    Order detail description only (no result)
		 * P    Preliminary results
		 * R    Results entered -- not verified
		 */
		$this->rawSeg[11] = $this->getType('ID');
		$this->rawSeg[12] = $this->getType('TS');
		$this->rawSeg[13] = $this->getType('ST');
		$this->rawSeg[14] = $this->getType('TS');
		$this->rawSeg[15] = $this->getType('CE');
		$this->rawSeg[16] = $this->getType('XCN');
		$this->rawSeg[17] = $this->getType('CE');
		$this->rawSeg[18] = $this->getType('EI');
		$this->rawSeg[19] = $this->getType('CX');
		$this->rawSeg[20] = null; // Reserved for harmonization with V2.6
		$this->rawSeg[21] = null; // Reserved for harmonization with V2.6
		$this->rawSeg[22] = null; // Reserved for harmonization with V2.6
		$this->rawSeg[23] = $this->getType('XON');
		$this->rawSeg[24] = $this->getType('XAD');
		$this->rawSeg[25] = $this->getType('XCN');

		parent::__construct($hl7);
	}
}