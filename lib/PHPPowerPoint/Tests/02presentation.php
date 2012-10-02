<?php
/**
 * PHPPowerPoint
 *
 * Copyright (c) 2009 - 2010 PHPPowerPoint
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPPowerPoint
 * @package    PHPPowerPoint
 * @copyright  Copyright (c) 2009 - 2010 PHPPowerPoint (http://www.codeplex.com/PHPPowerPoint)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    0.1.0, 2009-04-27
 */

/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '../Classes/');

/** PHPPowerPoint */
include 'PHPPowerPoint.php';

/** PHPPowerPoint_IOFactory */
include 'PHPPowerPoint/IOFactory.php';

// Create new PHPPowerPoint object
echo date('H:i:s') . " Create new PHPPowerPoint object\n";
$objPHPPowerPoint = new PHPPowerPoint();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPPowerPoint->getProperties()->setCreator("Maarten Balliauw");
$objPHPPowerPoint->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPPowerPoint->getProperties()->setTitle("Office 2007 PPTX Test Document");
$objPHPPowerPoint->getProperties()->setSubject("Office 2007 PPTX Test Document");
$objPHPPowerPoint->getProperties()->setDescription("Test document for Office 2007 PPTX, generated using PHP classes.");
$objPHPPowerPoint->getProperties()->setKeywords("office 2007 openxml php");
$objPHPPowerPoint->getProperties()->setCategory("Test result file");

// Remove first slide
echo date('H:i:s') . " Remove first slide\n";
$objPHPPowerPoint->removeSlideByIndex(0);

// Create templated slide
echo date('H:i:s') . " Create templated slide\n";
$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function


// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(200);
$shape->setWidth(600);
$shape->setOffsetX(10);
$shape->setOffsetY(400);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('Introduction to');
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('PHPPowerPoint');
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(60);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );


// Create templated slide
echo date('H:i:s') . " Create templated slide\n";
$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(100);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(10);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('What is PHPPowerPoint?');
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(48);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(600);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(100);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('- A class library');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('- Written in PHP');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('- Representing a presentation');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('- Supports writing to different file formats');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );


// Create templated slide
echo date('H:i:s') . " Create templated slide\n";
$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(100);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(10);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('What\'s the point?');
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(48);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(600);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(100);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('- Generate slide decks');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - Represent business data');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - Show a family slide show');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - ...');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('- Export these to different formats');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - PowerPoint 2007');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - Serialized');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    - ... (more to come) ...');
$textRun->getFont()->setSize(28);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );


// Create templated slide
echo date('H:i:s') . " Create templated slide\n";
$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(100);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(10);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('Need more info?');
$textRun->getFont()->setBold(true);
$textRun->getFont()->setSize(48);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

// Create a shape (text)
echo date('H:i:s') . " Create a shape (rich text)\n";
$shape = $currentSlide->createRichTextShape();
$shape->setHeight(600);
$shape->setWidth(930);
$shape->setOffsetX(10);
$shape->setOffsetY(100);
$shape->getAlignment()->setHorizontal( PHPPowerPoint_Style_Alignment::HORIZONTAL_LEFT );

$textRun = $shape->createTextRun('Check the project site on CodePlex:');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );

$shape->createBreak();

$textRun = $shape->createTextRun('    http://phppowerpoint.codeplex.com');
$textRun->getFont()->setSize(36);
$textRun->getFont()->setColor( new PHPPowerPoint_Style_Color( 'FFFFFFFF' ) );


// Create templated slide
echo date('H:i:s') . " Create templated slide\n";
$currentSlide = createTemplatedSlide($objPHPPowerPoint); // local function


// Save PowerPoint 2007 file
echo date('H:i:s') . " Write to PowerPoint2007 format\n";
$objWriter = PHPPowerPoint_IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
$objWriter->save(str_replace('.php', '.pptx', __FILE__));

// Echo memory peak usage
echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";



/**
 * Creates a templated slide
 * 
 * @param PHPPowerPoint $objPHPPowerPoint
 * @return PHPPowerPoint_Slide
 */
function createTemplatedSlide(PHPPowerPoint $objPHPPowerPoint)
{
	// Create slide
	$slide = $objPHPPowerPoint->createSlide();
	
	// Add background image
    $shape = $slide->createDrawingShape();
    $shape->setName('Background');
    $shape->setDescription('Background');
    $shape->setPath('./images/realdolmen_bg.jpg');
    $shape->setWidth(950);
    $shape->setHeight(720);
    $shape->setOffsetX(0);
    $shape->setOffsetY(0);
    
    // Add logo
    $shape = $slide->createDrawingShape();
    $shape->setName('PHPPowerPoint logo');
    $shape->setDescription('PHPPowerPoint logo');
    $shape->setPath('./images/phppowerpoint_logo.gif');
    $shape->setHeight(40);
    $shape->setOffsetX(10);
    $shape->setOffsetY(720 - 10 - 40);
    
    // Return slide
    return $slide;
}
