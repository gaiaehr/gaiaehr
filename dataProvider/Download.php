<?php
header('Content-Description: File Transfer');
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename=' . $_REQUEST['filename']);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
echo $_REQUEST['payload'];