<?php
// this should be obviously taken from .env and not committed to the repo,
// but this is just a trial task

define('API_KEY','kL19QDADDEG_p1fyzQIsTg');
define('DOMAIN', 'deriyenko');

use APN\YourConnector\YourConnector;

require_once('vendor/autoload.php');

$connector = new YourConnector(API_KEY, DOMAIN);
if($connector->authenticate()) {
    $filters = '';
    foreach($connector->doRequest('/leads/filters')->filters as $filter){
        $filters .= $filter->id . '|';
    }
    $filters = substr($filters,0,-1);

    $data = $connector->parse($connector->doRequest('/leads/view/'.$filters)->leads);
    var_dump($data);die();
}