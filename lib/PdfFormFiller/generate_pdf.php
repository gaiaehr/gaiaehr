<?php
// (C) copyleft AGPL license, http://itextpdf.com/terms-of-use/agpl.php, Nikolay Kitsul.

// Note: Use proper escaping functions: pdfff_escape($str), pdfff_checkbox($value).

$error_level = error_reporting();
error_reporting($error_level ^ E_NOTICE);
$error = 0;

// Do not pass parameters from $_REQUEST to proc_open() without validation!
$allowed_files = array('test', 'pdf_file_2');

if (in_array($_REQUEST['file'], $allowed_files))
    $file = $_REQUEST['file'] . '.pdf';
else {
    echo 'Unknown file.';
    exit;
}

// Process parameters.
if ($_REQUEST['flatten']){
	$flatten = true;
}else {
	$flatten = false;
}

if ($_REQUEST['pff_op'] == 'list')
    $operation = "list";
else if ($_REQUEST['pff_op'] == 'env_vars')
    $operation = "env_vars";
else if ($_REQUEST['pff_op'] == 'dump_fields')
    $operation = "dump_fields";
else 
    $operation = null;

switch ($_REQUEST['file']) {
    case 'pdf_file_1':
        $fill_func = 'fill_pdf_file_1';
        break;
    case 'history_2':
        $fill_func = 'fill_pdf_file_2';
        break;
}

pdfff_fill_pdffile_and_dump_to_http($file, 'fill_pdf_file_1', $operation, $flatten);

error_reporting($error_level);

/**
 * Fills a pdf file <var>$file</var> with the function provided <var>$fill_func</var> and dumps it to http for the browser to download.
 *
 * @param string $file pdf filename to fill.
 * @param string $fill_func - function name of a function that will fills pdf file by writing to a pipe.
 * @param string $operation   'fill' - DEFAULT. Fill pdf file <var>$file</var> with what is in function <var>$fill_func</var>. <br>
 *                          'list' - List all fields that are available in pdf file $file. <br>
 *                          'env_vars' - List shell enviroment variables. Useful if returned error code is 127 (when paths are wrong).<br>
 *                          'dump_fields' - Dump fields, submitted to be submitted to phpformfiller, to browser. Useful if Unix Shell encoding is wrong.<br>
 *
 * @param boolean $flatten - produce "flat" pdf, that is its forms will not be editable any more.
 */
function pdfff_fill_pdffile_and_dump_to_http($file, $fill_func, $operation = 'fill', $flatten = false) {
    $DO_DUMP = false;

    if ($flatten)
        $flatten = " -flatten";
    else
        $flatten = "";

//putenv('LANG=en_US.UTF-8');
    $env = array('LANG' => 'en_US.UTF-8');
    $path = null;
// On FreeBSD open_proc() is not run in a shell and
// thus a PATH with 'usr/local/bin' and CWD are not set.
// $env['PATH'] = '/usr/bin:/bin:/usr/local/bin';
// $path = '/var/www/linux.org/site/htroot/doc';

    if ($operation == 'list') {
        // List all fields that are available in pdf file $file.
        $cmd = 'java -jar pdfformfiller.jar ' . $file . ' -l 2>&1';
        $DO_DUMP = true; // send error as text to browser (not as a file to download via http).
    } else if ($operation == 'env_vars') {
        $cmd = 'set';
        $DO_DUMP = true;
    } else if ($operation == 'dump_fields') {
        $cmd = 'cat';
        $DO_DUMP = true;
    } else
        $cmd = 'java -jar pdfformfiller.jar ' . $file . ' -font "/Applications/MAMP/htdocs/gaiaehr/resources/fonts/Merriweather.ttf"' .
                $flatten . ' 2>&1';

    $descriptorspec = array(
        0 => array("pipe", "r"), // stdin is a pipe that the child will read from
        1 => array("pipe", "w"), // stdout is a pipe that the child will write to
        // 2 => array("file", "error-output.txt", "a") // stderr is a file to write to
        2 => array("pipe", "w")  // Actually, stderr is sent to stdin " 2>&1" 
            // in $cmd above, as select() in php is not reliable.
    );

    $f = proc_open($cmd, $descriptorspec, $pipes, $path, $env /* [, array $other_options ] */);
    $error |= $f === false;

    if (!$operation || $operation == 'fill')
        $error |= $fill_func($pipes[0]);

    $error |= false === fclose($pipes[0]);

    $result = stream_get_contents($pipes[1]);
    $error |= false === $result;
    $error |= false === fclose($pipes[1]);

// It is important that you close any pipes before calling
// proc_close in order to avoid a deadlock

    $return_value = proc_close($f);
    $error |= $return_value != 0;

    if (!$error && !$DO_DUMP) {
        // Disable cache.
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        // We'll be outputting a PDF
        header('Content-type: application/pdf');
        header('Content-Transfer-Encoding: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
    } else {
        header("Content-Type: text/plain");
        if ($error) {
            echo "ERROR:\n";
            if ($return_value == -1)
                echo "Error accesing pdfformfiller.\n";
            else
                echo "pdfformfiller returned error code: $return_value\n\n";
        }
    }

    echo $result;
}

function fill_pdf_file_1($pipe) {
    $error = 0;

    $error |= false === fwrite($pipe, '3-1' . ' ' . pdfff_escape('hello') . "\n");
    $error |= false === fwrite($pipe, '3-2' . ' ' . pdfff_escape('hello 2') . "\n");
    $error |= false === fwrite($pipe, 'service-1-24' . ' ' . pdfff_escape('Procedure One') . "\n");
    $error |= false === fwrite($pipe, 'service-2-24' . ' ' . pdfff_escape('Procedure Two') . "\n");
    $error |= false === fwrite($pipe, 'service-3-24' . ' ' . pdfff_escape('Procedure Three') . "\n");

    $error |= false === fwrite($pipe, '1Preauthorization' . ' ' . pdfff_checkbox(true) . "\n");
//    $error |= false === fwrite($pipe, '10-2' . ' ' . pdfff_checkbox(true) . "\n");
    
    return $error;
}

//function fill_pdf_file_2($pipe) {
//    $error = 0;
//
//    $error |= false === fwrite($pipe, 'FAMILY' . ' ' . pdfff_escape($_POST['FAMILY']) . "\n");
//    $error |= false === fwrite($pipe, 'NAME' . ' ' . pdfff_escape($_POST['NAME']) . "\n");
//    $error |= false === fwrite($pipe, 'SECOND_NAME' . ' ' . pdfff_escape($_POST['SECOND_NAME']) . "\n");
//
//    return $error;
//}

function pdfff_escape($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace("\n", "\\n", $str);
//    mb_internal_encoding('UTF-8');
//    mb_regex_encoding('UTF-8');

//    if (($s = preg_replace("\\", "\\\\", $str)) !== false)
//        $str = $s;

//    // U+2028 utf-8 E280A8 : LINE SEPARATOR LS
//    if (($s = preg_replace("\xE2\x80\xA8", "\\n", $str)) !== false)
//        $str = $s;
//
//    //U+2029 utf-8 E280A9 : PARAGRAPH SEPARATOR PS
//    if (($s = preg_replace("\xE2\x80\xA8", "\\p", $str)) !== false)
//        $str = $s;
//
//    // DOS newline
//    if (($s = preg_replace("\r\n", "\\n", $str)) !== false)
//        $str = $s;
//
//    if (($s = preg_replace("\n", "\\n", $str)) !== false)
//        $str = $s;
    return $str;
}

function pdfff_checkbox($value) {
    if ($value)
        return 'Yes';
    return 'Off';
}