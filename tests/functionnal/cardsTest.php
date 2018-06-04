<?php
require __DIR__ . '/../../vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

$goutteClient = new Client();
$guzzleClient = new GuzzleClient(array(
    'timeout' => 60,
));
$goutteClient->setClient($guzzleClient);
$crawler = $goutteClient->request('GET', 'http://127.0.0.1:8000/cards/play');
$crawler = $crawler->filter('.begin');
var_dump($crawler);
