<?php
// Loads the language file for the panel Report Center.
include_once('langs/en_US.php');
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="app/view/reports/css/reportCenter.css" >
<body>
	<script type="text/javascript">
	function loadjscssfile(filename, filetype)
	{
		if (filetype=="js")
		{ //if filename is a external JavaScript file
	  		var fileref=document.createElement('script')
	  		fileref.setAttribute("type","text/javascript")
	  		fileref.setAttribute("src", filename)
	 	}
	 	else if (filetype=="css")
	 	{ //if filename is an external CSS file
	  		var fileref=document.createElement("link")
	  		fileref.setAttribute("rel", "stylesheet")
	  		fileref.setAttribute("type", "text/css")
	  		fileref.setAttribute("href", filename)
	 	}
	 	if (typeof fileref!="undefined") document.getElementsByTagName("head")[0].appendChild(fileref)
	}
	</script>
	
	<div class="headerReportCenter">
		<h2>Gaia EHR - <?=$LANG['report_center']?></h2>
	</div>
	
	<div class="CategoryContainer">
		<span class="title"><?=$LANG['patient_reports']?></span>
		<ul>
			<li><a onclick="javascript:loadjscssfile('items/PatientList.js','js');" href="javascript:void(0);"><?=$LANG['patient_list'] ?></a></li>
			<li><?=$LANG['prescriptions_and_dispensations']?></li>
			<li><?=$LANG['clinical']?></li>
			<li><?=$LANG['referrals']?></li>
			<li><?=$LANG['immunization_registry']?></li>
		</ul>
	</div>
	
	<div class="CategoryContainer">
		<span class="title"><?=$LANG['clinic']?></span>
		<ul>
			<li><?=$LANG['standard_measures'] ?></li>
			<li><?=$LANG['clinical_quality_measures_cqm']?></li>
			<li><?=$LANG['automated_measure_calculations_amc']?></li>
			<li><?=$LANG['automated_measure_calculations_amc_tracking']?></li>
		</ul>
	</div>

</html> 