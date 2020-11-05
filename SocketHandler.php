<?php
namespace SocketServer;

abstract class SocketHandler {
    const OPTION_SHOW_MENU = 'show menu';
    const OPTION_DISK_SPACE = 'disk_space';
    const OPTION_PING = 'ping';
    const OPTION_SEARCH = 'search:';
    const OPTION_EXIT = 'exit';
    const TERMINATE_STR = "\n###\n";

    protected $host;

    protected $port;

    protected $_sock;

    public function __construct($host = '127.0.0.1', $port = 1111) 
    {
        if (!isset($host) || !isset($port)) {
            $this->error("You must provide a host and port to run the server.\n");
        }

        $this->host = $host;
        $this->port = $port;

        if (($this->_sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            $this->error("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
        }
    }

    protected function close() {
        if (isset($this->sock)) {
            socket_close($this->sock);
        }
    }

    abstract function init();

    abstract function run();

    protected function error($msg, $context = '') {
        $this->close();

        die("\nError: $context" . empty($context) ? '' : '-' . " $msg\n");
    }
}