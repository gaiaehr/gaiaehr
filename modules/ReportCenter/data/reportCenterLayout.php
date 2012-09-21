<?php
// Loads the language file for the panel Report Center.
include_once('../langs/en_US.php');
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
			var layout = Ext.getCmp('MainPanel').getLayout();
			layout.setActiveItem(panel_id);	
		}
	</script>

	<div class="CategoryContainer">
		<span class="title"><?=$LANG['patient_reports']?></span>
		<ul>
			<li><a onclick="javascript:displayPanel('panelReportClientList');" href="javascript:void(0);"><?=$LANG['patient_list'] ?></a></li>
			<li><?=$LANG['prescriptions_and_dispensations']?></li>
			<li><?=$LANG['clinical']?></li>
			<li><?=$LANG['referrals']?></li>
			<li><?=$LANG['immunization_registry']?></li>
		</ul>
	</div>
	
	<div class="CategoryContainer">
		<span class="title"><?=$LANG['clinic_reports']?></span>
		<ul>
			<li><?=$LANG['standard_measures'] ?></li>
			<li><?=$LANG['clinical_quality_measures_cqm']?></li>
			<li><?=$LANG['automated_measure_calculations_amc']?></li>
			<li><?=$LANG['automated_measure_calculations_amc_tracking']?></li>
		</ul>
	</div>

</html> 