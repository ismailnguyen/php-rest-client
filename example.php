<?php
require_once('rest_client.php');

$out = RestClient::connect('nguyenismail.com', 80)
    ->get()
    ->run();
	
print_r($out);

?>