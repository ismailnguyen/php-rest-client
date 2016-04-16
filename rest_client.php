<?php
require_once 'rest.php';

class RestClient
{
    private $_protocol = null;
    private $_host = null;
    private $_port = null;
    private $_user = null;
    private $_pass = null;
	private $_method = null;
	private $_url = null;
	private $_params = array();
	private $_headers = array();
	
	protected function __construct($host, $port, $protocol)
    {   
        $this->_host     = $host;
        $this->_port     = $port;
        $this->_protocol = $protocol;
    }
	
	static public function connect($host, $port = 80, $protocol = Rest::HTTP)
    {
        return new self($host, $port, $protocol);
    }
   
    public function param($key, $value = null)
    {
        $this->_params[$key] = $value;
        return $this;
    }
       
    public function authentification($user, $pass)
    {
        $this->_user = $user;
        $this->_pass = $pass;
		return $this;
    }
	
    public function post($url  = null)
    {
        return $this->_buildRequest(Rest::POST, $url);
    }
	
	public function get($url = null)
    {
        return $this->_buildRequest(Rest::GET, $url);
    }
	
	public function put($url = null)
    {
        return $this->_buildRequest(Rest::PUT, $url);
    }
	
	public function delete($url = null)
    {
        return $this->_buildRequest(Rest::DELETE, $url);
    }
	
	public function setHeaders($headers)
    {
        $this->_headers = $headers;
        return $this;
    }
	
	private function _buildRequest($method, $url) {
		$this->_method = $method;
		$this->_url = $this->_buildUrl($url);
		
		return $this;
	}
	
	private function _buildUrl($url = null)
    {
        return "{$this->_protocol}://{$this->_host}:{$this->_port}/{$url}";
    }
}
?>