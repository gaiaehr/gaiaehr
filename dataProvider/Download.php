<?php
header('Content-Description: File Download');
header('Content-Type: '. $_REQUEST['content_type']);
header('Content-Disposition: attachment; filename=' . $_REQUEST['file_name']);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
echo $_REQUEST['payload'];