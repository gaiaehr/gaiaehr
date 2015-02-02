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

class Roles
{
	/**
	 * @var MatchaCup|bool
	 */
	private $ar;
	/**
	 * @var MatchaCup|bool
	 */
	private $ap;
	/**
	 * @var MatchaCup|bool
	 */
	private $arp;

	function __construct(){
		$this->ar = MatchaModel::setSenchaModel('App.model.administration.AclRoles');
		$this->ap = MatchaModel::setSenchaModel('App.model.administration.AclPermissions');
		$this->arp = MatchaModel::setSenchaModel('App.model.administration.AclRolePermissions');
	}

	/**
	 *
	 * @return mixed
	 */
	public function getRolePerms()
	{
		$perms = $this->ap->load()->all();
		$roles = $this->ar->load()->all();
		$data = array();
//		$first = true;
		foreach($perms as $perm){
			$values = $this->getPermRolesValues($perm['perm_key']);
			$row = array(
				'id' => $perm['id'],
				'perm_name' => $perm['perm_name'],
				'perm_key' => $perm['perm_key'],
				'perm_cat' => $perm['perm_cat'],

			);
			foreach($roles as $role){
				$dataIndex = 'role-'.$role['role_key'];
//				$fields[] = array(
//					'name' => $dataIndex,
//					'type' => 'bool'
//				);
//				if($first){
//					$columns[] = array(
//						'text' => $role['role_name'],
//						'dataIndex' => $dataIndex
//					);
//				}

				$row[$dataIndex] = isset($values[$role['role_key']]) ? $values[$role['role_key']] : 0;
			}
//			$first = false;
			$data[] = $row;
		}
		return array(
//			'fields' => $fields,
//			'columns' => $columns,
			'data' => $data
		);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function updateRolePerm($params)
	{
		if($this->arp == null) $this->arp = MatchaModel::setSenchaModel('App.model.administration.AclRolePermissions');
		if(is_array($params)){
			foreach($params as $param){
				$this->saveRolePerm('front_office', $param->perm_key, $param->{'role-front_office'});
				$this->saveRolePerm('auditor', $param->perm_key, $param->{'role-auditor'});
				$this->saveRolePerm('clinician', $param->perm_key, $param->{'role-clinician'});
				$this->saveRolePerm('physician', $param->perm_key, $param->{'role-physician'});
				$this->saveRolePerm('administrator', $param->perm_key, $param->{'role-administrator'});
				$this->saveRolePerm('emergencyaccess', $param->perm_key, $param->{'role-emergencyaccess'});
				$this->saveRolePerm('referrer', $param->perm_key, $param->{'role-referrer'});
			}
			return $params;
		}
		$this->saveRolePerm('front_office', $params->perm_key, $params->{'role-front_office'});
		$this->saveRolePerm('auditor', $params->perm_key, $params->{'role-auditor'});
		$this->saveRolePerm('clinician', $params->perm_key, $params->{'role-clinician'});
		$this->saveRolePerm('physician', $params->perm_key, $params->{'role-physician'});
		$this->saveRolePerm('administrator', $params->perm_key, $params->{'role-administrator'});
		$this->saveRolePerm('emergencyaccess', $params->perm_key, $params->{'role-emergencyaccess'});
		$this->saveRolePerm('referrer', $params->perm_key, $params->{'role-referrer'});
		return $params;
	}



	/**
	 * @param $permKey
	 * @return array
	 */
	protected function getPermRolesValues($permKey){
		$perms = $this->arp->buildSQL(array(
			'SELECT' => "*",
			'WHERE' => "perm_key = '$permKey'"
		))->all();
		$data = array();
		foreach($perms as $perm){
			$data[$perm['role_key']] = $perm['value'];
		}
		return $data;
	}

	/**
	 * @param $roleKey
	 * @param $permKey
	 * @param $val
	 * @return void
	 */
	private function saveRolePerm($roleKey, $permKey, $val)
	{
		$perm = $this->arp->load(array('role_key' => $roleKey, 'perm_key' => $permKey))->one();
		if($perm !== false){
			$perm['value'] = $val;
			$this->arp->save((object) $perm);
		}
		else{
			$perm = new stdClass();
			$perm->role_key = $roleKey;
			$perm->perm_key = $permKey;
			$perm->value = $val;
			$this->arp->save((object) $perm);
		}
	}
}

//
//$r = new Roles();
//print '<pre>';
//print_r($r->getRoleGrid());