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

include_once(dirname(__FILE__) . '/../classes/Crypt.php');
include_once(dirname(__FILE__) . '/Documents.php');
include_once(dirname(__FILE__) . '/DoctorsNotes.php');

class DocumentHandler {
	private $db;
	private $documents;

	private $pid;
	private $docType;
	private $workingDir;
	private $fileName;

	/**
	 * @var MatchaCUP
	 */
	private $d = null;
	private $doctorsnotes;

	function __construct(){
		$this->db = new MatchaHelper();
		return;
	}

	private function setPatientDocumentModel(){
		if(!isset($this->d))
			$this->d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');
	}

	public function createDocument($params){
		$this->setPatientDocumentModel();

		$params = (object)$params;
		$path = $this->getPatientDir($params) . $this->nameFile();

		$this->documents = new Documents();
		$this->documents->PDFDocumentBuilder($params, $path);

		if(file_exists($path)){

			$data = new stdClass();
			$data->pid = $this->pid;
			$data->eid = (isset($params->eid) ? $params->eid : '0');
			$data->uid = (isset($params->uid) ? $params->uid : $_SESSION['user']['id']);
			$data->docType = $this->docType;
			$data->name = $this->fileName;
			$data->url = $this->getDocumentUrl();
			$data->date = date('Y-m-d H:i:s');
			$data->hash = sha1_file($path);

			$data = $this->d->save($data);

			if(isset($params->DoctorsNote)){
				$this->doctorsnotes = new DoctorsNotes();
				$this->doctorsnotes->addDoctorsNotes($params);
			}

			//print_r($data);

			return array('success' => true, 'doc' => array('id' => $data['data']->id, 'name' => $this->fileName, 'url' => $this->getDocumentUrl(), 'path' => $path));
		} else{
			return array('success' => false, 'error' => 'Document could not be created');
		}
	}

	// TODO: rename this function to uploadPatientDocument()
	public function uploadDocument($params, $file){
		$this->setPatientDocumentModel();

		$params = (object)$params;
		$src = $this->getPatientDir($params) . $this->reNameFile($file);
		if(move_uploaded_file($file['filePath']['tmp_name'], $src)){

			if(isset($params->encrypted) && $params->encrypted){
				file_put_contents($src, Crypt::encrypt(file_get_contents($src)), LOCK_EX);
			}

			$data = new stdClass();
			$data->pid = $this->pid;
			$data->eid = (isset($params->eid) ? $params->eid : 0);
			$data->uid = (isset($params->uid) ? $params->uid : $_SESSION['user']['id']);
			$data->docType = $this->docType;
			$data->name = $this->fileName;
			$data->url = $this->getDocumentUrl();
			$data->date = date('Y-m-d H:i:s');
			$data->hash = sha1_file($src);
			$data->encrypted = $params->encrypted;
			$data = $this->d->save($data);

			return array('success' => true, 'doc' => array('id' => $data['id'], 'name' => $this->fileName, 'url' => $this->getDocumentUrl()));
		} else{
			return array('success' => false, 'error' => 'File could not be uploaded');
		}
	}

	public function deleteDocumentById($id){
		$path = $this->getDocumentPathById($id);
		if(unlink($path)){
			$this->db->setSQL("DELETE FROM patient_documents WHERE id = '$id'");
			$this->db->execLog();
			return true;
		} else{
			return false;

		}

	}

	protected function getDocumentUrl(){
		return $_SESSION['site']['url'] . '/patients/' . $this->pid . '/' . strtolower(str_replace(' ', '_', $this->docType)) . '/' . $this->fileName;
	}

	public function getDocumentPathById($id){
		$this->db->setSQL("SELECT * FROM patient_documents WHERE id = '$id'");
		$doc = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $_SESSION['site']['path'] . '/patients/' . $doc['pid'] . '/' . strtolower(str_replace(' ', '_', $doc['docType'])) . '/' . $doc['name'];
	}

	protected function reNameFile($file){
		$foo = explode('.', $file['filePath']['name']);
		$ext = end($foo);
		return $this->fileName = $this->setName() . '.' . $ext;
	}

	protected function nameFile(){
		return $this->fileName = $this->setName() . '.pdf';
	}

	protected function setName(){
		$name = time();
		while(file_exists($this->workingDir . '/' . $name)){
			$name = time();
		}
		return $name;
	}

	protected function getPatientDir($params){
		if(is_array($params)){
			$this->pid = (isset($params['pid'])) ? $params['pid'] : $_SESSION['patient']['pid'];
			$this->docType = (isset($params['docType'])) ? $params['docType'] : 'orphanDocuments';
		} else{
			$this->pid = (isset($params->pid)) ? $params->pid : $_SESSION['patient']['pid'];
			$this->docType = (isset($params->docType)) ? $params->docType : 'orphanDocuments';
		}
		$path = $_SESSION['site']['path'] . '/patients/' . $this->pid . '/' . strtolower(str_replace(' ', '_', $this->docType)) . '/';
		if(is_dir($path) || mkdir($path, 0777, true)){
			chmod($path, 0777);
		}
		return $this->workingDir = $path;
	}

	public function getDocumentsTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'documenttemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function getDefaultDocumentsTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'defaulttemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function getHeadersAndFootersTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'headerorfootertemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function addDocumentsTemplates(stdClass $params){
		$data = get_object_vars($params);
		$data['created_by_uid'] = $_SESSION['user']['id'];
		$this->db->setSQL($this->db->sqlBind($data, 'documents_templates', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateDocumentsTemplates(stdClass $params){
		$data = get_object_vars($params);
		$data['update_by_uid'] = $_SESSION['user']['id'];
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'documents_templates', 'U', array('id' => $params->id)));
		$this->db->execLog();
		return $params;

	}

	public function checkDocHash($doc){
		$path = $_SESSION['site']['path'] . '/patients/' . $doc->pid . '/' . strtolower(str_replace(' ', '_', $doc->docType)) . '/' . $doc->name;
		return array('success' => $doc->hash == sha1_file($path), 'msg' => 'Stored Hash:' . $doc->hash . '<br>File hash:' . sha1_file($path));
	}

}

//$d = new DocumentHandler();
//$d->reHashDocs();
