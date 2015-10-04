#! /usr/bin/env php

<?php

/**
 *
 * Socket client prototype.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 *
 */

// Get the IP address for the target host.
$address = gethostbyname('localhost');

// Define specific port
$port = 9091;

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

// Create a socket
if (false === $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
    $raiseSocketError('Failed to create a socket.');
}

// Connecting
if (false === socket_connect($socket, $address, $port)) {
    $raiseSocketError('An error occurred while connecting on socket server.');
}

while ($msg = readline('Type something: ')) {
    socket_write($socket, $msg, strlen($msg));

    while ($out = socket_read($socket, 2048)) {
        var_dump($out);
    }
}

socket_close($socket);