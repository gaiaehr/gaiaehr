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
class Snippets {

	/**
	 * @var MatchaCUP
	 */
	private $Snippet = null;

	//------------------------------------------------------------------------------------------------------------------
	// Main Sencha Model Getter and Setters
	//------------------------------------------------------------------------------------------------------------------
	private function setSnippetModel(){
		if($this->Snippet == null)
			$this->Snippet = MatchaModel::setSenchaModel('App.model.patient.encounter.snippetTree');
	}

	public function getSoapSnippetsByCategory($params){
		$this->setSnippetModel();

		if(isset($params->category)){
			$snippets = $this->Snippet->load(array('parentId' => 'root', 'category' => $params->category))->all();
		} elseif(isset($params->id)){
			$snippets = $this->Snippet->load(array('parentId' => $params->id))->all();
		} else{
			$snippets = array();
		}
		return $snippets;
	}

	public function addSoapSnippets($params){
		$this->setSnippetModel();
		return $this->Snippet->save($params);
	}

	public function updateSoapSnippets($params){
		$this->setSnippetModel();
		return $this->Snippet->save($params);
	}

	public function deleteSoapSnippets($params){
		$this->setSnippetModel();
		return $this->Snippet->destroy($params);
	}
}
