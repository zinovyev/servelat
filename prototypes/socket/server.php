#! /usr/bin/env php

<?php

/**
 *
 * Socket server prototype.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 *
 */

error_reporting(E_ALL);

// Allow the script to hang around waiting for connections.
set_time_limit(0);

// Server address
$address = '127.0.0.1';

// Server port
$port = '9091';

// List of clients
$clients = [];

// Raise socket exception
//
$raiseSocketError = function ($msg = '') {
    $code = socket_last_error();
    $msg = sprintf(
        '%s%s [%d]',
        $msg ? trim(trim($msg), '.') . '. ' : '',
        socket_strerror($code),
        $code
    );

    throw new \RuntimeException($msg, $code);
};

printf("Socket server prototype. Running on %s:%d\n", $address, $port);

// Create socket
if (false === $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
    $raiseSocketError('Could not create socket.');
}

if (false === socket_bind($socket, $address, $port)) {
    $raiseSocketError('Failed to bind socket to the specific address.');
}

if (false === socket_listen($socket, 5)) {
    $raiseSocketError('Failed to listen on socket.');
}

// Waiting on the new connection
while (true) {

    // Build read streams
    $read = array_merge([$socket], $clients);
    $write = $except = null;

    // Run system select
    // Set up a blocking call to socket_select
    if(false !== $num = socket_select($read, $write, $except, 0, 500)) {

        // Got new connection - register new client
        if (in_array($socket, $read)) {
            if (false === $clients[] = socket_accept($socket)) {
                $raiseSocketError('Failed to accept a connection from client.');
            }
        }

        // Handle input values
        foreach ($clients as $key => $client) {
            if (in_array($client, $read)) {

                if (false === $buf = socket_read($client, 2048, PHP_NORMAL_READ)) {
                    $raiseSocketError('Could not read from socket!');
                } elseif ("" === $buf = trim($buf)) {
                    continue;
                } else {
                    socket_write($client, strtoupper($buf), strlen($buf));
                }

            }
        }
    }

}
socket_close($socketCli); socket_close($socket);