<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ernesto J. Rodriguez <e.rodriguez@certun.com>
 * Date: 3/24/14
 * Time: 12:00 PM
 */

class User {

	/**
	 * @var MatchaCup
	 */
	private $model;

	/**
	 * Set the Sencha Model for later user
	 */
	function __construct(){
		$this->model = MatchaModel::setSenchaModel('App.model.UserModel');
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function Read($params){
		return $this->model->load($params)->all();
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function Create($params){
		return $this->model->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function Update($params){
		return $this->model->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function Destroy($params){
		return $this->model->destroy($params);
	}
} 