

<!-- ABOUT THE PROJECT -->
## About The Project

This is a project that was requested for showing my abilities in PHP:

### Project Specs
    The task is to create socket server on PHP, listening to port 1111 (console
    script application).
    User can connect to the server from any IP (or locally) by regular telnet
    client, and receive menu with next function items:
    1. Get disk space (total on the Server)
    2. Get ping average to 8.8.8.8
    3. Request string from the user and print top 5 search results from Google
    (URL strings)
    4. Exit

    - OOP usage - must.
    - Server with ability to receive multiple connections simultaneously –
    big advantage.

### Built With
PHP, OOP approach



<!-- GETTING STARTED -->
## Getting Started

It was tested on macOS, might not work properly on a different env. 

### Prerequisites

You need to have a server that can run PHP from command line, preferably macOS.

### Installation


1. Clone/Download the repo
```sh
git clone https://github.com/nurielmeni/php_socket_challenge.git
```
3. Run the Server on one terminal
```sh
php ./runServer.php 
```
4. Run the Client on one terminal
```JS
php ./runClient.php 
```



<!-- USAGE EXAMPLES -->
## Usage

You can test the availiable commands from the menu that will show on the client screen.

<!-- Roadmap -->
## Roadmap

For now only the single thred (One connected client) was implemented.
The multi connection option can be done, if requested.

<!-- CONTACT -->
## Contact

Your Name - nurielmeni@gmail.com

Project Link: [https://github.com/nurielmeni/php_socket_challenge](https://github.com/nurielmeni/php_socket_challenge.git)

