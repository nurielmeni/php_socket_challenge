<?php
require_once 'Client.php';

use SocketServer\Client;

try {
    $client = new SocketServer\Client();
    $client->init();
    $client->run();
} catch (Exception $ex) {
    $client->close();
    die($ex->getMessage());
}