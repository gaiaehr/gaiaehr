<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/FileManager.php');
include_once ($_SESSION['root'] . '/dataProvider/DocumentPDF.php');

class Reports
{
	protected $fileManager;
	protected $pdf;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct()
	{
		$this -> fileManager = new FileManager();
		$this -> pdf = new DocumentPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		return;
	}

	public function ReportBuilder($html, $fontsize = 12)
	{
		$fileName = $this -> fileManager -> getTempDirAvailableName() . '.pdf';
		$this -> pdf -> SetCreator('TCPDF');
		$this -> pdf -> SetAuthor($_SESSION['user']['name']);
		$siteLogo = $_SESSION['site']['path'] . '/logo.jpg';
		$logo = (file_exists($siteLogo) ? $siteLogo : $_SESSION['root'] . '/resources/images/logo.jpg');

		// TODO: set from admin area
		$this -> pdf -> SetHeaderData($logo, '20', 'Ernesto\'s Clinic', "Cond. Capital Center\nPDO Suite 205\nAve. Arterial Hostos 239                                                                                                                                   Tel: 787-787-7878\nCarolina PR. 00987                                                                                                                                         Fax: 787-787-7878");
		//need to be change
		$this -> pdf -> setHeaderFont(Array(
			'helvetica',
			'',
			10
		));
		$this -> pdf -> setFooterFont(Array(
			'helvetica',
			'',
			8
		));
		$this -> pdf -> SetDefaultMonospacedFont('courier');
		$this -> pdf -> SetMargins(15, 27, 15);
		$this -> pdf -> SetHeaderMargin(5);
		$this -> pdf -> SetFooterMargin(10);
		$this -> pdf -> SetFontSize($fontsize);
		$this -> pdf -> SetAutoPageBreak(true, 25);
		$this -> pdf -> setFontSubsetting(true);
		$this -> pdf -> AddPage();
		$this -> pdf -> writeHTML($html, true, false, false, false, '');
		$this -> pdf -> Output($_SESSION['site']['temp']['path'] . '/' . $fileName, 'F');
		$this -> pdf -> Close();
		return $_SESSION['site']['temp']['url'] . '/' . $fileName;
	}

}

//
//$r = new Reports();
//$r->PDFDocumentBuilder('<p>asdasdasdasd</p>');
