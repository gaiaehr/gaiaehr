<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 9/19/12
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/FileManager.php');

class Modules
{
	private $modulesDir = 'modules/';

	function __construct()
    {
		$this->modulesDir = $_SESSION['site']['root'] . '/modules/';
	}

	public function getAllModules()
	{
		$modules = array();
		foreach(FileManager::scanDir($this->modulesDir) AS $module)
		{
			$modules[] = $this->getModuleConfig($module);
		}
		return $modules;
	}

	public function getEnabledModules()
	{
		$modules = array();
		foreach(FileManager::scanDir($this->modulesDir) AS $module)
		{
			$foo = $this->getModuleConfig($module);
			if($foo['enable']) $modules[] = $foo;
		}
		return $modules;
	}

	public function getDisabledModules()
	{
		$modules = array();
		foreach(FileManager::scanDir($this->modulesDir) AS $module){
			$foo = $this->getModuleConfig($module);
			if(!$foo['enable']) $modules[] = $foo;
		}
		return $modules;
	}

	public function getEnabledModulesAPI(){
		$actions = array();
		foreach($this->getEnabledModules() AS $module){
			$actions = array_merge($actions, $module['actionsAPI']);
		}
		return $actions;
	}

	private function getModuleConfig($module)
	{
		if(is_dir($this->modulesDir.$module))
		{
			$text = file_get_contents($this->modulesDir.$module.'/conf.json');
			return json_decode($text, true);
		}
		return false;
	}

}
//print '<pre>';
//$m = new Modules();
//
//print '****All MODULES***** <br>';
//print_r($m->getAllModules());
//print '*****Enabled MODULES***** <br>';
//print_r($m->getEnabledModules());
//print '*****Disabled MODULES***** <br>';
//print_r($m->getDisabledModules());