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
    if (false === $socketCli = socket_accept($socket)) {
        $raiseSocketError('Socket accept failed.');
    }

    $hi = 'foo';
    if (false === socket_write($socketCli, $hi, strlen($hi))) {
        $raiseSocketError('Failed to write to socket.');
    }

    // Waiting on some input
    while (true) {
        if (false === ($buf = socket_read($socketCli, 2048, PHP_NORMAL_READ))) {
            $raiseSocketError('Failed to read the input values.');
        }

        // No input available
        if ("" === $buf = trim($buf)) {
            continue;
        }

        echo $buf;
    }
    socket_close($socketCli);
}
socket_close($socket);