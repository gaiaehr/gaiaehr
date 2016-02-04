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
class File {

	/**
	 * @var bool
	 */
	private $error = false;
	/**
	 * @var string
	 */
	private $errorMsg = '';

	/**
	 * @var MatchaCUP
	 */
	private $d;

	/**
	 * @var bool
	 */
	private $encrypt = false;

	private $fileSystemStore = false;

	function __construct(){
        if(!isset($this->d))
            $this->d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');
		$this->encrypt = isset($_SESSION['globals']['enable_document_encryption']) && $_SESSION['globals']['enable_document_encryption'];
	}

	/**
	 *
	 * $params->pid int
	 * $params->eid int
	 * $params->uid int
	 * $params->docType string
	 * $params->document string Base64
	 *
	 * @param object $params
	 *
	 * @return array
	 */
	public function savePatientBase64Document($params){

		$this->validateParams($params);
		if($this->error) return array('success' => false, 'error' => $this->errorMsg);

		if($this->fileSystemStore){
			$dir = $this->getDocumentDirByPidAndDocType($params->pid, $params->docType);
			if($this->error) return array('success' => false, 'error' => $this->errorMsg);
		}

		$file = explode(',',$params->document);
		$ext = $this->fileExt($file[0]);
		if($this->error) return array('success' => false, 'error' => $this->errorMsg);

		if(isset($dir)){
			$params->name = $this->getNewFileName($dir, $ext);
			if($this->error) return array('success' => false, 'error' => $this->errorMsg);

			$src = $this->saveBase64File($dir . $params->name, $file[1]);
			if($this->error) return array('success' => false, 'error' => $this->errorMsg);
		}

		unset($params->file);

		$params->title = isset($params->title) ? $params->title : '';
		$params->date = date('Y-m-d H:i:s');
		$params->hash = isset($src) ? sha1_file($src) : sha1($file[1]);
		$params->name = isset($params->name) ? $params->name : 'unnamed' . $ext;
		$params->encrypted = $this->encrypt;

		if($this->fileSystemStore){
			$params->url = $this->buildDocumentUrl($params->pid, $params->docType, $params->name);
		}else{
			$params->document = $this->encrypt ? MatchaUtils::__encrypt($file[1]) : $file[1];
		}

		$rec = $this->d->save($params);
		if($rec === false)
            return array('success' => false, 'error' => 'Unable to save document record');

		return array('success' => true, 'id' => $rec['data']->id);

	}

	/**
	 * @param $pid
	 * @param $docType
	 * @return string
	 */
	private function getDocumentDirByPidAndDocType($pid, $docType){
		$dir = site_path . "/patients/$pid/$docType/";
		if(!is_dir($dir)){
			if(mkdir($dir, 0755, true)){
				chmod($dir, 0755);
				return $dir;
			} else{
				$this->error = true;
				$this->errorMsg = 'Unable to find ' . $dir;
				return $dir;
			}
		}
		return $dir;
	}

	/**
	 * @param $contentType
	 * @return string
	 */
	public function fileExt($contentType){
		$contentType = preg_replace('/^\w*:|;\w*$/', '', $contentType);
		$map = array(
			'application/pdf' => '.pdf',
			'image/gif' => '.gif',
			'image/jpeg' => '.jpg',
			'image/png' => '.png',
			'text/plain' => '.txt',
			'text/xml' => '.xml'
		);
		if(isset($map[$contentType])){
			return $map[$contentType];
		}
		$this->error = true;
		$this->errorMsg = 'Unsupported document content type '. $contentType;
		return '.txt';
	}

	/**
	 * @param $dir
	 * @param $ext
	 * @return string
	 */
	private function getNewFileName($dir, $ext){
		$name = uniqid() . $ext;
		$tries = 0;
		while(file_exists($dir . $name) || $tries > 20){
			$name = uniqid() . $ext;
			$tries++;
		}

		if($tries > 10){
			$this->error = true;
			$this->errorMsg = 'Unable to find a file name';
		}

		return $name;
	}

	/**
	 * @param $file
	 * @param $data
	 * @return mixed
	 */
	private function saveBase64File($file, $data){
		$data = base64_decode($data);

		if($this->encrypt){
			$data = MatchaUtils::__encrypt($data);
		}

		if(!file_put_contents($file, $data)){
			$this->error = true;
			$this->errorMsg = 'Unable to save ' . $file;
		}
		return $file;
	}

	private function buildDocumentUrl($pid, $docType, $fileName){
		$docType = strtolower(str_replace(' ', '_', $docType));
		$url = site_url;
		return "{$url}/patients/$pid/$docType/$fileName";

	}

	/**
	 * requires params:
	 *
	 * $params->pid int
	 * $params->eid int
	 * $params->uid int
	 * $params->docType string
	 * $params->file string Base64
	 *
	 * @param $params
	 */
	private function validateParams($params){
		if(!isset($params->pid) &&
			!isset($params->eid) &&
			!isset($params->uid) &&
			!isset($params->docType) &&
			!isset($params->document)
		){
			$this->error = true;
			$this->errorMsg = 'One or more parameters missing';
		}

	}

}

