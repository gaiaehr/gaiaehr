<?php
class SoapHandler {

	/**
	 * @var array
	 */
	private $auth = array();

	/**
	 * @param $header
	 */
	public function Auth($header){
		foreach($header->item AS $item){
			$this->auth[$item->key] = $item->value;
		}
	}

	/**
	 * @return bool
	 */
	protected function isAuth(){
		return $this->auth['Username'] == 'admin' && $this->auth['Password'] == 'pass';
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getDocument($params){

		if(!isset($_SESSION)){
			session_name('GaiaEHR');
			session_start();
			session_cache_limiter('private');
		}

		$root = dirname(dirname(dirname(__FILE__)));
		$site = isset($params->site) ? $params->site : 'default';
		$facility = isset($params->facility) ? $params->facility : '1';

		include_once("$root/sites/$site/conf.php");
		include_once("$root/dataProvider/CCDDocument.php");

		$_SESSION['user']['facility'] = $facility;

		$ccd = new CCDDocument();
		$ccd->setPid($params->pid);
		$ccd->setTemplate('toc');
		$ccd->createCCD();



		if(!$this->isAuth()){
			return array(
				'success' => false,
				'message' => 'Error: HTTP 403 Access Forbidden'
			);
		}
		return array(
			'success' => true,
			'document' => $ccd->get()
		);
	}
}


