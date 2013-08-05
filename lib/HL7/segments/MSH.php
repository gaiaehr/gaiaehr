<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class MSH extends Segments{

	function __construct(){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'MSH';               // MSH Message Header Segment
		$this->rawSeg[1] = '|';                 // MSH-1 Field Separator (ST)
		$this->rawSeg[2] = '^~\&';              // MSH-2 Encoding Characters (ST)
		/**
		 * MSH-3 Sending Application (HD)
		 */
		$this->rawSeg[3][0] = $this->getType('HD');     // MSH-3 Sending Application (HD): ^
		/**
		 * MSH-4 Sending Facility (HD)
		 */
		$this->rawSeg[4][0]= $this->getType('HD');      // MSH-4 Sending Facility (HD): ^
		/**
		 * MSH-5 Receiving Application
		 */
		$this->rawSeg[5] = $this->getType('HD');        // MSH-5 Receiving Application (HD): ^
		/**
		 * MSH-6 Receiving Facility (HD)
		 */
		$this->rawSeg[6]= $this->getType('HD');         // MSH-5 Receiving Facility (HD): ^
		/**
		 * MSH-7 Date/Time Of Message (TS)
		 */
		$this->rawSeg[7] = date('YmdHis');
		/**
		 * MSH-8 Security (ST)
		 */
		$this->rawSeg[8] = '';
		/**
		 * MSH-9 Message Type (MSG)
		 */
		$this->rawSeg[9] = $this->getType('MSG');
		/**
		 * MSH-10 Message Structure (ID)
		 */
		$this->rawSeg[10] = $this->newUID();
		/**
		 * MSH-11 Processing ID (PT)
		 */
		$this->rawSeg[11]= $this->getType('PT');
		/**
		 * MSH-12 Version
		 */
		$this->rawSeg[12][0] = $this->getType('VID');




	}
}