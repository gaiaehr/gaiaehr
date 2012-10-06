<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="content-language" content="en">
<meta name="description" content="htmltodocx converter documentation">
<title>htmltodocx converter documentation</title>
<link rel="stylesheet" type="text/css" href="h2d_style.css">
</head>
<body>
  <div class="container">

<?php

$showable_files = array(
  'example.php' => '../example.php',
  'docs_styles.inc' => 'docs_styles.inc',
  'example_files/styles.inc' => '../example_files/styles.inc',
);

if (isset($_GET['file']) && array_key_exists($_GET['file'], $showable_files)) {
  $file = $showable_files[$_GET['file']];
  
  print '<h1>File: ' . $file . '</h1>';
  
  $file_contents = file_get_contents($file);
  $code = highlight_string($file_contents, TRUE);
  
  // To facilitate word wrap:
  $code = str_replace('&nbsp;&nbsp;', '%%%nbsp-pair%%%', $code);
  $code = str_replace('&nbsp;', ' ', $code);
  $code = str_replace('%%%nbsp-pair%%%', '&nbsp;&nbsp;', $code);
  
  print $code;
}
else {
  print 'Nothing to display - maybe an incorrect link?'; 
}

?>
</div>
</body>
</html>