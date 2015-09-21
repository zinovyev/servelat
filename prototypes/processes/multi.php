#! /usr/bin/env php

<?php

/**
 *
 * Multiple processes run prototype.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 *
 */

// Function for building php commands
$buildCommand = function ($cmd) {
    return sprintf(
        '%s -r "%s" &',
        PHP_BINARY,
        stripslashes($cmd)
    );
};

$getProcessKey = function ($stream, array $streamCollection)
{
    if (
        is_resource($stream)
        && get_resource_type($stream) === 'stream'
        && $streamCollection
    ) {
        foreach ($streamCollection as $key => $streamSuite) {
            if (in_array($stream, $streamSuite)) {
                return $key;
            }
        }
    }

    return null;
};

// Commands
$cmds = [
    "echo 'foo\n';sleep(1); echo 'bar\n';",
    "sleep(1); echo '33\n'; sleep(1); echo 33.5, PHP_EOL;\n",
    "sleep(2); echo 34, PHP_EOL;\n",
    "sleep(3); echo 35, PHP_EOL;\n"
];

// Descriptors
$descriptors = [
    ['pipe', 'r'], // Process stdin
    ['pipe', 'w'], // Process stdout
    ['pipe', 'w'], // Process stderr
];

// Execute processes one by one
$processCollection = $streamCollection = [];
foreach ($cmds as $key => $cmd) {
    $process = proc_open(
        $buildCommand($cmd),
        $descriptors,
        $pipes
    );

    $processCollection[$key] = $process;
    $streamCollection[$key] = $pipes;
}

// Run system select to get process output
//
while (count($processCollection)) {
    $readStreams = $closedProcesses = [];
    $writeStreams = $errStreams = null;
    foreach ($streamCollection as $streamSuiteKey => $streamSuite) {
        $streamIn = $streamSuite[0];
        $streamOut = $streamSuite[1];
        $streamErr = $streamSuite[2];
        $readStreams[] = $streamOut;
        $readStreams[] = $streamErr;
    }
    if (isset($readStreams[0])) {
        $num = stream_select($readStreams, $writeStreams, $errStreams, 1);
        if ($num !== false && $num > 0) {


            foreach ($readStreams as $readStream) {

                $line = fgets($readStream);
                if (false !== $line) {
                    $line = trim ($line);
                    var_dump($line);
                } else {
                    $processKey = $getProcessKey($readStream, $streamCollection);
                    $closedProcesses[] = $processKey;
                }

            }

        } else {
            die();
            // ... Exception
        }
    }

    // Close processes and process streams
    //
    foreach ($closedProcesses as $processKey) {
        // Close all streams
        if (isset($streamCollection[$processKey])) {
            foreach ($streamCollection[$processKey] as $stream) {
                if (is_resource($stream) && get_resource_type($stream) === 'stream') {
                    fclose($stream);
                }
            }
            unset($streamCollection[$processKey]);
        }

        // Close process
        if (isset($processCollection[$processKey])) {
            if (is_resource($processCollection[$processKey])) {
                proc_close($processCollection[$processKey]);
            }
            unset($processCollection[$processKey]);
        }
    }
}
