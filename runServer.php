<?php
require_once 'Server.php';

use SocketServer\Server;

try {
    $server = new Server();
    $server->init();
    $server->run();
} catch (Exception $ex) {
    $server->close();
    die($ex->getMessage());
}