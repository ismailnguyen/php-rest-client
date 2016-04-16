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
}
?>