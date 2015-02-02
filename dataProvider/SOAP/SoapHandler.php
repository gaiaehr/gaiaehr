<?php
class SoapHandler {

	/**
	 * @var stdClass
	 */
	private $params;

	private $site;

	private $facility;

	private $provider;

	private $patient;

	function constructor($params){
		$this->params = $params;
		$this->site = isset($params->Site) ? $params->Site : 'default';
		$this->facility = isset($params->Facility) ? $params->Facility : '1';
		if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);
		include_once(str_replace('\\', '/', dirname(dirname(dirname(__FILE__)))) . '/registry.php');
		include_once(ROOT . "/sites/{$this->site}/conf.php");
		include_once(ROOT . '/classes/MatchaHelper.php');

		if(isset($params->Provider)){
			$this->getProvider($params->Provider);
		}

		if(isset($params->Patient)){
			$this->getPatient($params->Patient);
		}

		if(isset($params->Patient)){
			$this->getPatient($params->Patient);
		}

	}

	/**
	 * @return bool
	 */
	protected function isAuth(){
		require_once(ROOT . '/dataProvider/Applications.php');
		$Applications = new Applications();
		$access = $Applications->hasAccess($this->params->SecureKey);
		unset($Applications);
		return $access;
 	}

	/**
	 * @param $params
	 * @return array
	 */
	public function GetCCDDocument($params){
		$this->constructor($params);

		$_SESSION['user']['facility'] = $this->facility;

		$ccd = new CCDDocument();
		$ccd->setPid($params->pid);
		$ccd->setTemplate('toc');
		$ccd->createCCD();

		if(!$this->isAuth()){
			return array(
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			);
		}
		return array(
			'Success' => true,
			'Document' => $ccd->get()
		);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function UploadPatientDocument($params){
		$this->constructor($params);

		if(!$this->isAuth()){
			return array(
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			);
		}

		if(!$this->isPatientValid()){
			return array(
				'Success' => false,
				'Error' => 'Error: No Valid Patient Found'
			);
		}

		if(!$this->isProviderValid()){
			return array(
				'Success' => false,
				'Error' => 'Error: No Valid Provider Found'
			);
		}

		$document = new stdClass();
		$document->eid = 0;
		$document->pid = $this->patient->pid;
		$document->uid = $this->provider->id;
		$document->name = 'SoapUpload.pdf';

		$document->date = $params->Document->Date;
		$document->title = $params->Document->Title;
		$document->document = $params->Document->Base64Document;

		$document->docType = isset($params->Document->Category) ? $params->Document->Category : 'General';
		$document->note = isset($params->Document->Notes) ? $params->Document->Notes : '';
		$document->encrypted = isset($params->Document->Encrypted) ? $params->Document->Encrypted : false;;

		require_once(ROOT . '/dataProvider/DocumentHandler.php');
		$DocumentHandler =  new DocumentHandler();
		$result = $DocumentHandler->addPatientDocument($document);
		unset($DocumentHandler);

		return array(
			'Success' => isset($result['data']->id)
		);
	}

	/**
	 * @param $provider
	 *
	 * @return mixed|object
	 */
	private function getProvider($provider){
		require_once(ROOT . '/dataProvider/User.php');
		$User = new User();
		$provider = $User->getUserByNPI($provider->NPI);
		unset($User);
		return $this->provider = $provider !== false ? (object) $provider : $provider;
	}

	/**
	 * @param $patient
	 *
	 * @return mixed|object
	 */
	private function getPatient($patient){
		require_once(ROOT . '/dataProvider/Patient.php');
		$Patient = new Patient();
		if(isset($patient->RecordNumber)){
			$patient = $Patient->getPatientByPublicId($patient->RecordNumber);
		}else{
			$patient = $Patient->getPatientByPid($patient->Pid);
		}
		unset($Patient);
		return $this->patient = $patient !== false ? (object) $patient : $patient;
	}

	/**
	 * @return bool
	 */
	private function isPatientValid(){
		return $this->patient !== false;
	}

	/**
	 * @return bool
	 */
	private function isProviderValid(){
		return $this->provider !== false;
	}

	/**
	 * @param $error
	 */
	private function consolelog($error){
		ob_start();
		print_r($error);
		$contents = ob_get_contents();
		ob_end_clean();
		error_log($contents);
	}

}
