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
		parent::__construct($hl7, 'OBX');

		$this->setField(1, 'SI', 1);
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
		$this->setField(2, 'ID', 1, true);
		$this->setField(3, 'CE', 1);
		$this->setField(4, 'ST', 1);
		$this->setFieldValue(5, 2);  // OBX-2-value type contains the data type for this field
		$this->setField(6, 'CE', 1);
		$this->setField(7, 'ST', 1);

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
		$this->setField(8, 'IS', 1, false, true);
		$this->setField(9, 'NM', 1);
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
		$this->setField(10, 'ID', 1, false, true);
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
		$this->setField(11, 'ID', 1, true);
		$this->setField(12, 'TS', 1);
		$this->setField(13, 'ST', 1);
		$this->setField(14, 'TS', 1);
		$this->setField(15, 'CE', 1);
		$this->setField(16, 'XCN', 1, false, true);
		$this->setField(17, 'CE', 1, false, true);
		$this->setField(18, 'EI', 1, false, true);
		$this->setField(19, 'CX', 1);
		$this->setFieldValue(20, null);
		$this->setFieldValue(21, null);
		$this->setFieldValue(22, null);
		$this->setField(23, 'XON', 1);
		$this->setField(24, 'XAD', 1);
		$this->setField(25, 'XCN', 1);

	}
}