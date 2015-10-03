<?php

/**
 * The servelat cli file.
 *
 * The servelat console is an entry point of the application.
 * It is used by both server and client components.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */

use Servelat\Tools\ConsoleRunner;

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;

        break;
    }
}

// Run the console instance
ConsoleRunner::run();