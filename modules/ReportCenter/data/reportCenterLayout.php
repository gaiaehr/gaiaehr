<?php
// Loads the language file for the panel Report Center.
if(!isset($_SESSION))
{
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/modules/ReportCenter/langs/en_US.php');
?>
<html>
<head>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="modules/ReportCenter/css/reportCenter.css" >
<body>
	<script>
		function displayPanel(panel_id)
		{
			var layout = app.MainPanel.getLayout();
			layout.setActiveItem(panel_id);	
		}
	</script>

	<div class="CategoryContainer">
		<span class="title"><?php print $LANG['patient_reports']?></span>
		<ul>
			<li><a onclick="javascript:displayPanel('panelReportClientList');" href="javascript:void(0);"><?php print $LANG['patient_list'] ?></a></li>
			<li><?php print $LANG['prescriptions_and_dispensations'] ?></li>
			<li><?php print $LANG['clinical']?></li>
			<li><?php print $LANG['referrals']?></li>
			<li><?php print $LANG['immunization_registry']?></li>
		</ul>
	</div>
	
	<div class="CategoryContainer">
		<span class="title"><?=$LANG['clinic_reports']?></span>
		<ul>
			<li><?php print $LANG['standard_measures'] ?></li>
			<li><?php print $LANG['clinical_quality_measures_cqm']?></li>
			<li><?php print $LANG['automated_measure_calculations_amc']?></li>
			<li><?php print $LANG['automated_measure_calculations_amc_tracking']?></li>
		</ul>
	</div>

</html> 