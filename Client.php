<?php
namespace SocketServer;

require_once 'SocketHandler.php';

use SocketServer\SocketHandler;

class Client extends SocketHandler {
    
    private $prompt = "Youe option: ";
    
    public function init() 
    {
        /**
         * To open socket and listen
         *  1.  socket_create() - Create the socket (Done by Socket Handler)
         *  2.  socket_bind() - Bind to host and port
         *  3.  socket_listen() - Listen for connection
         *  4.  socket_create() - Accept connection, return socket resource
         *  5.  socket_create() - Create the socket
         */

        echo "Info: Connecting to bound socket.($this->host:$this->port)\n";
        if (socket_connect($this->_sock, $this->host, $this->port) === false) {
            $this->error(socket_strerror(socket_last_error($this->_sock)), "socket_connect()");
        }
        echo "Info: Success.\n\n";
    }

    private function getResponse() {       
        $buf = '';
        
        do {
            echo $buf;
            
            if (false === ($buf = socket_read ($this->_sock, 2048, PHP_NORMAL_READ))) {
                $this->error(socket_strerror(socket_last_error($this->_sock)), "socket_read()");
            }
            
        } while ($buf && trim($buf) !== trim(self::TERMINATE_STR));
    }
    
    public function run() 
    {
        if (!isset($this->_sock)) {
            $this->error("The socket is not ready (make sure to run init() first.", "server_run()");
        }

        socket_write($this->_sock, self::OPTION_SHOW_MENU . "\n");
        $this->getResponse();
        
        do {
            $clientInput = trim(readline($this->prompt));
            if (empty($clientInput)) { continue; }
            if ($clientInput === self::OPTION_EXIT) { break; }
            
            $msg = $clientInput . "\n";
            socket_write($this->_sock, $msg);
            
            $this->getResponse();
        } while (true);

        echo "\n\nConnection to server terminated.\n";
        socket_write($this->_sock, self::OPTION_EXIT, strlen(self::OPTION_EXIT));
        $this->close();
    }
}
