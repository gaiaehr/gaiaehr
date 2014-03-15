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
define ('K_PATH_IMAGES', '');
include_once(dirname(dirname(__FILE__)) . '/lib/tcpdf/tcpdf.php');
include_once(dirname(__FILE__) . '/i18nRouter.php');
include_once(dirname(__FILE__) . '/Facilities.php');

class DocumentPDF extends TCPDF {
	//Page header
	public function Header() {
		// Logo
		$siteLogo = $_SESSION['site']['path'] . '/logo.png';
		$image_file = (file_exists($siteLogo) ? $siteLogo : $_SESSION['root'] . '/resources/images/gaiaehr_small_white.png');
		$y = 16;
		$x = 70;

		$f = new Facilities();
		$facility = $f->getFacility(true);
		$address1 = $facility['name'];
		$address2 = $facility['street'];
		$address3 = $facility['city'] . ', ' . $facility['state'] . ' ' . $facility['postal_code'];

		$phone1  = '';
		$phone2  = 'Tel. ' . $facility['phone'];
		$phone3  = 'Fax ' . $facility['fax'];


		// Logo
		$this->Image($image_file, 10, 13, '', '', 'PNG', '', 'T', false, 1200, '', false, false, 0, false, false, false);
		// Address
		$this->SetFont('helvetica', '', 9);
		$this->SetY($y);
		$this->SetX($x);
		if($address1 != ''){
			$this->Cell(95, 0, $address1, '', false, 'L', 0, '', 0, false, 'M', 'M');
		}
		$this->SetY($y + 4);
		$this->SetX($x);
		if($address2 != ''){
			$this->Cell(95, 0, $address2, '', false, 'L', 0, '', 0, false, 'M', 'M');
		}
		$this->SetY($y + 8);
		$this->SetX($x);
		if($address3 != ''){
			$this->Cell(95, 0, $address3, '', false, 'L', 0, '', 0, false, 'M', 'M');
		}

		// set phones
		$this->SetY($y);
		$this->SetX(165);
		if($phone1 != ''){
			$this->Cell(0, 0, $phone1, '', false, 'R', 0, '', 0, false, 'M', 'M');
		}
		$this->SetY($y + 4);
		$this->SetX(165);
		if($phone2 != ''){
			$this->Cell(0, 0, $phone2, '', false, 'R', 0, '', 0, false, 'M', 'M');
		}
		$this->SetY($y + 8);
		$this->SetX(165);
		if($phone3 != ''){
			$this->Cell(0, 0, $phone3, '', false, 'R', 0, '', 0, false, 'M', 'M');
		}
		$this->Line(10,30,200,30, array('color' => array(0,0,0)));
	}

	// Page footer
	public function Footer(){
		$this->SetLineStyle(array('width' => 0.2, 'color' => array(0,0,0)));
		$this->Line(15, $this->getPageHeight() - 0.5 * 15 - 2, $this->getPageWidth() - 15, $this->getPageHeight() - 0.5 * 15 - 2);
		$this->SetFont('times', '', 8);
		$this->SetY(-0.5 * 15, true);
		$this->Cell(15, 0, 'Created by GaiaEHR (Electronic Health Record) ');
		$this->Cell(333, 0, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

}
