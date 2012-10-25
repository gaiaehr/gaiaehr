<?php
/*
 GaiaEHR (Electronic Health Records)
 Modules.php
 Modules dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/FileManager.php');

class Modules
{
	private $modulesDir = 'modules/';

	function __construct()
	{
		$this -> modulesDir = $_SESSION['root'] . '/modules/';
	}

	public function getAllModules()
	{
		$modules = array();
		foreach (FileManager::scanDir($this->modulesDir) AS $module)
		{
			$modules[] = $this -> getModuleConfig($module);
		}
		return $modules;
	}

	public function getEnabledModules()
	{
		$modules = array();
		foreach (FileManager::scanDir($this->modulesDir) AS $module)
		{
			$foo = $this -> getModuleConfig($module);
			if ($foo['enable'])
				$modules[] = $foo;
		}
		return $modules;
	}

	public function getDisabledModules()
	{
		$modules = array();
		foreach (FileManager::scanDir($this->modulesDir) AS $module)
		{
			$foo = $this -> getModuleConfig($module);
			if (!$foo['enable'])
				$modules[] = $foo;
		}
		return $modules;
	}

	public function getEnabledModulesAPI()
	{
		$actions = array();
		foreach ($this->getEnabledModules() AS $module)
		{
			$actions = array_merge($actions, $module['actionsAPI']);
		}
		return $actions;
	}

	private function getModuleConfig($module)
	{
		if (is_dir($this -> modulesDir . $module))
		{
			$text = file_get_contents($this -> modulesDir . $module . '/conf.json');
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
