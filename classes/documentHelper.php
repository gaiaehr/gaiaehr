<?php
/**
 * @brief       Document Helper
 * @details     This class is ment to be a mere Help to PHPWord, PHPExcel, PHPPowerPoint libraries
 * 				within this Open Source Software. 
 * 
 * 				It will read HTML data and tries to convert it to DOCX.
 *
 * @author      Gino Rivera (GI Technologies) <grivera@gi-technologies.com>
  * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
 
 
 /*
  * Load the current session
  */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}

/*
 * Include PHP Word, PHP Excel, PHP PowerPoint Class Library.
 */
include_once($_SESSION['site']['root'] . '/lib/PHPWord/PHPWord.php');
include_once($_SESSION['site']['root'] . '/lib/PHExcel/PHPExcel.php');
include_once($_SESSION['site']['root'] . '/lib/PHPPowerPoint/PHPPowerpoint.php');


/*
 * Begin of the Document Helper Class.
 */
class DocumentHelper 
{
	/*
	 * Method to convert any given HTML string to a valid DOCX document.
	 * This document is compatible with LibreOffice Suite.
	 */
	public function HTMLtoDOCX(string $HTML)
	{
		
	}
	
	private function detectParagraph(string $HTML)
	{
		$pattern = "/<p ?.*>(.*)<\/p>/";
		preg_match($pattern, $string, $matches);
		return $matches;
	}
}


?>