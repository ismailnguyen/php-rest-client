<?php
	class RestException {
		private $_status = null;
		private $_method   = null;
		private $_url    = null;
		private $_params = null;
		
		function __construct($status, $method, $url, $params) {
			$this->_status = $status;
			$this->_method   = $method;
			$this->_url    = $url;
			$this->_params = $params;
		}
		
		function getStatus() {
			return $this->_status;
		}
		
		function getMethod() {
			return $this->_method;
		}
		
		function getUrl() {
			return $this->_url;
		}
		
		function getParams() {
			return $this->_params;
		}
	}
?>