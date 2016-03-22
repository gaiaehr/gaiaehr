<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if(!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

if(!isset($_REQUEST['token']) || str_replace(' ', '+', $_REQUEST['token']) != $_SESSION['user']['token']) die('Not Authorized!');

if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);
require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
require_once(ROOT . '/sites/'. $_REQUEST['site'] .'/conf.php');

ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 5);

if(isset($_SESSION['user']) && $_SESSION['user']['auth'] == true)
{
	/**
	 * init Matcha
	 */
	require_once(ROOT . '/classes/MatchaHelper.php');
    require_once(ROOT . '/dataProvider/TransactionLog.php');
	new MatchaHelper();

	if(!isset($_REQUEST['id'])) die('');

	function get_mime_type($file)
    {
		$mime_types = [
			"pdf" => "application/pdf",
			"exe" => "application/octet-stream",
			"zip" => "application/zip",
			"docx" => "application/msword",
			"doc" => "application/msword",
			"xls" => "application/vnd.ms-excel",
			"ppt" => "application/vnd.ms-powerpoint",
			"gif" => "image/gif",
			"png" => "image/png",
			"jpeg" => "image/jpg",
			"jpg" => "image/jpg",
			"bmp" => "image/bmp",
			"mp3" => "audio/mpeg",
			"wav" => "audio/x-wav",
			"mpeg" => "video/mpeg",
			"mpg" => "video/mpeg",
			"mpe" => "video/mpeg",
			"mov" => "video/quicktime",
			"avi" => "video/x-msvideo",
			"3gp" => "video/3gpp",
			"css" => "text/css",
			"jsc" => "application/javascript",
			"js" => "application/javascript",
			"php" => "text/html",
			"htm" => "text/html",
			"html" => "text/html",
			"xml" => "text/xml"
		];

		$foo = explode('.', $file);
		$extension = strtolower(end($foo));
		return isset($mime_types[$extension]) ? $mime_types[$extension] : '';
	}

	function base64ToBinary($document, $encrypted, $is_image)
    {
		if($encrypted == true)
        {
			$document = MatchaUtils::decrypt($document);
		}
		if(!$is_image)
        {
			$document = base64_decode($document);
		}
		return $document;
	}

	$isTemp = isset($_REQUEST['temp']);

	$TransactionLog = new TransactionLog();

	if($isTemp)
    {
		$d = MatchaModel::setSenchaModel('App.model.patient.PatientDocumentsTemp');

		$doc = $d->load($_REQUEST['id'])->one();
		if($doc === false)
        {
            error_log('No Document Found, Please contact Support Desk. Thank You!');
            die('No Document Found, Please contact Support Desk. Thank You!');
		}
		$doc = (object) $doc;
		$doc->name = isset($doc->document_name) && $doc->document_name != '' ? $doc->document_name : 'temp.pdf';
		$doc->is_temp = 'true';
		$mineType = get_mime_type($doc->name);
		$is_image = preg_match('/^image/', $mineType);
		$document = base64ToBinary($doc->document, false, $is_image);
        $TransactionLog->saveTransactionLog([
                'event' => 'EXPORT',
                'data' => 'Generated a PDF'
        ]);
	}
    else
    {
		$d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');
		$doc = $d->load($_REQUEST['id'])->one();
		if($doc === false)
        {
            error_log('No Document Found, Please contact Support Desk. Thank You!');
			die('No Document Found, Please contact Support Desk. Thank You!');
		}
		$doc = (object) $doc;
		$doc->is_temp = 'false';
		$mineType = get_mime_type($doc->name);
		$is_image = preg_match('/^image/', $mineType);

		if(isset($doc->document_instance))
        {
			$dd = MatchaModel::setSenchaModel('App.model.administration.DocumentData', false, $doc->document_instance);
			$data = $dd->load($doc->document_id)->one();
			if($data == false)
            {
                error_log('No Document Found, Please contact Support Desk. Thank You!');
				die('No Document Data Found, Please contact Support Desk. Thank You!');
			}
			$data = (object) $data;
			$document = base64ToBinary($data->document, $doc->encrypted, $is_image);
            $TransactionLog->saveTransactionLog([
                'event' => 'VIEW',
                'data' => 'Generated and viewed image'
            ]);
		}
        else
        {
			$document = base64ToBinary($doc->document, $doc->encrypted, $is_image);
            $TransactionLog->saveTransactionLog([
                'event' => 'VIEW',
                'data' => 'Generated and viewed image'
            ]);
		}
	}

	unset($TransactionLog);

	if($is_image){

		$html = <<<HTML
			<!doctype html>
			<html>
				<head>
				    <meta charset="UTF-8">
				    <link rel="stylesheet" href="../lib/darkroomjs/build/css/darkroom.min.css">
				</head>
				<body style="overflow: hidden">
			        <div class="image-container target">
				        <img src="data:{$mineType};base64,{$document}" style="width:100%;" alt="" id="target" crossOrigin="anonymous">
			        </div>
					<script src="../lib/darkroomjs/vendor/fabric.js" data-illuminations="true"></script>
					<script src="../lib/darkroomjs/build/js/darkroom.min.js" data-illuminations="true"></script>
					<script data-illuminations="true">

				    var dkrm = new Darkroom('#target', {
				        plugins: {
					        save: '$doc->is_temp' == 'true' ? false : {
					            callback: function(){
			                        var msg = 'documentedit{"save":{"id":{$doc->id},"document":"'+dkrm.snapshotImage()+'" }}';
			                        window.parent.postMessage(msg, '*');
					            }
					        },
					        crop: {
					            quickCropKey: 67
				            }
			            }
				    });
				  </script>
				</body>
			</html>
HTML;

		print $html;

	}
    else
    {
		header('Content-Type: ' . $mineType, true);
		header('Content-Disposition: inline; filename="' . $doc->name . '"');
		header('Content-Transfer-Encoding: BINARY');
		header('Content-Length: ' . strlen($document));
		header('Accept-Ranges: bytes');
		print $document;
	}

}
else
{
	print 'Not Authorized to be here, Please contact Support Desk. Thank You!';
}



