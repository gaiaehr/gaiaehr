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

class Globals extends MatchaHelper {

	/**
	 * @var bool|MatchaCUP
	 */
	private static $g = null;

	/**
	 * @return array
	 */
	public static function getGlobals()	{
        if(self::$g == null) self::$g = MatchaModel::setSenchaModel('App.model.administration.Globals');
		return self::$g->load()->all();
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateGlobals($params)	{
        if(self::$g == null) self::$g = MatchaModel::setSenchaModel('App.model.administration.Globals');
		$params = self::$g->save($params);
		$this->setGlobals();
		return $params;
	}

	/**
	 * @static
	 * @return mixed
	 */
	public static function setGlobals()	{
		new MatchaHelper();
        if(self::$g == null) self::$g = MatchaModel::setSenchaModel('App.model.administration.Globals');
		foreach(self::$g->load()->all() as $setting){
			$_SESSION['globals'][$setting['gl_name']] = $setting['gl_value'];
		}
		$_SESSION['globals']['timezone_offset'] = -14400;
		$_SESSION['globals']['date_time_display_format'] = $_SESSION['globals']['date_display_format'] . ' ' . $_SESSION['globals']['time_display_format'];
		return $_SESSION['globals'];
	}

	/**
	 * @return array
	 */
	public static function getGlobalsArray(){
		if(self::$g == null) self::$g = MatchaModel::setSenchaModel('App.model.administration.Globals');
		$gs = array();
		foreach(self::$g->load()->all() AS $g){
			$gs[$g['gl_name']] = $g['gl_value'];
		}
		return $gs;
	}

}

//print '<pre>';
//$g = new Globals();
//print_r($g->getGlobalsArray());
