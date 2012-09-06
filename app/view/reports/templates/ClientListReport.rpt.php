<?php
/*
 * Client List Report (Patients List)
 * Desc: This is the template for the final layout of the report
 * this also layout the PDF and the HTML.
 */
 
if(!isset($_SESSION)) 
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

//------------------------------------------------------------------------------
// Load one of the most used classes of this application.
// The data base abstraction layer.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');

//------------------------------------------------------------------------------
// Loads up the language file.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root'] . '/langs/' . $_SESSION['site']['localization'] . '.php');

//------------------------------------------------------------------------------
// Load the PDF class.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root']."/lib/dompdf_0-6-0_beta3/dompdf_config.inc.php");

class ReportClass
{
    /**
     * @var dbHelper
     */
    private $db;
	private $dompdf;
	public $html;
    /**
     * Creates the dbHelper instance
     */
    function __construct()
    {
        $this->db = new dbHelper();
		$this->dompdf = new DOMPDF();
        return;
    }
	
	function renderPDF()
	{
		$this->dompdf->load_html($this->html);
		$this->dompdf->render();
		$this->dompdf->stream("my_pdf.pdf", array("Attachment" => 0));
	}
	
}

$pdf = new ReportClass();

ob_start();
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="<?=$_SESSION['site']['root'] ?>/resources/css/printReport.css">
</head>
<body>
<h3>Client List Report (Patient List)</h3>
<table>
	<tr>
		<td>Last Visit</td>
		<td>Patient </td>
		<td>ID</td>
		<td>Street</td>
		<td>City</td>
		<td>State</td>
		<td>Zip</td>
		<td>Home Phone</td>
		<td>Work Phone</td>
	</tr>
</table>
<DIV style="page-break-after:always"></DIV>
<h3>Client List Report (Patient List)</h3>
<ul>
	<li>PHP 5.0+ with the DOM extension enabled.   Note that the domxml PECL extension conflicts with the DOM extension and   must be disabled. </li>
	<li>Some fonts. PDFs internally support   Helvetica, Times-Roman, Courier, Zapf-Dingbats, &amp; Symbol. DOMPDF adds the , but if you wish to   use other fonts or Unicode charsets you will need to install some fonts.   dompdf supports the same fonts as the underlying PDF backends: Type 1   (.pfb with the corresponding .afm) and TrueType (.ttf). At the minimum,   you should probably have the Microsoft core fonts (now available at: <a href="http://corefonts.sourceforge.net/" rel="nofollow">http://corefonts.sourceforge.net/</a>). See below for font installation instructions.</li>
</ul>
</body>
</html>

<?php
$pdf->html = ob_get_contents(); 
ob_end_clean();
$pdf->renderPDF();
?>






