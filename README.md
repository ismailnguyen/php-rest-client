## Installation

require_once('rest_client.php');


## Using
$musics = RestClient::connect('nguyenismail.com')
    ->get('musics')
    ->run();

echo $musics;

#