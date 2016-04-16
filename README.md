## Install

```
require_once('rest_client.php');
```

## Usage

```
$musics = RestClient::connect('localhost')
    ->get('musics')
    ->run();

```

## Examples


Use different port and protocol:
```
$musics = RestClient::connect('nguyenismail.com', 8080, Rest::HTTPS)
    ->get('musics')
    ->run();

```

Add parameters to request:
```
$musics = RestClient::connect('localhost')
    ->get('musics')
	->param('genre', 'hiphop')
	->param('order', 'desc')
	->param('onlyTitle')
    ->run();

```
