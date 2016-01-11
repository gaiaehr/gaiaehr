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

include_once(ROOT . '/classes/Crypt.php');
include_once(ROOT . '/dataProvider/Documents.php');
include_once(ROOT . '/dataProvider/DoctorsNotes.php');

class DocumentHandler {

	private $db;
	private $documents;

	private $pid;
	private $docType;
	private $workingDir;
	private $fileName;

	private $filesPerInstance = 50000;

	/**
	 * @var MatchaCUP
	 */
	private $d;

	/**
	 * @var MatchaCUP
	 */
	private $t;

	private $doctorsnotes;

	function __construct(){
		$this->db = new MatchaHelper();
		return;
	}

	private function setPatientDocumentModel(){
		if(!isset($this->d))
			$this->d = MatchaModel::setSenchaModel('App.model.patient.PatientDocuments');
	}

	private function setPatientDocumentTempModel(){
		if(!isset($this->t))
			$this->t = MatchaModel::setSenchaModel('App.model.patient.PatientDocumentsTemp');
	}

	/**
	 * @param      $params
	 * @param bool $includeDocument
	 *
	 * @return mixed
	 */
	public function getPatientDocuments($params, $includeDocument = false){
		$this->setPatientDocumentModel();
		$records = $this->d->load($params)->all();

		/** lets unset the actual document data */
		if(!$includeDocument && isset($records['data'])){
			foreach($records['data'] as $i => $record){
				unset($records['data'][$i]['document']);
			}
		}
		return $records;
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getPatientDocument($params){
		$this->setPatientDocumentModel();
		$record = $this->d->load($params)->one();
		return $record;
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function addPatientDocument($params){
		$this->setPatientDocumentModel();
		if(is_array($params)){
			foreach($params as $i => $param){
				/** remove the mime type */
				$params[$i]->document = $this->trimBase64($params[$i]->document);

				/** encrypted if necessary */
				if($params[$i]->encrypted){
					$params[$i]->document = MatchaUtils::encrypt($params[$i]->document);
				};
				$params[$i]->hash = hash('sha256', $params[$i]->document);
			}
		}else{
			/** remove the mime type */
			$params->document = $this->trimBase64($params->document);
			/** encrypted if necessary */
			if($params->encrypted){
				$params->document = MatchaUtils::encrypt($params->document);
			};
			$params->hash = hash('sha256', $params->document);
		}

		$results = $this->d->save($params);

		if(is_array($results)){
			foreach($results as &$result){
				$this->handleDocumentData($result);
			}
		}else{
			$this->handleDocumentData($results);
		}
		return $results;
	}

	/**
	 * This logic is to eventually split the document into multiples tables
	 * using the sencha model instance
	 *
	 * @param $document
	 */
	private function handleDocumentData(&$document){

		try{
			$document = (object) $document;
			$instance = floor($document->id / $this->filesPerInstance) + 1;
			$conn = Matcha::getConn();
			$sth = $conn->prepare("SHOW TABLES LIKE 'documents_data_{$instance}'");
			$sth->execute();
			$table = $sth->fetch(PDO::FETCH_ASSOC);
			if($table === false){
				$document_model = MatchaModel::setSenchaModel('App.model.administration.DocumentData', true, $instance);
			}else{
				$document_model = MatchaModel::setSenchaModel('App.model.administration.DocumentData', false, $instance);
			}

			if($document_model === false) {
				throw new Exception("Unable to create App.model.administration.DocumentData model instance '{$instance}'");
			};

			//error_log('DOCUMENT');
			$data = new stdClass();
			$data->pid = $document->pid;
			$data->document = $document->document;
			$record = $document_model->save($data);
			//error_log('DOCUMENT DATA COMPLETED');

			$document->document ='';
			$document->document_instance = $instance;
			$document->document_id = $record->id;
			$sth = $conn->prepare("UPDATE patient_documents SET document = '', document_instance = :doc_ins, document_id = :doc_id WHERE id = :id;");
			$sth->execute([
				':id' => $document->id,
				':doc_ins' => $document->document_instance,
				':doc_id' => $document->document_id
			]);
			//error_log('DOCUMENT COMPLETE');
			unset($document->document);

			unset($data, $record, $document_model);
		}catch(Exception $e){
			error_log('Error Converting Document');
			error_log($e->getMessage());
		}
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function updatePatientDocument($params){
		$this->setPatientDocumentModel();

		if(is_array($params)){
			foreach($params as &$param){
				unset($param->document, $param->hash);
			}
		}else{
			unset($params->document, $params->hash);
		}

		return $this->d->save($params);
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function destroyPatientDocument($params){
		$this->setPatientDocumentModel();
		return $this->d->destroy($params);
	}

	/**
	 * @param $params
	 * @return object|stdClass
	 */
	public function createTempDocument($params){
		$this->setPatientDocumentTempModel();
		$params = (object) $params;
		$record = new stdClass();
		if(isset($params->document) && $params->document != ''){
			$record->document = $params->document;
		}else{
			$this->documents = new Documents();
			$record->document = base64_encode($this->documents->PDFDocumentBuilder((object) $params));;
		}
		$record->create_date = date('Y-m-d H:i:s');
		$record->document_name = isset($params->document_name) ? $params->document_name : '';
		$record = (object) $this->t->save($record);
		unset($record->document);
		return $record;
	}

	/**
	 * @param $params
	 * @return object|stdClass
	 */
	public function createRawTempDocument($params){
		$this->setPatientDocumentTempModel();
		$params = (object) $params;
		$record = new stdClass();
		$record->create_date = date('Y-m-d H:i:s');
		$record->document_name = $params->document_name;
		$record->document = base64_encode($params->document);
		$record = (object) $this->t->save($record);
		unset($record->document);
		return $record;
	}

	public function destroyTempDocument($params){
		$this->setPatientDocumentTempModel();
		return $this->t->destroy($params);
	}

	/**
	 * @param $params
	 *
	 * @return array|mixed
	 */
	public function transferTempDocument($params){
		$this->setPatientDocumentModel();
		$this->setPatientDocumentTempModel();
		$record = $this->t->load($params)->one();
		if($record == false) return ['success' => false];

		$params->document = $record['document'];
		$params->date = date('Y-m-d H:i:s');
		$params->name = 'transferred.pdf';
		unset($params->id);

		$params = $this->addPatientDocument($params);
		unset($params['data']->document);
		return ['success' => true, 'record' => $params['data']];
	}

	private function trimBase64($base64){
		$pos = strpos($base64, ',');
		if($pos === false) return $base64;
		return substr($base64, $pos + 1);
	}



	/**
	 * this will return the document info
	 * @param $params
	 * @return array
	 */
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
			$data->hash = hash_file('sha256', $path);

			$data = $this->d->save($data);

			if(isset($params->DoctorsNote)){
				$this->doctorsnotes = new DoctorsNotes();
				$this->doctorsnotes->addDoctorsNotes($params);
			}

			//print_r($data);

			return ['success' => true, 'doc' => ['id' => $data['data']->id, 'name' => $this->fileName, 'url' => $this->getDocumentUrl(), 'path' => $path]];
		} else{
			return ['success' => false, 'error' => 'Document could not be created'];
		}
	}

	/**
	 * this will return the PDF base64 string
	 * @param $params
	 * @return bool|string
	 */
	public function createPDF($params){
		$this->setPatientDocumentModel();
		$this->documents = new Documents();
		return $this->documents->PDFDocumentBuilder((object)$params);
	}

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
			$data->hash = hash_file('sha256', $src);
			$data->encrypted = $params->encrypted;
			$data = $this->d->save($data);

			return ['success' => true, 'doc' => ['id' => $data['id'], 'name' => $this->fileName, 'url' => $this->getDocumentUrl()]];
		} else{
			return ['success' => false, 'error' => 'File could not be uploaded'];
		}
	}

