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
class HL7ServerHandler {

	public function start(stdClass $params){

		$server = MatchaModel::setSenchaModel('App.model.administration.HL7Server');
		$data = new stdClass();
		$data->id = $params->id;
		$data->token = $params->token = md5(time());
		$server->save($data);

		$cmd = 'php -f "'.ROOT.'/lib/HL7/HL7Server.php" -- "' . $params->ip . '" ' . $params->port . ' "' . ROOT . '/dataProvider" "HL7Server" "Process" "default" "'.$params->token.'"';
		if (substr(php_uname(), 0, 7) == "Windows"){
			pclose(popen("start /B ". $cmd, "r"));
		}
		else {
			exec($cmd . " > /dev/null &");
		}
		sleep(2);
		return $this->status($params);
	}

	public function stop($params){
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		@socket_connect($socket, $params->ip, $params->port);
		$msg = $params->token;
		@socket_write($socket, $msg, strlen($msg));
		@socket_recv($socket, $response, 1024*10, MSG_WAITALL);
		@socket_close($socket);
		sleep(3);
		return $this->status($params);
	}

	public function status($params){
		$params = (object) $params;
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$status = @socket_connect($socket, $params->ip, $params->port);
		unset($socket);
		$token = isset($params->token) ? $params->token : '';
		return array('online' => $status, 'token' => $token);
	}

}


