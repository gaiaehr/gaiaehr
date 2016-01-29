<?php
class APIBaseClass {
	public $_root, $_http;
	
	// removing construct method to allow the APIBaseClass to make a 'new_request'
	// api libraries extend this base class, and then call $this->new_request('http://apikeyurl.com');
	
	public function new_request($server, $http = false) {
		$this->_root = $server;
		if($http) {
			$this->_http = $http;
		} else {
			$this->_http = curl_init();
			curl_setopt($this->_http, CURLOPT_RETURNTRANSFER, 1);
		}
	}
	
	public function _request($path, $method, $data=false, $headers=false) {
		# URL encode any available data
        if ($data) $query = http_build_query($data);
		
		// these statements could be combined.. but not ready to take the leap
		if(in_array(strtolower($method), array('get','delete'))) 
			# Add urlencoded data to the path as a query if method is GET or DELETE
			if($data) $path = $path.'?'.$query;
		else {
			# If method is POST or PUT, put the query data into the body
			$body = ($data) ? $query : '';
			curl_setopt($this->_http, CURLOPT_POSTFIELDS, $body);
		}
		
		$url = $this->_root . $path;
		curl_setopt($this->_http, CURLOPT_URL, $url);
		if($headers) curl_setopt($this->_http, CURLOPT_HTTPHEADER, array($headers));
		
		curl_setopt($this->_http, CURLOPT_CUSTOMREQUEST, $method);

		$result = curl_exec($this->_http);
	
		if($result === false) {	
			echo 'Curl error: ' . curl_error($this->_http) . "\n";
		} 
		//curl_close($this->_http);
		
		return $result;
		
	}
	
	public function get($path, $data, $headers = null) {
		return $this->_request($path, 'GET', $data, $headers);
	}
	public function post($path, $data, $headers = null) {
		return $this->_request($path, 'POST', $data, $headers);
	}
	public function put($path, $data, $headers = null) {
		return $this->_request($path, 'PUT', $data, $headers);
	}
	public function delete($path, $data, $headers = null) {
		return $this->_request($path, 'DELETE', $data, $headers);
	}
	
	public function _apiHelper($path, $params)
	{
		$params['format'] = 'json';
		$json = $this->_request($path, 'GET', $params,  array('Accept: application/json'));
		return $json == null ? null : json_decode($json, true);
	}
	
	public function do_query($query_path,$params,$return_param)
	{
	// do some syntax cleanup if improperly written method call is made.
		if(!is_string($query_path) || (!is_string($query_path) && !is_array($params) || (!is_string($return_param)))) return false;

	// query path is location to api query, params is either a string (if only one param) or an
	// associtative array, $return_param is the name of the parameter to lookfor and display...	
		if(!is_array($params)){
	// allow developer to pass paramname<=>attribute might want to use something other than a comma for pair seperation
			 foreach(explode(',',$params) as $key=>$value){
             	if($key==0)unset($params);
    // separate each key value pair with commas, seperate the keyname from the value with a <=>		 	        
                foreach(explode('<=>',$value) as $value2)
                	$params [$value2[0]]=$value2[1];
			 	}	
			 }
		
		$data = $this->_apiHelper($query_path, $params);
		return ($data == null 
				? null 
				: (is_array($data) && array_key_exists($return_param, $data)
					? array_key_exists($return_param, $data) 
						? $data[$return_param] 
						: array()
					: array()
				)
				);
	}
}