	/**
	 * @param $id
	 * @return bool
	 */
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

	/**
	 * @return string
	 */
	protected function getDocumentUrl(){
		return $_SESSION['site']['url'] . '/patients/' . $this->pid . '/' . strtolower(str_replace(' ', '_', $this->docType)) . '/' . $this->fileName;
	}

	/**
	 * @param $id
	 * @return string
	 */
	public function getDocumentPathById($id){
		$this->db->setSQL("SELECT * FROM patient_documents WHERE id = '$id'");
		$doc = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return site_path . '/patients/' . $doc['pid'] . '/' . strtolower(str_replace(' ', '_', $doc['docType'])) . '/' . $doc['name'];
	}

	/**
	 * @param $file
	 * @return string
	 */
	protected function reNameFile($file){
		$foo = explode('.', $file['filePath']['name']);
		$ext = end($foo);
		return $this->fileName = $this->setName() . '.' . $ext;
	}

	/**
	 * @return string
	 */
	protected function nameFile(){
		return $this->fileName = $this->setName() . '.pdf';
	}

	/**
	 * @return int
	 */
	protected function setName(){
		$name = time();
		while(file_exists($this->workingDir . '/' . $name)){
			$name = time();
		}
		return $name;
	}

	/**
	 * @param $params
	 * @return string
	 */
	protected function getPatientDir($params){
		if(is_array($params)){
			$this->pid = $params['pid'];
			$this->docType = (isset($params['docType'])) ? $params['docType'] : 'orphanDocuments';
		} else{
			$this->pid = $params->pid;
			$this->docType = (isset($params->docType)) ? $params->docType : 'orphanDocuments';
		}
		$path = site_path . '/patients/' . $this->pid . '/' . strtolower(str_replace(' ', '_', $this->docType)) . '/';
		if(is_dir($path) || mkdir($path, 0777, true)){
			chmod($path, 0777);
		}
		return $this->workingDir = $path;
	}

	/**
	 * @return array
	 */
	public function getDocumentsTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'documenttemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	public function getDefaultDocumentsTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'defaulttemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	public function getHeadersAndFootersTemplates(){
		$this->db->setSQL("SELECT * FROM documents_templates WHERE template_type = 'headerorfootertemplate'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addDocumentsTemplates(stdClass $params){
		$data = get_object_vars($params);
		$data['created_by_uid'] = $_SESSION['user']['id'];
		$this->db->setSQL($this->db->sqlBind($data, 'documents_templates', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateDocumentsTemplates(stdClass $params){
		$data = get_object_vars($params);
		$data['updated_by_uid'] = $_SESSION['user']['id'];
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'documents_templates', 'U', ['id' => $params->id]));
		$this->db->execLog();
		return $params;

	}

	/**
	 * @param $doc
	 * @return array
	 */
	public function checkDocHash($doc){
		$doc = $this->getPatientDocument($doc->id);
		$hash = hash('sha256', $doc['document']);
		return ['success' => $doc['hash'] == $hash, 'msg' => 'Stored Hash:' . $doc['hash'] . '<br>File hash:' . $hash];
	}

	public function convertDocuments($quantity = 100){

		ini_set('memory_limit', '-1');

		$this->setPatientDocumentModel();
		$this->d->addFilter('document_instance', null, '=');

		//error_log('LOAD RECORDS');
		$records = $this->d->load()->limit(0, $quantity);
		//error_log('LOAD RECORDS COMPLETED');

		foreach($records as $record){
			$this->handleDocumentData($record);
		}

		return [ 'success' => true, 'total' => count($records) ];
	}

}

//$d = new DocumentHandler();
//$d->reHashDocs();
