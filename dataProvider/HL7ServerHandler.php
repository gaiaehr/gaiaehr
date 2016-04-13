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

	public function start(stdClass $params) {
		$server = MatchaModel::setSenchaModel('App.model.administration.HL7Server');
		$data = new stdClass();
		$data->id = $params->id;
		$data->token = $params->token = md5(time());
		$server->save($data);
		$foo = parse_url(URL);
		$url = $foo['scheme'] . '://' . $foo['host'] . $foo['path'] . '/lib/HL7/HL7Server.php';
		$curl = curl_init();
		$post = [
			'host' => $params->ip,
			'port' => $params->port,
			'path' => ROOT . '/dataProvider',
			'class' => 'HL7Server',
			'method' => 'Process',
			'site' => $_SESSION['user']['site'],
			'token' => $params->token
		];
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl, CURLOPT_USERAGENT, 'api');
		curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_exec($curl);
		return $this->status($params);
	}

	public function stop($params) {
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		@socket_connect($socket, $params->ip, $params->port);
		$msg = $params->token;
		@socket_write($socket, $msg, strlen($msg));
		@socket_recv($socket, $response, 1024 * 10, MSG_WAITALL);
		@socket_close($socket);
		sleep(3);
		return $this->status($params);
	}

	public function status($params) {
		$params = (object)$params;
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$status = @socket_connect($socket, $params->ip, $params->port);
		unset($socket);
		$token = isset($params->token) ? $params->token : '';
		return array(
			'online' => $status,
			'token' => $token
		);
	}

}


