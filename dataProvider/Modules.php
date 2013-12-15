<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

include_once (dirname(__FILE__) . '/../classes/FileManager.php');

class Modules
{
    /**
     * @var string
     */
    private $modulesDir;
    private $db;

    function __construct()
    {
        $this->modulesDir = $_SESSION['root'] . '/modules/';
        $this->db = new MatchaHelper();
        $this->setNewModules();
    }

    /**
     * get all modules inside the modules directory
     * @return array
     */
    public function getAllModules()
    {
        $modules = array();
        foreach (FileManager::scanDir($this->modulesDir) AS $module) {
            $modules[] = $this->getModuleConfig($module);
        }
        return $modules;
    }

    /**
     * get only modules that are set "active":true in conf.json
     * @return array
     */
    public function getActiveModules()
    {
        $modules = array();
        foreach (FileManager::scanDir($this->modulesDir) AS $module) {
            $foo = $this->getModuleConfig($module);
            if ($foo['active']){
                $name = $foo['name'];
                $this->db->setSQL("SELECT * FROM `modules` WHERE name = '$name'");
                $rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
                $modules[] = array_merge($foo, $rec);
            }
        }
        return $modules;
    }

    /**
     * get only site enabled modules
     * @return array
     */
    public function getEnabledModules()
    {
        $modules = array();
        $this->db->setSQL("SELECT * FROM `modules` WHERE enable = 1");
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $m){
	        if(isset($m['name'])){
		        $foo = $this->getModuleConfig($m['name']);
		        if ($foo['active']){
                    $modules[] = $foo;
                    if(isset($foo['actionsAPI'])) unset($foo['actionsAPI']);
                    if(isset($foo['extjs'])) unset($foo['extjs']);
                    if(isset($foo['install'])) unset($foo['install']);
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
    public function getDisabledModules()
    {
        $modules = array();
        $this->db->setSQL("SELECT * FROM `modules` WHERE enable = 0");
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) AS $m){
            $foo = $this->getModuleConfig($m['name']);
            if ($foo['active']) $modules[] = $foo;
        }
        return $modules;
    }
    public function updateModule(stdClass $params)
    {
        $data = array();
        if(isset($params->enable))      $data['enable']     = $params->enable;
        if(isset($params->licensekey))  $data['licensekey'] = $params->licensekey;
        if(isset($params->localkey))    $data['localkey']   = $params->localkey;
        $this->db->setSQL($this->db->sqlBind($data, 'modules', 'U', array('name' => $params->name)));
        $this->db->execLog();
        return $params;
    }

    public function getEnabledModulesAPI()
    {
        $actions = array();
        foreach ($this->getEnabledModules() AS $module) {
            $actions = array_merge($actions, $module['actionsAPI']);
        }
        return $actions;
    }

    /**
     * get modules config data by module name
     * @param $moduleName
     * @return bool|mixed
     */
    private function getModuleConfig($moduleName)
    {
        if (is_dir($this->modulesDir . $moduleName)) {
            $text = file_get_contents($this->modulesDir . $moduleName . '/conf.json');
            return json_decode($text, true);
        }
        return false;
    }

    public function getModuleByName($moduleName){
        $this->db->setSQL("SELECT * FROM `modules` WHERE `name` = '$moduleName'");
        $m = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $foo = $this->getModuleConfig($m['name']);
        if ($foo['active']) {
            return array_merge($m,$foo);
        }else{
            return array();
        }
    }

    /**
     * this method will insert the new active modules in site database if
     * does not exist
     */
    private function setNewModules(){
        foreach (FileManager::scanDir($this->modulesDir) AS $module)
        {
            $ModuleConfig = $this->getModuleConfig($module);
            if ($ModuleConfig['active'])
            {
                $this->db->setSQL("SELECT * FROM modules WHERE `name` = '{$ModuleConfig['name']}'");
                $moduleRecord = $this->db->fetchRecord(PDO::FETCH_ASSOC);
                if(empty($moduleRecord))
                {
                    $data['name'] = $ModuleConfig['name'];
                    $data['enable'] = '0';
                    $data['installed_version'] = $ModuleConfig['version'];
                    $this->db->setSQL($this->db->sqlBind($data, 'modules', 'I'));
                    $this->db->execOnly();
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
