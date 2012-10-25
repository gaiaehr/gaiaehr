<?php
/*
 GaiaEHR (Electronic Health Records)
 DocumentPDF.php
 Document PDF dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/lib/tcpdf/tcpdf.php');
include_once ($_SESSION['root'] . '/dataProvider/i18nRouter.php');
class DocumentPDF extends TCPDF
{
	//Page header

	// Page footer
	public function Footer()
	{
		$this -> SetLineStyle(array(
			'width' => 0.2,
			'color' => array(
				0,
				0,
				0
			)
		));
		$this -> Line(15, $this -> getPageHeight() - 0.5 * 15 - 2, $this -> getPageWidth() - 15, $this -> getPageHeight() - 0.5 * 15 - 2);
		$this -> SetFont('times', '', 8);
		$this -> SetY(-0.5 * 15, true);
		$this -> Cell(15, 0, 'Created by GaiaEHR (Electronic Health Record) ');
		$this -> Cell(333, 0, 'Page ' . $this -> getAliasNumPage() . '/' . $this -> getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

}
