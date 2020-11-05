<?php
namespace SocketServer;

require_once 'SocketHandler.php';
require_once 'Model.php';

use SocketServer\SocketHandler;
use SocketServer\Model;

class Server extends SocketHandler {
    
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

        echo "Info: Binding to socket Host: $this->host:$this->port\n";
        if (socket_bind($this->_sock, $this->host, $this->port) === false) {
            $this->error(socket_strerror(socket_last_error($this->_sock)), "socket_bind()");
        }
        echo "Info: Success.\n\n";

        echo "Info: Listening to bound socket.\n";
        // up to 5 requests will be queued waiting for the socket.
        if (socket_listen($this->_sock, 5) === false) {
            $this->error(socket_strerror(socket_last_error($this->_sock)), "socket_listen()");
        }
        echo "Info: Success.\n\n";

    }

    private function getMenue() 
    {
        $msg = "\nWelcome to the PHP Socket Server. \n" .
            "Please use one of the options:\n\n" .
            "- " . self::OPTION_DISK_SPACE . " - Total disk space on the server.\n" .
            "- " . self::OPTION_PING . " - Ping average to 8.8.8.8.\n" .
            "- " . self::OPTION_SEARCH . " [string] - Get 5 search results from google for the [string].\n" .
            "- " . self::OPTION_EXIT . " - To terminate the connection.\n\n"; 
            "- " . self::OPTION_SHOW_MENU . " - To show the menu.\n\n"; 

        return $this->socketMsg($msg);
    }
    
    private function socketMsg($msg) {
        return $msg . self::TERMINATE_STR;
    }

    public function run() 
    {
        if (!isset($this->_sock)) {
            $this->error("The socket is not ready (make sure to run init() first.", "server_run()");
        }

        if (($serverSocket = socket_accept($this->_sock)) === false) {
            $this->error(socket_strerror(socket_last_error($this->_sock)), "socket_accept()");
        }

        do {
            echo "Listening for client command...\n";
            if (false === ($buff = socket_read($serverSocket, 2048, PHP_NORMAL_READ))) {
                $this->error(socket_strerror(socket_last_error($serverSocket)), "socket_read()");
            }

            $command = trim($buff);
            echo "Command recieved: $command\n";

            // Handler for command: disk_space
            if ($command === self::OPTION_DISK_SPACE) {
                $msg = $this->socketMsg(Model::totalDiskSpace());
                socket_write($serverSocket, $msg);

                continue;
            }

            // Handler for command: ping
            if ($command === self::OPTION_PING) {

                $msg = $this->socketMsg(Model::pingAvg('8.8.8.8'));
                socket_write($serverSocket, $msg);

                continue;
            }

            // Handler for command: search
            if (strpos($command, self::OPTION_SEARCH) === 0) {
                $search = trim(substr($command, strlen(self::OPTION_SEARCH)));
                $msg = $this->socketMsg(Model::googleSearch($search));
                socket_write($serverSocket, $this->socketMsg($msg));

                continue;
            }

            // Handler for command: exit
            if ($command === self::OPTION_EXIT) {

                $msg = $this->socketMsg("Command: $command");
                socket_write($serverSocket, $msg);

                break;
            }

            if ($command === self::OPTION_SHOW_MENU) {
                $bytes = socket_write($serverSocket, $this->getMenue());
            }
            
        } while (true);

        $msg = $this->socketMsg("Connection to server terminated.");
        socket_write($serverSocket, $msg);
        socket_close($serverSocket);
        $this->close();
    }
}
