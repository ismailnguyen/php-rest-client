<?php
	require_once 'rest_constants.php';
	require_once 'rest_exception.php';

	class RestClient {
		private $_protocol = null;
		private $_host = null;
		private $_port = null;
		private $_user = null;
		private $_pass = null;
		private $_method = null;
		private $_url = null;
		private $_params = array();
		private $_headers = array();
		
		protected function __construct($host, $port, $protocol) {   
			$this->_host     = $host;
			$this->_port     = $port;
			$this->_protocol = $protocol;
		}
		
		static public function connect($host, $port = 80, $protocol = Rest::HTTP) {
			return new self($host, $port, $protocol);
		}
	   
		public function param($key, $value = '') {
			
			if (is_array($key))
				$this->_param($key);
			else
				$this->_params[$key] = $value;
			
			return $this;
		}
		
		private function _param($data) {
			foreach ($data as $value) {
				$value = addslashes($value);
			}
			
			$this->_params = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		   
		public function authentication($user, $pass) {
			$this->_user = $user;
			$this->_pass = $pass;
			return $this;
		}
		
		public function post($url  = null) {
			return $this->_buildRequest(Rest::POST, $url);
		}
		
		public function get($url = null) {
			return $this->_buildRequest(Rest::GET, $url);
		}
		
		public function put($url = null) {
			return $this->_buildRequest(Rest::PUT, $url);
		}
		
		public function delete($url = null) {
			return $this->_buildRequest(Rest::DELETE, $url);
		}
		
		public function header($key, $value) {
			$this->_headers[] = "{$key}: {$value}";
			return $this;
		}
		
		private function _buildRequest($method, $url) {
			$this->_method = $method;
			$this->_url = $this->_buildUrl($url);
			
			return $this;
		}
		
		private function _buildUrl($url) {
			return "{$this->_protocol}://{$this->_host}:{$this->_port}/{$url}";
		}
		
		public function run() {
			$result = array();

			$curl_init = curl_multi_init();
			
			$curl = curl_init();
			
			if(!is_null($this->_user)) {
			   curl_setopt($curl, CURLOPT_USERPWD, $this->_user.':'.$this->_pass);
			}
			
			switch ($this->_method) {
				case Rest::POST:
					curl_setopt($curl, CURLOPT_URL, $this->_url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_params != null ? $this->_params : '');
					break;
					
				case Rest::GET:
					curl_setopt($curl, CURLOPT_URL, $this->_url . '?' . http_build_query($this->_params));
					break;
					
				case Rest::PUT:
					curl_setopt($curl, CURLOPT_URL, $this->_url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_params != null ? $this->_params : '');
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, Rest::PUT);
					break;
					
				case Rest::DELETE:
					curl_setopt($curl, CURLOPT_URL, $this->_url . '?' . http_build_query($this->_params));
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, Rest::DELETE);
					break;
			}
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
			
			curl_multi_add_handle($curl_init, $curl);
		
			$running = null;
			
			do {
				curl_multi_exec($curl_init, $running);
				sleep(0.2);
			}
			while($running > 0);
		
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			switch ($status) {
				case Rest::HTTP_OK:
				case Rest::HTTP_CREATED:
				case Rest::HTTP_ACCEPTED:
					$result = curl_multi_getcontent($curl);
					break;
					
				default:
					$result = new RestException($status, $this->_method, $this->_url, $this->_headers, $this->_params, curl_multi_getcontent($curl));
			}
			
			curl_multi_remove_handle($curl_init, $curl);
			curl_multi_close($curl_init);
			
			return $result;
		}
	}
?>
