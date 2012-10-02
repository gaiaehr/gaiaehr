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
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}

/*
 * Include PHP Word Class Library.
 */
include_once($_SESSION['site']['root'] . '/lib/PHPWord/PHPWord.php');

/*
 * Include PHP Excel Class Library.
 */
include_once($_SESSION['site']['root'] . '/lib/PHExcel/PHPExcel.php');

/*
 * Include PHP PowerPoint Class Library.
 */
include_once($_SESSION['site']['root'] . '/lib/PHPPowerPoint/PHPPowerpoint.php');


/*
 * Begin of the Document Helper Class.
 */
class DocumentHelper 
{
	public function HTMLtoDOCX(string $HTML)
	{
		
	}
	
	
}


?>