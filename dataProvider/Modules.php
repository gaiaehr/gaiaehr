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

include_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/classes/FileManager.php');

class Modules {
	/**
	 * @var string
	 */
	private $modulesDir;

	/**
	 * @var MatchaCUP
	 */
	private $m;

	function __construct() {
		$this->modulesDir = ROOT . '/modules/';
		$this->m = MatchaModel::setSenchaModel('App.model.administration.Modules');
		$this->setNewModules();

	}

	/**
	 * get all modules inside the modules directory
	 * @return array
	 */
	public function getAllModules() {
		$modules = array();
		foreach(FileManager::scanDir($this->modulesDir) AS $module){
			$modules[] = $this->getModuleConfig($module);
		}
		return $modules;
	}

	/**
	 * get only modules that are set "active":true in conf.json
	 * @return array
	 */
	public function getActiveModules() {
		$modules = array();
		foreach(FileManager::scanDir($this->modulesDir) AS $module){
			$foo = $this->getModuleConfig($module);
			if($foo['active']){
				$record = $this->m->load(array('name' => $foo['name']))->one();
				if($record === false)
					continue;
				$modules[] = array_merge($foo, $record);
			}
		}
		return $modules;
	}

	/**
	 * get only site enabled modules
	 * @return array
	 */
	public function getEnabledModules() {
		$modules = array();
		$records = $this->m->load(array('enable' => 1))->all();
		foreach($records AS $m){
			if(isset($m['name'])){
				$foo = $this->getModuleConfig($m['name']);
				if($foo['active']){
					$modules[] = $foo;
					if(isset($foo['actionsAPI']))
						unset($foo['actionsAPI']);
					if(isset($foo['extjs']))
						unset($foo['extjs']);
					if(isset($foo['install']))
						unset($foo['install']);
					$_SESSION['site']['modules'][$foo['name']] = $foo;
				}
			}
		}
		return $modules;
	}

	/**
	 * get only site disabled modules
	 * @return array
	 */
	public function getDisabledModules() {
		$modules = array();
		$records = $this->m->load(array('enable' => 0))->all();
		foreach($records AS $m){
			$foo = $this->getModuleConfig($m['name']);
			if($foo['active'])
				$modules[] = $foo;
		}
		return $modules;
	}

	public function updateModule($params) {
		if(is_array($params)){
			foreach($params as $param){
				$conf = (object) self::getModuleConfig($param->name);
				if(isset($conf->permissions)){
					ACL::updateModulePermissions($param->title, $conf->permissions, $param->enable);
				}
			}
		}else{
			$conf = (object) self::getModuleConfig($params->name);
			if(isset($conf->permissions)){
				ACL::updateModulePermissions($params->title, $conf->permissions, $params->enable);
			}


		}
		return $this->m->save($params);
	}

	public function getEnabledModulesAPI() {
		$actions = array();
		foreach($this->getEnabledModules() AS $module){
			$actions = array_merge($actions, $module['actionsAPI']);
		}
		return $actions;
	}

	/**
	 * get modules config data by module name
	 * @param $moduleName
	 * @return bool|mixed
	 */
	private function getModuleConfig($moduleName) {
		if(is_dir($this->modulesDir . $moduleName)){
			$text = file_get_contents($this->modulesDir . $moduleName . '/conf.json');
			return json_decode($text, true);
		}
		return false;
	}

	public function getModuleByName($moduleName) {
		$records = $this->m->load(array('name' => $moduleName))->one();
		$foo = $this->getModuleConfig($records['name']);
		if($foo['active']){
			return array_merge($records, $foo);
		} else {
			return array();
		}
	}

	/**
	 * this method will insert the new active modules in site database if
	 * does not exist
	 */
	private function setNewModules() {
		foreach(FileManager::scanDir($this->modulesDir) AS $module){
			$ModuleConfig = $this->getModuleConfig($module);
			if($ModuleConfig['active']){
				$moduleRecord = $this->m->load(array('name' => $ModuleConfig['name']))->one();

				if(empty($moduleRecord)){
					$data = new stdClass();
					$data->title = $ModuleConfig['title'];
					$data->name = $ModuleConfig['name'];
					$data->description = $ModuleConfig['description'];
					$data->enable = '0';
					$data->installed_version = $ModuleConfig['version'];
					$this->m->save($data);
				}
			}
		}
		return;
	}
}

//print '<pre>';
//$m = new Modules();

//print '****All MODULES***** <br>';
//print_r($m->getAllModules());
//print '*****Active MODULES***** <br>';
//print_r($m->getActiveModules());
//print '*****Enabled MODULES***** <br>';
//print_r($m->getEnabledModules());
//print '*****Disabled MODULES***** <br>';
//print_r($m->getDisabledModules());

//print_r($m->getModuleByName('druginteractions'));
//print 'hello';
