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
namespace modules\imageforms\dataProvider;

class ImageForm {

	/**
	 * @var bool|\MatchaCUP
	 */
	private $i;

	function __construct(){
		\Matcha::$__app = ROOT.'/modules';
		$this->i = \MatchaModel::setSenchaModel('Modules.imageforms.model.PatientImage');
		\Matcha::$__app = ROOT.'/app';
	}

	public function getImages($params){
		return $this->i->load($params)->all();
	}

	public function getImage($params){
		return $this->i->load($params)->one();
	}

	public function addImage($params){
		return $this->i->save($params);
	}

	public function updateImage($params){
		return $this->i->save($params);
	}

	public function destroyImage($params){
		return $this->i->destroy($params);
	}

}