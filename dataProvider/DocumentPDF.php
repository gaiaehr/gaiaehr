<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['root'] . '/lib/tcpdf/tcpdf.php');
include_once($_SESSION['root'] . '/dataProvider/i18nRouter.php');
class DocumentPDF extends TCPDF {
    //Page header


    // Page footer
    public function Footer() {
        $this->SetLineStyle( array( 'width' => 0.2,'color' => array(0, 0, 0) ) );
            $this->Line( 15, $this->getPageHeight() - 0.5 * 15 - 2,
        $this->getPageWidth() - 15, $this->getPageHeight() - 0.5 * 15 - 2 );
            $this->SetFont('times', '', 8 );
            $this->SetY( -0.5 * 15, true );
            $this->Cell( 15, 0, 'Created by GaiaEHR (Electronic Health Record) ');
	        $this->Cell( 333, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}