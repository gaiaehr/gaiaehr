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
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

if(isset($_SESSION['user']) && $_SESSION['user']['auth'] == true){
	/**
	 * init Matcha
	 */
	require_once(dirname(dirname(__FILE__)).'/classes/MatchaHelper.php');
	new MatchaHelper();
	$d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');

	if(!isset($_REQUEST['doc'])){
		print 'No Document Found, Please contact Support Desk. Thank You!';
		exit;
	}

	$doc = $d->load($_REQUEST['doc'])->one();

	if($doc === false){
		print 'No Document Found, Please contact Support Desk. Thank You!';
		exit;
	}

	$path = $_SESSION['site']['path'] . '/patients/' . $doc['pid'] . '/' . strtolower(str_replace(' ', '_', $doc['docType'])) . '/' . $doc['name'];

	function get_mime_type($file)
	{

		$mime_types = array(
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
			"html" => "text/html"
		);

		$foo = explode('.', $file);
		$extension = strtolower(end($foo));
		return $mime_types[$extension];
	}

	if($doc['encrypted'] == true){
		$content = MatchaUtils::__decrypt(file_get_contents($path));
	}else{
		$content =  file_get_contents($path);
	}
	header('Content-Type: '. get_mime_type($doc['name']));
	header('Content-Disposition: inline; filename="' . $doc['name'] . '"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . strlen($content));
	header('Accept-Ranges: bytes');
	print $content;

} else{
	print 'Not Authorized to be here, Please contact Support Desk. Thank You!';
}



