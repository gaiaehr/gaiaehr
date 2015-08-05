<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace modules\reportcenter\dataProvider;

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

include_once(ROOT . '/classes/FileManager.php');
include_once(ROOT . '/dataProvider/DocumentPDF.php');

class Reports {
	protected $fileManager;
	protected $pdf;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		$this->fileManager = new \FileManager();
		$this->pdf = new \DocumentPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		return;
	}

	public function ReportBuilder($html, $fontsize = 12) {
		$fileName = $this->fileManager->getTempDirAvailableName() . '.pdf';
		$this->pdf->SetCreator('TCPDF');
		$this->pdf->SetAuthor($_SESSION['user']['name']);
		$siteLogo = site_dir . '/logo.jpg';
		$logo = (file_exists($siteLogo) ? $siteLogo : ROOT . '/resources/images/logo.jpg');

		// TODO: set from admin area
		$this->pdf->SetHeaderData($logo, '20', 'Ernesto\'s Clinic', "Cond. Capital Center\nPDO Suite 205\nAve. Arterial Hostos 239                                                                                                                                   Tel: 787-787-7878\nCarolina PR. 00987                                                                                                                                         Fax: 787-787-7878");
		//need to be change
		$this->pdf->setHeaderFont(Array(
			'helvetica',
			'',
			10
		));
		$this->pdf->setFooterFont(Array(
			'helvetica',
			'',
			8
		));
		$this->pdf->SetDefaultMonospacedFont('courier');
		$this->pdf->SetMargins(15, 27, 15);
		$this->pdf->SetHeaderMargin(5);
		$this->pdf->SetFooterMargin(10);
		$this->pdf->SetFontSize($fontsize);
		$this->pdf->SetAutoPageBreak(true, 25);
		$this->pdf->setFontSubsetting(true);
		$this->pdf->AddPage();
		$this->pdf->writeHTML($html, true, false, false, false, '');
		$this->pdf->Output($_SESSION['site']['temp']['path'] . '/' . $fileName, 'F');
		$this->pdf->Close();
		return $_SESSION['site']['temp']['url'] . '/' . $fileName;
	}

}

//
//$r = new Reports();
//$r->PDFDocumentBuilder('<p>asdasdasdasd</p>');
