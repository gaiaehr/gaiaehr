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

class TemplatePanels {


	/**
	 * @var MatchaCUP
	 */
	private $p;
	/**
	 * @var MatchaCUP
	 */
	private $i;

	function __construct() {
        if(!isset($this->p))
            $this->p = MatchaModel::setSenchaModel('App.model.administration.TemplatePanel');
        if(!isset($this->i))
            $this->i = MatchaModel::setSenchaModel('App.model.administration.TemplatePanelTemplate');
	}

	public function getTemplatePanels($params){
		return $this->p->load($params)->all();
	}

	public function getTemplatePanel($params){
		return $this->p->load($params)->one();
	}

	public function createTemplatePanel($params){
		return $this->p->save($params);
	}

	public function updateTemplatePanel($params){
		return $this->p->save($params);
	}

	public function deleteTemplatePanel($params){
		return $this->p->destroy($params);
	}

	public function getTemplatePanelTemplates($params){
		return $this->i->load($params)->all();
	}

	public function getTemplatePanelTemplate($params){
		return $this->i->load($params)->one();
	}

	public function createTemplatePanelTemplate($params){
		return $this->i->save($params);
	}

	public function updateTemplatePanelTemplate($params){
		return $this->i->save($params);
	}

	public function deleteTemplatePanelTemplate($params){
		return $this->i->destroy($params);
	}
}

