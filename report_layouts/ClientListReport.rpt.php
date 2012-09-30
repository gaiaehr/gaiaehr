<?php
/*
 * Client List Report (Patients List)
 * Desc: This is the template for the final layout of the report
 * this also layout the PDF and the HTML.
 */

//------------------------------------------------------------------------------
// Start up the session
//------------------------------------------------------------------------------  
if(!isset($_SESSION)) 
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
$params = get_object_vars( json_decode($_GET['params']) );

//------------------------------------------------------------------------------
// Load one of the most used classes of this application.
// The data base abstraction layer.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
$db = new dbHelper();

//------------------------------------------------------------------------------
// Load the PDF class.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root']."/lib/dompdf_0-6-0_beta3/dompdf_config.inc.php");
$pdfDocument = new DOMPDF();

//------------------------------------------------------------------------------
// Loads up the language file.
//------------------------------------------------------------------------------
include_once($_SESSION['site']['root'] . '/langs/' . $_SESSION['site']['localization'] . '.php');

// Get Client List SQL Statement
$sql = "SELECT
			form_data_demographics.title,
			CONCAT(form_data_demographics.fname,' ', form_data_demographics.mname,' ', form_data_demographics.lname) As PatientName,
			form_data_demographics.pid,
			form_data_demographics.city,
			form_data_demographics.address,
			form_data_demographics.state,
			form_data_demographics.zipcode,
			form_data_demographics.home_phone,
			form_data_demographics.work_phone,
			form_data_encounter.close_date
		FROM
			form_data_demographics 
		LEFT JOIN
			form_data_encounter 
		ON
			form_data_demographics.pid = form_data_encounter.pid
		WHERE form_data_encounter.close_date IS NOT NULL ";
if(is_array($dateParameters)) $sql .= "AND close_date BETWEEN " . $dateParameters['start_date'] . " AND " . $dateParameters['end_date'];
$db->setSQL($sql);

//------------------------------------------------------------------------------
// Start buffering, this will record all the HTML code
// to then pass it to the DomPDF class.
//------------------------------------------------------------------------------
ob_start();
$pathCSS = ($params['pdf'] ? $_SESSION['site']['root'] : '../');
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php print $pathCSS ?>/resources/css/printReport.css">
</head>
<body>
<h3>Client List Report (Patient List)</h3>
<table>
	<tr>
		<th><?php print $LANG['last_visit'] ?></th>
		<th><?php print $LANG['patient'] ?></th>
		<th><?php print $LANG['id'] ?></th>
		<th><?php print $LANG['street'] ?></th>
		<th><?php print $LANG['city'] ?></th>
		<th><?php print $LANG['state'] ?></th>
		<th><?php print $LANG['zipcode'] ?></th>
		<th><?php print $LANG['patient_home_phone'] ?></th>
		<th><?php print $LANG['patient_work_phone'] ?></th>
	</tr>
	<?php foreach($db->fetchRecords(PDO::FETCH_ASSOC) as $row) { ?>
	<tr>
		<td><?php print date("m/d/Y", strtotime($params['close_date'])) ?></td>
		<td><?php print $row['PatientName'] ?></td>
		<td><?php print $row['pid'] ?></td>
		<td><?php print $row['address'] ?></td>
		<td><?php print $row['city'] ?></td>
		<td><?php print $row['state'] ?></td>
		<td><?php print $row['zipcode'] ?></td>
		<td><?php print $row['home_phone'] ?></td>
		<td><?php print $row['work_phone'] ?></td>
	</tr>
	<?php } ?>
</table>
<DIV style="page-break-after:always"></DIV>
</body>
</html>

<?php
//------------------------------------------------------------------------------
// Get the HTML content and pass it to the DomPDF class.
// Below this code, there should not be any more HTML code.
//------------------------------------------------------------------------------
$html = ob_get_contents(); 
ob_end_clean();
if($params['pdf'])
{
	$pdfDocument->load_html($html);
	$pdfDocument->render();
	$pdfDocument->stream("ClientList.pdf", array("Attachment" => 0));
}
else 
{
	echo $html;
}

?>






